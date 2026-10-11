<?php

namespace App\Services;

use App\Models\Question_detail;
use App\Models\Question_paper;
use App\Models\Question_paper_question;
use App\Models\Question_paper_subject;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class QuestionPaperPdfService
{
    /** @var list<string> */
    protected array $tempFiles = [];

    public function download(Question_paper $paper, string $kind): Response
    {
        @set_time_limit(180);
        @ini_set('memory_limit', '512M');

        try {
            $prepareImages = $kind !== 'answer';
            $data = $this->buildData($paper, $prepareImages);
            $total = collect($data['subject_list'])->sum(fn ($s) => count($s->question_list ?? []));
            if ($total === 0) {
                abort(404, 'No questions found to export.');
            }

            $view = $kind === 'answer' ? 'template.exam_answer_pdf' : 'template.exam_question_pdf';
            $safeName = preg_replace('/[^A-Za-z0-9_\-]+/', '_', (string) $paper->name) ?: 'question_paper';
            $filename = $kind === 'answer'
                ? $safeName.'_answers.pdf'
                : $safeName.'_questions.pdf';

            $pdf = Pdf::setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => false,
                'isPhpEnabled' => false,
                'dpi' => 72,
                'chroot' => public_path(),
                'defaultFont' => 'DejaVu Sans',
            ])->loadView($view, $data);
            $pdf->setPaper('a4', 'portrait');

            $binary = $pdf->output();

            return response($binary, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
                'Content-Length' => (string) strlen($binary),
                'Cache-Control' => 'private, max-age=0, must-revalidate',
            ]);
        } finally {
            $this->cleanupTempFiles();
        }
    }

    public function buildData(Question_paper $paper, bool $prepareImages = true): array
    {
        $languageId = function_exists('getDefaultLanguage') ? (int) getDefaultLanguage() : 1;
        $logoPath = public_path('web/images/logo.png');
        $logoSrc = is_file($logoPath) ? $this->fileUrl($logoPath) : '';

        $subjectList = Question_paper_subject::select(['question_paper_subjects.*', 'subjects.name as subject_name'])
            ->leftJoin('subjects', 'question_paper_subjects.subject_id', '=', 'subjects.id')
            ->where('question_paper_id', $paper->id)
            ->orderBy('question_paper_subjects.id')
            ->get();

        $slots = Question_paper_question::select([
                'question_paper_questions.id as paper_question_id',
                'question_paper_questions.question_paper_subject_id',
                'question_paper_questions.question_number',
                'question_paper_questions.question_id',
                'questions.answer',
                'questions.solution_video_link',
            ])
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->where('question_paper_questions.question_paper_id', $paper->id)
            ->orderBy('question_paper_questions.question_number')
            ->orderBy('question_paper_questions.id')
            ->get();

        $questionIds = $slots->pluck('question_id')->filter()->unique()->values()->all();
        $details = Question_detail::whereIn('question_id', $questionIds ?: [0])
            ->orderByRaw('CASE WHEN language_id = ? THEN 0 ELSE 1 END', [$languageId])
            ->get()
            ->groupBy('question_id')
            ->map(fn ($rows) => $rows->first());

        if ($subjectList->isEmpty() && $slots->isNotEmpty()) {
            $fallback = new Question_paper_subject([
                'question_paper_id' => $paper->id,
                'subject_id' => 0,
                'status' => 1,
            ]);
            $fallback->id = 0;
            $fallback->subject_name = 'Questions';
            $subjectList = collect([$fallback]);
        }

        foreach ($subjectList as $subject) {
            $sectionSlots = $slots->where('question_paper_subject_id', $subject->id);
            if ((int) $subject->id === 0) {
                $sectionSlots = $slots;
            }
            $subject->question_list = $sectionSlots->values()->map(function ($row) use ($details, $prepareImages) {
                $detail = $details->get($row->question_id);
                if ($prepareImages) {
                    $row->question_text = $this->prepareHtml($detail->question_text ?? '');
                    $row->option1 = (int) ($detail->is_option1_image ?? 0)
                        ? ($detail->option1 ?? '')
                        : $this->prepareHtml($detail->option1 ?? '');
                    $row->option2 = (int) ($detail->is_option2_image ?? 0)
                        ? ($detail->option2 ?? '')
                        : $this->prepareHtml($detail->option2 ?? '');
                    $row->option3 = (int) ($detail->is_option3_image ?? 0)
                        ? ($detail->option3 ?? '')
                        : $this->prepareHtml($detail->option3 ?? '');
                    $row->option4 = (int) ($detail->is_option4_image ?? 0)
                        ? ($detail->option4 ?? '')
                        : $this->prepareHtml($detail->option4 ?? '');
                } else {
                    $row->question_text = '';
                    $row->option1 = '';
                    $row->option2 = '';
                    $row->option3 = '';
                    $row->option4 = '';
                }
                $row->question_image = $prepareImages ? ($detail->question_image ?? null) : null;
                $row->is_option1_image = $prepareImages ? (int) ($detail->is_option1_image ?? 0) : 0;
                $row->is_option2_image = $prepareImages ? (int) ($detail->is_option2_image ?? 0) : 0;
                $row->is_option3_image = $prepareImages ? (int) ($detail->is_option3_image ?? 0) : 0;
                $row->is_option4_image = $prepareImages ? (int) ($detail->is_option4_image ?? 0) : 0;
                $row->id = $row->question_id;

                return $row;
            });
        }

        return [
            'details' => $paper,
            'subject_list' => $subjectList,
            'pdf_logo' => $logoSrc,
        ];
    }

    protected function prepareHtml(?string $html): string
    {
        $html = (string) $html;
        if ($html === '') {
            return '';
        }

        // Rewrite only URL-style src values (not absolute filesystem paths).
        $html = preg_replace_callback(
            '#\bsrc=(["\'])((?:https?://[^"\']+)?/uploads/question/[^"\']+)\1#i',
            function ($m) {
                $pathPart = parse_url($m[2], PHP_URL_PATH) ?: $m[2];
                $path = public_path('uploads/question/'.basename(urldecode($pathPart)));
                if (!is_file($path)) {
                    return 'src=""';
                }

                return 'src="'.$this->fileUrl($path).'"';
            },
            $html
        ) ?? $html;

        // Convert inline base64 images to temp files (faster + smaller than huge data URIs).
        $html = preg_replace_callback(
            '#(<img\b[^>]*\bsrc=)(["\'])(data:image/(png|jpe?g|gif|webp);base64,([A-Za-z0-9+/=\s]+))\2([^>]*>)#i',
            function ($m) {
                $bin = base64_decode(preg_replace('/\s+/', '', $m[5]), true);
                if ($bin === false || strlen($bin) < 8) {
                    return '';
                }
                $ext = strtolower($m[4]);
                if ($ext === 'jpeg') {
                    $ext = 'jpg';
                }
                $path = $this->tempImagePath($ext);
                file_put_contents($path, $bin);
                $this->tempFiles[] = $path;

                return $m[1].'"'.$this->fileUrl($path).'" style="max-width:180px;height:auto;"'.$m[6];
            },
            $html
        ) ?? $html;

        // Drop remaining remote images (deadlock risk with artisan serve).
        $html = preg_replace('#<img\b[^>]*\bsrc=["\']https?://[^"\']+["\'][^>]*>#i', '', $html) ?? $html;

        return $html;
    }

    protected function tempImagePath(string $ext): string
    {
        // Keep temp images under public/ so DomPDF chroot can read them.
        $dir = public_path('uploads/question/_pdf_tmp');
        if (!is_dir($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        return $dir.DIRECTORY_SEPARATOR.uniqid('pdf_', true).'.'.$ext;
    }

    protected function fileUrl(string $path): string
    {
        // DomPDF accepts absolute filesystem paths; avoid http(s) and fragile file:// URLs.
        return str_replace('\\', '/', $path);
    }

    protected function cleanupTempFiles(): void
    {
        foreach ($this->tempFiles as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
        $this->tempFiles = [];
    }
}
