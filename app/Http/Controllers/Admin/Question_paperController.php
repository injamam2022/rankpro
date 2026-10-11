<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use App\Models\Question_paper;
use App\Models\Question_paper_subject;
use App\Models\Question_paper_question;
use App\Models\Subject;
use App\Models\Question;
use App\Models\Question_detail;
use App\Models\Question_type;
use App\Models\Question_paper_question_type;
use App\Models\CustomTest;
use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Sub_topic;
use App\Models\Exam;
use App\Services\QuestionPaperMixService;
use App\Services\QuestionPaperPdfService;
use Illuminate\Support\Facades\DB;
use App\Support\AdminListQuery;

class Question_paperController extends Controller
{
    public function list(Request $request){
        $data = [];
        $data['q'] = AdminListQuery::q($request);
        $data['per_page'] = AdminListQuery::perPage($request);

        $query = Question_paper::where('is_deleted',0)
            ->whereNotIn('id', CustomTest::questionPaperIdQuery());
        if(session()->get('admmin_is_super') == 'T'){
            $query->where('administrator_id',session()->get('adminAuth'));
        }
        AdminListQuery::applySearch($query, $data['q'], ['name']);
        $data['list'] = $query->orderByDesc('id')->paginate($data['per_page'])->withQueryString();

        return view('admin.question_paper.list',$data);
    }


    public function add(){
        $data = [];
        if(session()->get('admmin_is_super') == 'T'){
            $data['subject_list'] = Subject::where('status',1)->where('id',session()->get('admmin_subject_id'))->get();
        }else{
            $data['subject_list'] = Subject::where('status',1)->get();
        }

        return view('admin.question_paper.add',$data);
    }

    public function mixPreview(Request $request, QuestionPaperMixService $mix)
    {
        $total = (int) $request->input('no_of_question', 0);
        $choice = $this->difficultyChoice($request);
        $percents = [
            'hard' => $choice['hard'],
            'medium' => $choice['medium'],
            'easy' => $choice['easy'],
        ];
        $lockSubjectId = session()->get('admmin_is_super') == 'T'
            ? (int) session()->get('admmin_subject_id')
            : null;

        $preview = $mix->preview($total, $percents, $this->sectionRows($request, false), [], $lockSubjectId);

        return response()->json($preview);
    }

