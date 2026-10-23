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

    /** @var array<string, string> */
    protected array $imageCache = [];

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
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
                'dpi' => 96,
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
                if ($prepareImages && $detail) {
                    $row->question_text = $detail->question_text ?? '';
                    $row->question_image = $detail->question_image ?? null;
                    foreach ([1, 2, 3, 4] as $n) {
                        $imgFlag = 'is_option'.$n.'_image';
                        $optKey = 'option'.$n;
                        $row->$imgFlag = (int) ($detail->$imgFlag ?? 0);
                        $row->$optKey = $detail->$optKey ?? '';
                    }
                    $this->prepareLoadedQuestion($row);
                } else {
                    $row->question_text = '';
                    $row->option1 = $row->option2 = $row->option3 = $row->option4 = '';
                    $row->is_option1_image = $row->is_option2_image = $row->is_option3_image = $row->is_option4_image = 0;
                    $row->question_image = null;
                }
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

    public function prepareLoadedQuestion(object $row): void
    {
        $row->question_text = $this->prepareHtml($row->question_text ?? '');
        foreach ([1, 2, 3, 4] as $n) {
            $imgFlag = 'is_option'.$n.'_image';
            $optKey = 'option'.$n;
            $raw = (string) ($row->$optKey ?? '');
            $isImg = (int) ($row->$imgFlag ?? 0) === 1 || $this->looksLikeImageFilename($raw);
            if ($isImg) {
                $resolved = $this->resolveImagePath(trim(strip_tags($raw)) !== '' ? trim(strip_tags($raw)) : $raw);
                if ($resolved !== '') {
                    $row->$optKey = $resolved;
                    $row->$imgFlag = 1;
                    continue;
                }
            }
            $row->$optKey = $this->prepareHtml($raw);
            $row->$imgFlag = 0;
        }
        $row->question_image = $this->resolveImagePath((string) ($row->question_image ?? ''));
    }

    protected function prepareHtml(?string $html): string
    {
        $html = (string) $html;
        if (trim($html) === '') {
            return '';
        }

        $html = preg_replace_callback(
            '#<img\b[^>]*\bsrc=(["\'])(data:image/(png|jpe?g|gif|webp);base64,([A-Za-z0-9+/=\s]+))\1[^>]*>#i',
            function ($m) {
                $bin = base64_decode(preg_replace('/\s+/', '', $m[4]), true);
                if ($bin === false || strlen($bin) < 8) {
                    return '';
                }
                $ext = strtolower($m[3]) === 'jpeg' ? 'jpg' : strtolower($m[3]);
                $path = $this->tempImagePath($ext);
                file_put_contents($path, $bin);
                $this->tempFiles[] = $path;
                $info = @getimagesizefromstring($bin);

                return $this->imgTag($path, $info[0] ?? null, $info[1] ?? null);
            },
            $html
        ) ?? $html;

        $html = preg_replace_callback(
            '#<img\b[^>]*\bsrc\s*=\s*(["\'])([^"\']+)\1[^>]*>#i',
            function ($m) {
                if (stripos($m[2], 'data:') === 0) {
                    return $m[0];
                }
                $path = $this->resolveImagePath($m[2]);
                if ($path === '') {
                    return '';
                }

                return $this->imgTag($path);
            },
            $html
        ) ?? $html;

        $html = preg_replace('#<img\b[^>]*\bsrc=["\']https?://[^"\']+["\'][^>]*>#i', '', $html) ?? $html;
        $html = preg_replace_callback('#<math\b[^>]*>(.*?)</math>#is', function ($m) {
            $text = html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $text = preg_replace('/\s+/', ' ', trim($text)) ?? trim($text);

            return $text !== '' ? htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : '';
        }, $html) ?? $html;
        $html = preg_replace('#</?(?:span|font|o:p|mjx-[a-z0-9-]+)[^>]*>#i', '', $html) ?? $html;
        $html = preg_replace_callback('#<(p|div|td|th|li|h[1-6])(\b[^>]*)>#i', function ($m) {
            $attrs = preg_replace('/\s(?:style|class|id|align|border|cellpadding|cellspacing|width|height)=("[^"]*"|\'[^\']*\')/i', '', $m[2]);

            return '<'.$m[1].$attrs.'>';
        }, $html) ?? $html;
        $html = preg_replace('/(?:&nbsp;|&\#160;|\x{00A0}){2,}/iu', ' ', $html) ?? $html;
        $html = preg_replace('~<(p|div)>(?:\s|&nbsp;|&\#160;)+~i', '<$1>', $html) ?? $html;
        $html = preg_replace('~(?:\s|&nbsp;|&\#160;)+</(p|div)>~i', '</$1>', $html) ?? $html;
        $html = preg_replace('~<(p|div)>(?:\s|&nbsp;|&\#160;)*</\1>~i', '', $html) ?? $html;
        $html = preg_replace('/(?:<br\s*\/?>\s*){2,}/i', '<br>', $html) ?? $html;

        return trim($html);
    }

    protected function imgTag(string $path, $width = null, $height = null): string
    {
        $src = $this->fileUrl($path);
        if ((! $width || ! $height) && is_file($path)) {
            $info = @getimagesize($path);
            if (is_array($info)) {
                $width = $width ?: ($info[0] ?? 0);
                $height = $height ?: ($info[1] ?? 0);
            }
        }
        $width = (int) $width;
        $height = (int) $height;
        $inline = $width > 0 && $height > 0 && $width <= 240 && $height <= 90;
        if ($inline) {
            return '<img src="'.$src.'" width="'.$width.'" height="'.$height.'" style="vertical-align:middle;display:inline;">';
        }

        return '<img src="'.$src.'" style="max-width:100%;max-height:180px;height:auto;display:block;margin:4px 0;">';
    }

    protected function looksLikeImageFilename(string $raw): bool
    {
        $name = trim(strip_tags(html_entity_decode($raw, ENT_QUOTES | ENT_HTML5, 'UTF-8')));

        return (bool) preg_match('/^[A-Za-z0-9._-]+\.(png|jpe?g|gif|webp)$/i', $name);
    }

    protected function resolveImagePath(?string $src): string
    {
        $src = trim(html_entity_decode((string) $src, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($src === '' || preg_match('#^data:#i', $src)) {
            return $src;
        }
        if (is_file($src)) {
            return $this->fileUrl($src);
        }

        $pathPart = parse_url($src, PHP_URL_PATH) ?: $src;
        $basename = basename(urldecode(str_replace('\\', '/', $pathPart)));
        if ($basename === '' || $basename === '.' || $basename === '/') {
            return '';
        }
        if (isset($this->imageCache[$basename])) {
            return $this->imageCache[$basename];
        }

        foreach ([
            public_path('uploads/question/'.$basename),
            public_path('uploads/option/'.$basename),
        ] as $path) {
            if (is_file($path)) {
                return $this->imageCache[$basename] = $this->fileUrl($path);
            }
        }

        $fallbackRoot = rtrim((string) config('app.question_media_fallback', 'https://rankpro.co.in/public'), '/');
        $downloaded = $this->downloadFallbackImage($fallbackRoot.'/uploads/question/'.$basename, $basename);
        if ($downloaded === '') {
            $downloaded = $this->downloadFallbackImage($fallbackRoot.'/uploads/option/'.$basename, $basename);
        }

        return $this->imageCache[$basename] = $downloaded;
    }

    protected function downloadFallbackImage(string $url, string $basename): string
    {
        $dest = public_path('uploads/question/_pdf_tmp/'.$basename);
        if (is_file($dest) && filesize($dest) > 32) {
            return $this->fileUrl($dest);
        }

        try {
            $ctx = stream_context_create([
                'http' => ['timeout' => 8, 'follow_location' => 1, 'ignore_errors' => true],
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
            ]);
            $bin = @file_get_contents($url, false, $ctx);
            if (!is_string($bin) || strlen($bin) < 32) {
                return '';
            }
            if (!is_dir(dirname($dest))) {
                File::makeDirectory(dirname($dest), 0755, true);
            }
            file_put_contents($dest, $bin);

            return $this->fileUrl($dest);
        } catch (\Throwable $e) {
            return '';
        }
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
        $this->imageCache = [];
    }
}