    public function save(Request $request, QuestionPaperMixService $mix){
        $request->validate([
            'name' => 'required',
            'no_of_question' => 'required|integer|min:1',
            'marks_per_question' => 'required|numeric|min:0',
            'time_per_question' => 'required|numeric|min:0',
            'difficulty' => 'required|in:hard,medium,easy',
        ]);

        $choice = $this->difficultyChoice($request);
        $hard = $choice['hard'];
        $medium = $choice['medium'];
        $easy = $choice['easy'];
        if ($choice['choice'] === '') {
            toastr()->error('Select Hard, Medium or Easy.');
            return back()->withInput();
        }

        $sections = $this->sectionRows($request);
        if ($sections === []) {
            toastr()->error('Add at least one subject with a question count.');
            return back()->withInput();
        }

        $sectionSum = 0;
        foreach ($sections as $row) {
            $sectionSum += (int) $row['total_no_of_question'];
        }
        $totalWanted = (int) $request->no_of_question;
        if ($sectionSum !== $totalWanted) {
            toastr()->error('Subject question counts ('.$sectionSum.') must equal Total No. Of Questions ('.$totalWanted.').');
            return back()->withInput();
        }

        $lockSubjectId = session()->get('admmin_is_super') == 'T'
            ? (int) session()->get('admmin_subject_id')
            : null;

        $percents = ['hard' => $hard, 'medium' => $medium, 'easy' => $easy];
        $pick = $mix->pick($sections, $percents, [], $lockSubjectId);

        if ($pick['shortfall'] > 0) {
            $msg = 'Not enough matching questions in the pool (short by '.$pick['shortfall'].').';
            if (! empty($pick['notes'])) {
                $msg .= ' '.implode(' ', $pick['notes']);
            }
            toastr()->error($msg);
            return back()->withInput();
        }

        try {
            DB::transaction(function () use ($request, $sections, $pick, $hard, $medium, $easy) {
                $insertData = [];
                $insertData['name'] = $request->name;
                $insertData['no_of_question'] = $request->no_of_question;
                $insertData['totals_marks_for_exam'] = $request->totals_marks_for_exam;
                $insertData['total_time_for_exam'] = $request->total_time_for_exam;
                $insertData['marks_per_question'] = $request->marks_per_question;
                $insertData['time_per_question'] = $request->time_per_question;
                $insertData['negative_marking_applicable'] = $request->negative_marking_applicable;
                $insertData['negative_marking_per_question'] = $request->negative_marking_per_question;
                $insertData['hard_level'] = $hard;
                $insertData['medium_level'] = $medium;
                $insertData['easy_level'] = $easy;
                $insertData['status'] = ($request->status) ? $request->status : 0;

                if (session()->get('admmin_is_super') == 'T') {
                    $insertData['administrator_id'] = session()->get('adminAuth');
                }

                $exam = Question_paper::create($insertData);

                $subjectRows = [];
                foreach ($sections as $key => $row) {
                    $payload = [
                        'subject_id' => $row['subject_id'],
                        'question_paper_id' => $exam->id,
                        'total_no_of_question' => $row['total_no_of_question'],
                        'status' => 1,
                    ];
                    if (! empty($row['chapter_id'])) {
                        $payload['chapter_id'] = $row['chapter_id'];
                    }
                    if (! empty($row['topic_id'])) {
                        $payload['topic_id'] = $row['topic_id'];
                    }
                    if (! empty($row['sub_topic_id'])) {
                        $payload['sub_topic_id'] = $row['sub_topic_id'];
                    }
                    $subjectRows[$key] = Question_paper_subject::create($payload);
                }

                $questionNumber = 1;
                foreach ($pick['picked'] as $item) {
                    $sectionKey = $item['section_key'];
                    if (! isset($subjectRows[$sectionKey])) {
                        continue;
                    }
                    Question_paper_question::create([
                        'question_number' => $questionNumber,
                        'question_id' => $item['question_id'],
                        'question_paper_subject_id' => $subjectRows[$sectionKey]->id,
                        'question_paper_id' => $exam->id,
                        'status' => 1,
                    ]);
                    $questionNumber++;
                }
            });
        } catch (\Throwable $e) {
            toastr()->error('Could not save the question bank. Please try again.');
            return back()->withInput();
        }

        toastr()->success('Question Bank added successfully.');
        return redirect()->route('admin.question_paper');
    }

    /**
     * @return array<int|string,array<string,mixed>>
     */
    private function sectionRows(Request $request, bool $requireCount = true): array
    {
        $subjectIds = (array) $request->input('subject_id', []);
        $chapterIds = (array) $request->input('chapter_id', []);
        $topicIds = (array) $request->input('topic_id', []);
        $subTopicIds = (array) $request->input('sub_topic_id', []);
        $counts = (array) $request->input('total_no_of_question', []);

        $rows = [];
        foreach ($subjectIds as $key => $subjectId) {
            if ($subjectId === null || $subjectId === '') {
                continue;
            }
            $count = (int) ($counts[$key] ?? 0);
            if ($requireCount && $count <= 0) {
                continue;
            }
            $rows[$key] = [
                'subject_id' => (int) $subjectId,
                'chapter_id' => ! empty($chapterIds[$key]) ? (int) $chapterIds[$key] : null,
                'topic_id' => ! empty($topicIds[$key]) ? (int) $topicIds[$key] : null,
                'sub_topic_id' => ! empty($subTopicIds[$key]) ? (int) $subTopicIds[$key] : null,
                'total_no_of_question' => $count,
            ];
        }

        return $rows;
    }

    public function edit(Request $request){
        $data = [];
        if(session()->get('admmin_is_super') == 'T'){
            $data['subject_list'] = Subject::where('status',1)->where('id',session()->get('admmin_subject_id'))->get();
        }else{
            $data['subject_list'] = Subject::where('status',1)->get();
        }

        $data['details'] = Question_paper::where('id',$request->id)->where('is_deleted',0)->first();
        if (!$data['details']) {
            toastr()->error('Question bank not found.');
            return redirect()->route('admin.question_paper');
        }

        $data['difficulty_choice'] = $this->difficultyChoiceFromPaper($data['details']);

        $rows = Question_paper_subject::where('question_paper_id', $data['details']->id)->get();
        $subjectRows = [];
        foreach ($rows as $row) {
            $subjectRows[] = [
                'row' => $row,
                'chapters' => Chapter::where('status', 1)->where('subject_id', $row->subject_id)->get(),
                'topics' => Topic::where('status', 1)->where('subject_id', $row->subject_id)
                    ->when($row->chapter_id, fn ($q) => $q->where('chapter_id', $row->chapter_id))
                    ->get(),
                'sub_topics' => Sub_topic::where('status', 1)->where('subject_id', $row->subject_id)
                    ->when($row->topic_id, fn ($q) => $q->where('topic_id', $row->topic_id))
                    ->get(),
            ];
        }
        $data['subject_rows'] = $subjectRows;

        return view('admin.question_paper.edit',$data);
    }

    public function update(Request $request, QuestionPaperMixService $mix){
        $request->validate([
            'name' => 'required',
            'id' => 'required',
            'no_of_question' => 'required|integer|min:1',
            'marks_per_question' => 'required|numeric|min:0',
            'time_per_question' => 'required|numeric|min:0',
            'difficulty' => 'required|in:hard,medium,easy',
        ]);

        $paper = Question_paper::where('id', $request->id)->where('is_deleted', 0)->first();
        if (!$paper) {
            toastr()->error('Question bank not found.');
            return redirect()->route('admin.question_paper');
        }

        $choice = $this->difficultyChoice($request);
        $hard = $choice['hard'];
        $medium = $choice['medium'];
        $easy = $choice['easy'];
        if ($choice['choice'] === '') {
            toastr()->error('Select Hard, Medium or Easy.');
            return back()->withInput();
        }

        $rebuild = $request->boolean('regenerate_questions');
        $sections = $this->sectionRows($request);
        $totalWanted = (int) $request->no_of_question;

        if ($rebuild) {
            if ($sections === []) {
                toastr()->error('Add at least one subject with a question count before rebuilding.');
                return back()->withInput();
            }
            $sectionSum = 0;
            foreach ($sections as $row) {
                $sectionSum += (int) $row['total_no_of_question'];
            }
            if ($sectionSum !== $totalWanted) {
                toastr()->error('Subject question counts ('.$sectionSum.') must equal Total No. Of Questions ('.$totalWanted.').');
                return back()->withInput();
            }
        }

        $pick = ['picked' => [], 'shortfall' => 0, 'notes' => []];
        $lockSubjectId = session()->get('admmin_is_super') == 'T'
            ? (int) session()->get('admmin_subject_id')
            : null;
        if ($rebuild) {
            $pick = $mix->pick($sections, ['hard' => $hard, 'medium' => $medium, 'easy' => $easy], [], $lockSubjectId);
            if ($pick['shortfall'] > 0) {
                $msg = 'Not enough matching questions in the pool (short by '.$pick['shortfall'].').';
                if (! empty($pick['notes'])) {
                    $msg .= ' '.implode(' ', $pick['notes']);
                }
                toastr()->error($msg);
                return back()->withInput();
            }
        }

        try {
            DB::transaction(function () use ($request, $paper, $sections, $pick, $hard, $medium, $easy, $rebuild) {
                $paper->update([
                    'name' => $request->name,
                    'no_of_question' => $request->no_of_question,
                    'totals_marks_for_exam' => $request->totals_marks_for_exam,
                    'total_time_for_exam' => $request->total_time_for_exam,
                    'marks_per_question' => $request->marks_per_question,
                    'time_per_question' => $request->time_per_question,
                    'negative_marking_applicable' => $request->negative_marking_applicable,
                    'negative_marking_per_question' => $request->negative_marking_per_question,
                    'hard_level' => $hard,
                    'medium_level' => $medium,
                    'easy_level' => $easy,
                    'status' => $request->status ? $request->status : 0,
                ]);

                Question_paper_question_type::where('question_paper_id', $paper->id)->delete();

                if ($rebuild) {
                    Question_paper_question::where('question_paper_id', $paper->id)->delete();
                    Question_paper_subject::where('question_paper_id', $paper->id)->delete();

                    $subjectRows = [];
                    foreach ($sections as $key => $row) {
                        $payload = [
                            'subject_id' => $row['subject_id'],
                            'question_paper_id' => $paper->id,
                            'total_no_of_question' => $row['total_no_of_question'],
                            'status' => 1,
                        ];
                        if (! empty($row['chapter_id'])) {
                            $payload['chapter_id'] = $row['chapter_id'];
                        }
                        if (! empty($row['topic_id'])) {
                            $payload['topic_id'] = $row['topic_id'];
                        }
                        if (! empty($row['sub_topic_id'])) {
                            $payload['sub_topic_id'] = $row['sub_topic_id'];
                        }
                        $subjectRows[$key] = Question_paper_subject::create($payload);
                    }

                    $questionNumber = 1;
                    foreach ($pick['picked'] as $item) {
                        $sectionKey = $item['section_key'];
                        if (! isset($subjectRows[$sectionKey])) {
                            continue;
                        }
                        Question_paper_question::create([
                            'question_number' => $questionNumber,
                            'question_id' => $item['question_id'],
                            'question_paper_subject_id' => $subjectRows[$sectionKey]->id,
                            'question_paper_id' => $paper->id,
                            'status' => 1,
                        ]);
                        $questionNumber++;
                    }
                }
            });
        } catch (\Throwable $e) {
            toastr()->error('Could not update the question bank. Please try again.');
            return back()->withInput();
        }

        toastr()->success($rebuild
            ? 'Question Bank updated and question set rebuilt.'
            : 'Question Bank updated. Existing questions were kept.');
        return redirect()->route('admin.question_paper');
    }
    
    
    public function delete(Request $request){
        $id = $request->id;
        $exam = Question_paper::where('id',$id)->first();
        if($exam){
            $live = Exam::where('question_paper_id', $exam->id)->where('is_deleted', 0)->count();
            if ($live > 0) {
                toastr()->error('This bank is used by '.$live.' exam(s). Remove those exams first.');
                return redirect()->route('admin.question_paper');
            }
            $exam->update(['is_deleted'=>1]);
        }
        
        toastr()->success('Question Bank deleted successfully.');
        return redirect()->route('admin.question_paper');
    }

    public function change(Request $request){
        $loginCheck = Question_paper::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
            toastr()->success('Question Bank status change successfully.');
        } else {
            toastr()->error('Question bank not found.');
        }

        return redirect()->route('admin.question_paper');
    }

    public function question(Request $request){
        $data = [];
        $data['details'] = Question_paper::where('id', $request->id)->where('is_deleted', 0)->first();
        if (!$data['details']) {
            toastr()->error('Question bank not found.');
            return redirect()->route('admin.question_paper');
        }

        $languageId = getDefaultLanguage();
        $slots = Question_paper_question::where('question_paper_id', $request->id)
            ->orderBy('id')
            ->get();
        $questionIds = $slots->pluck('question_id')->filter()->unique()->values()->all();

        $details = Question_detail::whereIn('question_id', $questionIds ?: [0])
            ->where('language_id', $languageId)
            ->get()
            ->keyBy('question_id');
        $questions = Question::whereIn('id', $questionIds ?: [0])->get()->keyBy('id');

        $data['list'] = $slots->map(function ($slot) use ($details, $questions) {
            $question = $questions->get($slot->question_id);
            $detail = $details->get($slot->question_id);
            $slot->question_paper_question_id = $slot->id;
            $slot->question_text = $detail->question_text ?? '';
            $slot->question_image = $detail->question_image ?? null;
            $slot->answer = $question->answer ?? null;
            $slot->solution_video_link = $question->solution_video_link ?? null;
            return $slot;
        });

        return view('admin.question_paper.question', $data);
    }

    public function searchQuestions(Request $request)
    {
        $paperId = (int) $request->input('question_paper_id');
        $slotId = (int) $request->input('question_paper_question_id');
        $term = trim((string) $request->input('q', $request->input('term', '')));

        $paper = Question_paper::where('id', $paperId)->where('is_deleted', 0)->first();
        if (!$paper) {
            return response()->json(['results' => []]);
        }

        $usedIds = Question_paper_question::where('question_paper_id', $paperId)->pluck('question_id');
        $languageId = getDefaultLanguage();

        $subjectId = null;
        if ($slotId) {
            $slot = Question_paper_question::where('id', $slotId)->where('question_paper_id', $paperId)->first();
            if ($slot) {
                $subjectId = Question_paper_subject::where('id', $slot->question_paper_subject_id)->value('subject_id')
                    ?: Question::where('id', $slot->question_id)->value('subject_id');
            }
        }

        $query = Question_detail::select([
                'question_details.question_id',
                'question_details.question_text',
                'subjects.name as subject_name',
            ])
            ->leftJoin('questions', 'questions.id', '=', 'question_details.question_id')
            ->leftJoin('subjects', 'questions.subject_id', '=', 'subjects.id')
            ->where('question_details.language_id', $languageId)
            ->where('questions.is_deleted', 0)
            ->where('questions.status', 1)
            ->whereNotIn('questions.id', $usedIds);

        if ($subjectId) {
            $query->where('questions.subject_id', $subjectId);
        }
        if (session()->get('admmin_is_super') == 'T') {
            $query->where('questions.subject_id', session()->get('admmin_subject_id'));
        }
        if ($term !== '') {
            if (ctype_digit($term)) {
                $query->where('questions.id', (int) $term);
            } else {
                $boolean = $this->fullTextBoolean($term);
                $hasFullText = !empty(DB::select("SHOW INDEX FROM question_details WHERE Key_name = ?", ['question_details_question_text_fulltext']));
                if ($hasFullText && $boolean !== '') {
                    $query->whereRaw('MATCH(question_details.question_text) AGAINST(? IN BOOLEAN MODE)', [$boolean]);
                } else {
                    $query->where('question_details.question_text', 'like', AdminListQuery::like($term));
                }
            }
        }

        $rows = $query->orderByDesc('questions.id')->limit(20)->get();

        return response()->json([
            'results' => $rows->map(function ($row) {
                $plain = trim(html_entity_decode(strip_tags((string) $row->question_text), ENT_QUOTES, 'UTF-8'));
                $plain = preg_replace('/\s+/u', ' ', $plain);
                if (mb_strlen($plain) > 140) {
                    $plain = mb_substr($plain, 0, 140) . '…';
                }
                $label = '#' . $row->question_id;
                if (!empty($row->subject_name)) {
                    $label .= ' · ' . $row->subject_name;
                }
                $label .= ' — ' . ($plain !== '' ? $plain : '(no text)');
                return ['id' => (int) $row->question_id, 'text' => $label];
            })->values(),
        ]);
    }

    public function download_question(Request $request, QuestionPaperPdfService $pdfService)
    {
        return $this->downloadPaperPdf($request, 'question', $pdfService);
    }

    public function download_answer(Request $request, QuestionPaperPdfService $pdfService)
    {
        return $this->downloadPaperPdf($request, 'answer', $pdfService);
    }

    private function downloadPaperPdf(Request $request, string $kind, QuestionPaperPdfService $pdfService)
    {
        $paperId = (int) $request->input('id');
        $paper = Question_paper::where('id', $paperId)->where('is_deleted', 0)->first();
        if (!$paper) {
            toastr()->error('Question bank not found.');
            return redirect()->route('admin.question_paper');
        }

        try {
            return $pdfService->download($paper, $kind);
        } catch (\Throwable $e) {
            report($e);
            toastr()->error('Could not generate the PDF. Please try again.');
            return redirect()->route('admin.question_paper.question', ['id' => $paper->id]);
        }
    }

    public function change_question(Request $request){
        $question_paper_id = (int) $request->question_paper_id;
        $question_paper_question_id = (int) $request->question_paper_question_id;
        $question_id = (int) $request->question_id;

        if (!$question_id) {
            toastr()->error('Please search and select a question.');
            return redirect()->route('admin.question_paper.question', ['id' => $question_paper_id]);
        }

        $question_paper_question = Question_paper_question::where('id', $question_paper_question_id)
            ->where('question_paper_id', $question_paper_id)
            ->first();

        if (!$question_paper_question) {
            toastr()->error('Question slot not found.');
            return redirect()->route('admin.question_paper');
        }

        $question_paper_question->update(['question_id' => $question_id]);

        toastr()->success('Question change successfully.');
        return redirect()->route('admin.question_paper.question', ['id' => $question_paper_id]);
    }

    public function view(Request $request){
        $input = $request->all();
        $data = [];
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['details'] = Question_paper::where('id',$request->id)->first();
        $data['question_paper_subject'] = Question_paper_subject::select(['question_paper_subjects.*','subjects.name as subject_name','chapters.name as chapter_name','topics.name as topic_name','sub_topics.name as sub_topic_name'])
                ->leftJoin('subjects', 'question_paper_subjects.subject_id', '=', 'subjects.id')
                ->leftJoin('chapters', 'question_paper_subjects.chapter_id', '=', 'chapters.id')
                ->leftJoin('topics', 'question_paper_subjects.topic_id', '=', 'topics.id')
                ->leftJoin('sub_topics', 'question_paper_subjects.sub_topic_id', '=', 'sub_topics.id')
                ->where('question_paper_id',$data['details']->id)->get();

        $data['question_paper_question_type'] = Question_paper_question_type::select(['question_paper_question_types.*','question_types.name'])
                ->leftJoin('question_types', 'question_paper_question_types.question_type_id', '=', 'question_types.id')
                ->where('question_paper_question_types.question_paper_id',$data['details']->id)
                ->get();

        // dd($data['question_paper_subject']);
        return view('admin.question_paper.view',$data);
    }

    /**
     * @return array{choice:string,hard:float,medium:float,easy:float}
     */
    private function difficultyChoice(Request $request): array
    {
        $choice = strtolower(trim((string) $request->input('difficulty', '')));
        if (! in_array($choice, ['hard', 'medium', 'easy'], true)) {
            return ['choice' => '', 'hard' => 0, 'medium' => 0, 'easy' => 0];
        }

        return [
            'choice' => $choice,
            'hard' => $choice === 'hard' ? 100 : 0,
            'medium' => $choice === 'medium' ? 100 : 0,
            'easy' => $choice === 'easy' ? 100 : 0,
        ];
    }

    private function difficultyChoiceFromPaper($paper): string
    {
        $h = (float) ($paper->hard_level ?? 0);
        $m = (float) ($paper->medium_level ?? 0);
        $e = (float) ($paper->easy_level ?? 0);
        if ($h >= $m && $h >= $e && $h > 0) {
            return 'hard';
        }
        if ($m >= $e && $m > 0) {
            return 'medium';
        }
        if ($e > 0) {
            return 'easy';
        }

        return '';
    }

    private function fullTextBoolean(string $term): string
    {
        $clean = preg_replace('/[+\-><()~*"@]+/', ' ', $term);
        $clean = trim((string) preg_replace('/\s+/', ' ', (string) $clean));
        if ($clean === '') {
            return '';
        }
        $parts = [];
        foreach (preg_split('/\s+/', $clean) as $word) {
            if ($word === '') {
                continue;
            }
            $parts[] = '+' . $word . '*';
        }
        return implode(' ', $parts);
    }
    
}
