<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use ZipArchive;
use Illuminate\Support\Str;
use App\Imports\ExcelToDbImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Question;
use App\Models\Question_detail;
use App\Models\Chapter;
use App\Models\Subject;
use App\Models\Question_exam;
use App\Models\Question_source;
use App\Models\Language;
use App\Models\Source;
use App\Models\Topic;
use App\Models\Sub_topic;
use App\Models\Question_type;
use App\Support\AdminListQuery;

class QuestionController extends Controller
{
    public function list(Request $request){
        $data = [];

        $data['subject_id'] = $request->subject_id;
        $data['chapter_id'] = $request->chapter_id;
        $data['topic_id'] = $request->topic_id;
        $data['sub_topic_id'] = $request->sub_topic_id;
        $data['is_premium'] = $request->input('is_premium', '');

        $language_id = getDefaultLanguage();
        $data['duplicates'] = $request->boolean('duplicates');
        $data['q'] = AdminListQuery::q($request);
        $data['per_page'] = AdminListQuery::perPage($request);

        $data['list'] = Question_detail::select(['questions.*','question_details.question_text','question_details.question_image',
                                                    'question_details.content_hash',
                                                    'chapters.name as chapter_name','subjects.name as subject_name','sources.name as source_name',
                                                    'question_details.is_option1_image','question_details.option1','question_details.is_option2_image',
                                                    'question_details.option2','question_details.is_option3_image','question_details.option3',
                                                    'question_details.is_option4_image','question_details.option4'
                                                ])
                        ->leftJoin('questions', 'questions.id', '=', 'question_details.question_id')
                        ->leftJoin('subjects', 'questions.subject_id', '=', 'subjects.id')
                        ->leftJoin('chapters', 'questions.chapter_id', '=', 'chapters.id')
                        ->leftJoin('sources', 'questions.source_id', '=', 'sources.id')
                        ->where('questions.is_deleted',0)
                        ->where('question_details.language_id',$language_id);

        $data['chapter_list'] = [];
        $data['topic_list'] = [];
        $data['sub_topic_list'] = [];

        if($request->subject_id){ 
            $data['list'] = $data['list']->where('questions.subject_id',$request->subject_id);
            $data['chapter_list'] = Chapter::where('subject_id',$request->subject_id)->where('status',1)->get();
        }

        if($request->chapter_id){ 
            $data['list'] = $data['list']->where('questions.chapter_id',$request->chapter_id);
            $data['topic_list'] = Topic::where('chapter_id',$request->chapter_id)->where('status',1)->get();
        }

        if($request->topic_id){ 
            $data['list'] = $data['list']->where('questions.topic_id',$request->topic_id);
            $data['sub_topic_list'] = Sub_topic::where('topic_id',$request->topic_id)->where('status',1)->get();
        }

        if($request->sub_topic_id){ 
            $data['list'] = $data['list']->where('questions.sub_topic_id',$request->sub_topic_id);
        }

        if ($request->filled('is_premium') && Schema::hasColumn('questions', 'is_premium')) {
            if ((string) $request->input('is_premium') === '1') {
                $data['list'] = $data['list']->where('questions.is_premium', 1);
            } else {
                $data['list'] = $data['list']->where(function ($q) {
                    $q->where('questions.is_premium', 0)->orWhereNull('questions.is_premium');
                });
            }
        }

        AdminListQuery::applySearch($data['list'], $data['q'], [
            'question_details.question_text',
            'subjects.name',
            'chapters.name',
            'sources.name',
        ]);

        if(session()->get('admmin_is_super') == 'T'){
            $data['list'] = $data['list']->where('questions.subject_id',session()->get('admmin_subject_id'));
            $data['subject_list'] = Subject::where('id',session()->get('admmin_subject_id'))->where('status',1)->get();
        }else{
            $data['subject_list'] = Subject::where('status',1)->get();
        }

        $duplicateIndex = $this->duplicateIndex($language_id);
        $duplicateHashes = $duplicateIndex['hashes'];
        $hasFilters = $request->filled('subject_id') || $request->filled('chapter_id') || $request->filled('topic_id') || $request->filled('sub_topic_id') || $request->filled('is_premium') || $data['q'] !== '' || session()->get('admmin_is_super') == 'T';
        $data['duplicate_count'] = 0;
        if ($duplicateHashes->isNotEmpty()) {
            if ($hasFilters) {
                $data['duplicate_count'] = (clone $data['list'])
                    ->whereIn('question_details.content_hash', $duplicateHashes->all())
                    ->distinct()
                    ->count('questions.id');
            } else {
                $data['duplicate_count'] = $duplicateIndex['count'];
            }

            if ($data['duplicates']) {
                $data['list']->whereIn('question_details.content_hash', $duplicateHashes->all());
                $data['removable_duplicate_count'] = $this->removableDuplicateCount(clone $data['list'], $language_id);
            }
        }

        $data['list'] = $data['list']->orderBy('questions.id','DESC')->paginate($data['per_page'])->withQueryString();
        $this->flagDuplicateQuestions($data['list'], $language_id, $duplicateHashes->all());

        return view('admin.question.list',$data);
    }

    /**
     * How many questions can be removed while keeping one original per duplicate hash.
     */
    private function removableDuplicateCount($query, $languageId): int
    {
        if (!Schema::hasColumn('question_details', 'content_hash')) {
            return 0;
        }

        $ids = (clone $query)->distinct()->pluck('questions.id');
        if ($ids->isEmpty()) {
            return 0;
        }

        $groups = Question_detail::query()
            ->select('question_details.content_hash', DB::raw('COUNT(DISTINCT question_details.question_id) as c'))
            ->join('questions', 'questions.id', '=', 'question_details.question_id')
            ->where('questions.is_deleted', 0)
            ->where('question_details.language_id', $languageId)
            ->whereIn('questions.id', $ids->all())
            ->whereNotNull('question_details.content_hash')
            ->where('question_details.content_hash', '!=', '')
            ->groupBy('question_details.content_hash')
            ->havingRaw('COUNT(DISTINCT question_details.question_id) > 1')
            ->get();

        $removable = 0;
        foreach ($groups as $group) {
            $removable += max(0, (int) $group->c - 1);
        }

        return $removable;
    }

    public function bulkDeleteDuplicates(Request $request)
    {
        if (!$request->boolean('duplicates')) {
            toastr()->warning('Turn on “Show duplicates only” before bulk deleting.');
            return redirect()->route('admin.question', $request->except(['_token']));
        }

        if (!Schema::hasColumn('question_details', 'content_hash')) {
            toastr()->error('Duplicate detection is not available.');
            return redirect()->route('admin.question', $request->except(['_token']));
        }

        $languageId = getDefaultLanguage();
        $query = Question_detail::query()
            ->select(['questions.id', 'question_details.content_hash'])
            ->join('questions', 'questions.id', '=', 'question_details.question_id')
            ->where('questions.is_deleted', 0)
            ->where('question_details.language_id', $languageId)
            ->whereNotNull('question_details.content_hash')
            ->where('question_details.content_hash', '!=', '');

        if ($request->filled('subject_id')) {
            $query->where('questions.subject_id', $request->subject_id);
        }
        if ($request->filled('chapter_id')) {
            $query->where('questions.chapter_id', $request->chapter_id);
        }
        if ($request->filled('topic_id')) {
            $query->where('questions.topic_id', $request->topic_id);
        }
        if ($request->filled('sub_topic_id')) {
            $query->where('questions.sub_topic_id', $request->sub_topic_id);
        }
        if ($request->filled('is_premium') && Schema::hasColumn('questions', 'is_premium')) {
            if ((string) $request->input('is_premium') === '1') {
                $query->where('questions.is_premium', 1);
            } else {
                $query->where(function ($q) {
                    $q->where('questions.is_premium', 0)->orWhereNull('questions.is_premium');
                });
            }
        }
        if (session()->get('admmin_is_super') == 'T') {
            $query->where('questions.subject_id', session()->get('admmin_subject_id'));
        }

        $q = AdminListQuery::q($request);
        if ($q !== '') {
            $query->leftJoin('subjects', 'questions.subject_id', '=', 'subjects.id')
                ->leftJoin('chapters', 'questions.chapter_id', '=', 'chapters.id')
                ->leftJoin('sources', 'questions.source_id', '=', 'sources.id');
            AdminListQuery::applySearch($query, $q, [
                'question_details.question_text',
                'subjects.name',
                'chapters.name',
                'sources.name',
            ]);
        }

        $duplicateHashes = $this->duplicateIndex($languageId)['hashes'];
        if ($duplicateHashes->isEmpty()) {
            toastr()->info('No duplicate questions found.');
            return redirect()->route('admin.question', array_merge($request->except(['_token']), ['duplicates' => 1]));
        }

        $rows = $query->whereIn('question_details.content_hash', $duplicateHashes->all())
            ->orderBy('questions.id')
            ->get();

        $byHash = [];
        foreach ($rows as $row) {
            $byHash[$row->content_hash][] = (int) $row->id;
        }

        $deleteIds = [];
        foreach ($byHash as $ids) {
            $ids = array_values(array_unique($ids));
            if (count($ids) < 2) {
                continue;
            }
            // Keep the lowest ID (original), remove the rest.
            sort($ids);
            array_shift($ids);
            foreach ($ids as $id) {
                $deleteIds[] = $id;
            }
        }

        $deleteIds = array_values(array_unique($deleteIds));
        if ($deleteIds === []) {
            toastr()->info('No removable duplicates in the current filter.');
            return redirect()->route('admin.question', array_merge($request->except(['_token']), ['duplicates' => 1]));
        }

        Question::whereIn('id', $deleteIds)->update(['is_deleted' => 1]);
        Question_detail::forgetDuplicateCache();

        toastr()->success(count($deleteIds).' duplicate question(s) deleted. One original kept for each set.');
        return redirect()->route('admin.question', array_merge(
            $request->only(['subject_id', 'chapter_id', 'topic_id', 'sub_topic_id', 'is_premium', 'q']),
            ['duplicates' => 1]
        ));
    }

    private function duplicateIndex($languageId)
    {
        if (!Schema::hasColumn('question_details', 'content_hash')) {
            return ['hashes' => collect(), 'count' => 0];
        }

        $index = Cache::remember(Question_detail::duplicateCacheKey($languageId), 3600, function () use ($languageId) {
            $hashes = Question_detail::query()
                ->select('question_details.content_hash')
                ->join('questions', 'questions.id', '=', 'question_details.question_id')
                ->where('questions.is_deleted', 0)
                ->where('question_details.language_id', $languageId)
                ->whereNotNull('question_details.content_hash')
                ->where('question_details.content_hash', '!=', '')
                ->groupBy('question_details.content_hash')
                ->havingRaw('COUNT(DISTINCT question_details.question_id) > 1')
                ->pluck('question_details.content_hash')
                ->all();

            $count = 0;
            if ($hashes) {
                $count = Question_detail::query()
                    ->join('questions', 'questions.id', '=', 'question_details.question_id')
                    ->where('questions.is_deleted', 0)
                    ->where('question_details.language_id', $languageId)
                    ->whereIn('question_details.content_hash', $hashes)
                    ->distinct()
                    ->count('questions.id');
            }

            return ['hashes' => $hashes, 'count' => $count];
        });

        return [
            'hashes' => collect($index['hashes']),
            'count' => (int) $index['count'],
        ];
    }

    private function flagDuplicateQuestions($list, $languageId, array $duplicateHashes)
    {
        $hashLookup = array_flip($duplicateHashes);
        $pageHashes = [];

        foreach ($list as $row) {
            if (!empty($row->content_hash) && isset($hashLookup[$row->content_hash])) {
                $pageHashes[] = $row->content_hash;
            }
        }

        $pageHashes = array_values(array_unique($pageHashes));
        $siblings = [];

        if ($pageHashes) {
            $rows = Question_detail::query()
                ->select('question_details.content_hash', 'question_details.question_id')
                ->join('questions', 'questions.id', '=', 'question_details.question_id')
                ->where('questions.is_deleted', 0)
                ->where('question_details.language_id', $languageId)
                ->whereIn('question_details.content_hash', $pageHashes)
                ->get();

            foreach ($rows as $row) {
                $siblings[$row->content_hash][] = (int) $row->question_id;
            }
        }

        foreach ($list as $row) {
            $ids = $siblings[$row->content_hash] ?? [];
            $ids = array_values(array_unique(array_filter($ids, function ($id) use ($row) {
                return (int) $id !== (int) $row->id;
            })));
            $row->is_duplicate = count($ids) > 0;
            $row->duplicate_ids = $ids;
        }
    }

    public function add(){
        $data = [];
        $data['question_source_list'] = Question_source::where('status',1)->get();
        $data['question_exam_list'] = Question_exam::where('status',1)->get();
        if(session()->get('admmin_is_super') == 'T'){
            $data['subject_list'] = Subject::where('status',1)->where('id',session()->get('admmin_subject_id'))->get();
        }else{
            $data['subject_list'] = Subject::where('status',1)->get();
        }
        $data['language_list'] = Language::where('status',1)->get();
        $data['question_type_list'] = Question_type::where('status',1)->get();
        return view('admin.question.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'subject_id' => 'required'
                        ]);

        $input = $request->all();
        // dd($input);
        // $loginCheck = Ranker::where('email',$request->email)->first();

        if (1){
            $insertData = [];
            
            $insertData['subject_id'] = $request->subject_id;
            $insertData['chapter_id'] = $request->chapter_id;
            $insertData['source_id'] = $request->source_id;
            $insertData['topic_id'] = $request->topic_id;
            $insertData['sub_topic_id'] = $request->sub_topic_id;
            $insertData['question_type_id'] = $request->input('question_type_id') ?: 0;
            $insertData['difficulty_level'] = $request->difficulty_level;
            $insertData['is_premium'] = (int) $request->input('is_premium') === 1 ? 1 : 0;
            $sourceIds = $this->selectedQuestionSourceIds($request);
            $insertData['question_source_id'] = $sourceIds[0] ?? 0;
            $insertData['solution_video_link'] = $request->solution_video_link;
            $insertData['answer'] = $request->answer;
            $insertData['status'] = $request->status;
            $insertData['administrator_id'] = session()->get('adminAuth');
            $insertData['is_deleted'] = 0;
            
            $question = Question::create($insertData);
            $question->syncQuestionSources($sourceIds);
            
            $language_list = Language::where('status',1)->get();

            foreach($language_list as $value){
                $insertData = [];
                $insertData['question_text'] = $request->question_text[$value->id];
                if(isset($request->file('question_image')[$value->id])){
                    if ($image = $request->file('question_image')[$value->id]){
                        $insertData['question_image'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                        $destinationPath = public_path('/uploads/question');
                        $image->move($destinationPath, $insertData['question_image']);
                    }
                }
                    

                $insertData['is_option1_image'] = $request->is_option1_image[$value->id];
                if($insertData['is_option1_image'] == 1){
                    if ($image = $request->file('option1_image')[$value->id]){
                        $insertData['option1'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                        $destinationPath = public_path('/uploads/question');
                        $image->move($destinationPath, $insertData['option1']);
                    }
                }else{
                    $insertData['option1'] = $request->option1_text[$value->id];
                }

                $insertData['is_option2_image'] = $request->is_option2_image[$value->id];
                if($insertData['is_option2_image'] == 1){
                    if ($image = $request->file('option2_image')[$value->id]){
                        $insertData['option2'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                        $destinationPath = public_path('/uploads/question');
                        $image->move($destinationPath, $insertData['option2']);
                    }
                }else{
                    $insertData['option2'] = $request->option2_text[$value->id];
                }

                $insertData['is_option3_image'] = $request->is_option3_image[$value->id];
                if($insertData['is_option3_image'] == 1){
                    if ($image = $request->file('option3_image')[$value->id]){
                        $insertData['option3'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                        $destinationPath = public_path('/uploads/question');
                        $image->move($destinationPath, $insertData['option3']);
                    }
                }else{
                    $insertData['option3'] = $request->option3_text[$value->id];
                }

                $insertData['is_option4_image'] = $request->is_option4_image[$value->id];
                if($insertData['is_option4_image'] == 1){
                    if ($image = $request->file('option4_image')[$value->id]){
                        $insertData['option4'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                        $destinationPath = public_path('/uploads/question');
                        $image->move($destinationPath, $insertData['option4']);
                    }
                }else{
                    $insertData['option4'] = $request->option4_text[$value->id];
                }

                $insertData['answer_behavior_tag1'] = $request->answer_behavior_tag1[$value->id];
                $insertData['answer_behavior_tag2'] = $request->answer_behavior_tag2[$value->id];
                $insertData['answer_behavior_tag3'] = $request->answer_behavior_tag3[$value->id];
                $insertData['answer_behavior_tag4'] = $request->answer_behavior_tag4[$value->id];

                $insertData['solution'] = $request->solution[$value->id];
                $insertData['question_id'] = $question->id;
                $insertData['language_id'] = $value->id;
                $insertData['status'] = $request->status;
                Question_detail::create($insertData);
            }

            toastr()->success('Question added successfully.');
            return redirect()->route('admin.question');
        }else{
            toastr()->warning('Question already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Question::where('id',$request->id)->first();
        $data['question_source_list'] = Question_source::where('status',1)->get();
        $data['selected_question_source_ids'] = $data['details']
            ? $data['details']->selectedQuestionSourceIds()
            : [];
        $data['question_exam_list'] = Question_exam::where('status',1)->get();
        if(session()->get('admmin_is_super') == 'T'){
            $data['subject_list'] = Subject::where('status',1)->where('id',session()->get('admmin_subject_id'))->get();
            if ((int) $data['details']->subject_id !== (int) session()->get('admmin_subject_id')) {
                toastr()->error('You can only edit questions in your subject.');
                return redirect()->route('admin.question');
            }
        }else{
            $data['subject_list'] = Subject::where('status',1)->get();
        }
        $data['chapter_list'] = Chapter::where('subject_id',$data['details']->subject_id)->where('status',1)->get();
        $data['topic_list'] = Topic::where('chapter_id',$data['details']->chapter_id)->where('status',1)->get();
        $data['sub_topic_list'] = Sub_topic::where('topic_id',$data['details']->topic_id)->where('status',1)->get();
        $data['source_list'] = Source::where('subject_id',$data['details']->subject_id)->where('status',1)->get();
        $data['language_list'] = Language::where('status',1)->get();
        $data['question_type_list'] = Question_type::where('status',1)->get();

        foreach($data['language_list'] as $key => $value){
            $question_detail = Question_detail::where('question_id',$request->id)->where('language_id',$value->id)->first();
            $data['language_list'][$key]->question_detail = $question_detail;
            if($question_detail){
                $data['language_list'][$key]->question_text = $question_detail->question_text;
                $data['language_list'][$key]->question_image = $question_detail->question_image;
                $data['language_list'][$key]->option1 = $question_detail->option1;
                $data['language_list'][$key]->is_option1_image = $question_detail->is_option1_image;
                $data['language_list'][$key]->option2 = $question_detail->option2;
                $data['language_list'][$key]->is_option2_image = $question_detail->is_option2_image;
                $data['language_list'][$key]->option3 = $question_detail->option3;
                $data['language_list'][$key]->is_option3_image = $question_detail->is_option3_image;
                $data['language_list'][$key]->option4 = $question_detail->option4;
                $data['language_list'][$key]->is_option4_image = $question_detail->is_option4_image;
                $data['language_list'][$key]->solution = $question_detail->solution;
                $data['language_list'][$key]->answer_behavior_tag1 = $question_detail->answer_behavior_tag1;
                $data['language_list'][$key]->answer_behavior_tag2 = $question_detail->answer_behavior_tag2;
                $data['language_list'][$key]->answer_behavior_tag3 = $question_detail->answer_behavior_tag3;
                $data['language_list'][$key]->answer_behavior_tag4 = $question_detail->answer_behavior_tag4;
            }else{
                $data['language_list'][$key]->question_text = "";
                $data['language_list'][$key]->question_image = "";
                $data['language_list'][$key]->option1 = "";
                $data['language_list'][$key]->is_option1_image = "";
                $data['language_list'][$key]->option2 = "";
                $data['language_list'][$key]->is_option2_image = "";
                $data['language_list'][$key]->option3 = "";
                $data['language_list'][$key]->is_option3_image = "";
                $data['language_list'][$key]->option4 = "";
                $data['language_list'][$key]->is_option4_image = "";
                $data['language_list'][$key]->solution = "";
                $data['language_list'][$key]->answer_behavior_tag1 = "";
                $data['language_list'][$key]->answer_behavior_tag2 = "";
                $data['language_list'][$key]->answer_behavior_tag3 = "";
                $data['language_list'][$key]->answer_behavior_tag4 = "";
            }
        }

        return view('admin.question.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'subject_id' => 'required'
                        ]);

        $input = $request->all();
        // dd($input);
        $loginCheck = Question::where('id',$request->id)->first();

        if ($loginCheck){
            if(session()->get('admmin_is_super') == 'T' && (int) $request->subject_id !== (int) session()->get('admmin_subject_id')) {
                toastr()->error('You can only save questions in your subject.');
                return back()->withInput();
            }
            $insertData = [];
            
            $insertData['subject_id'] = $request->subject_id;
            $insertData['chapter_id'] = $request->chapter_id;
            $insertData['source_id'] = $request->source_id;
            $insertData['topic_id'] = $request->topic_id;
            $insertData['sub_topic_id'] = $request->sub_topic_id;
            $insertData['difficulty_level'] = $request->difficulty_level;
            $insertData['is_premium'] = (int) $request->input('is_premium') === 1 ? 1 : 0;
            $sourceIds = $this->selectedQuestionSourceIds($request);
            $insertData['question_source_id'] = $sourceIds[0] ?? 0;
            $insertData['solution_video_link'] = $request->solution_video_link;
            $insertData['answer'] = $request->answer;
            $insertData['status'] = $request->status;
            
            $loginCheck->update($insertData);
            $loginCheck->syncQuestionSources($sourceIds);

            $language_list = Language::where('status',1)->get();

            foreach($language_list as $value){
                $question_detail = Question_detail::where('question_id',$request->id)->where('language_id',$value->id)->first();
                if($question_detail){
                    $insertData = [];
                    $insertData['question_text'] = $request->question_text[$value->id];
                    if(isset($request->file('question_image')[$value->id])){
                        if ($image = $request->file('question_image')[$value->id]){
                            $insertData['question_image'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                            $destinationPath = public_path('/uploads/question');
                            $image->move($destinationPath, $insertData['question_image']);
                            if($question_detail->question_image){
                                if (file_exists(public_path('uploads/question/'.$question_detail->question_image))) {
                                    unlink(public_path('uploads/question/'.$question_detail->question_image));
                                }
                            }
                        }
                    }
                        

                    $insertData['is_option1_image'] = $request->is_option1_image[$value->id];
                    if($insertData['is_option1_image'] == 1){
                        if(isset($request->file('option1_image')[$value->id])){
                            if ($image = $request->file('option1_image')[$value->id]){
                                $insertData['option1'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                                $destinationPath = public_path('/uploads/question');
                                $image->move($destinationPath, $insertData['option1']);
                                if($question_detail->option1){
                                    if (file_exists(public_path('uploads/question/'.$question_detail->option1))) {
                                        unlink(public_path('uploads/question/'.$question_detail->option1));
                                    }
                                }
                            }
                        }
                    }else{
                        $insertData['option1'] = $request->option1_text[$value->id];
                    }

                    $insertData['is_option2_image'] = $request->is_option2_image[$value->id];
                    if($insertData['is_option2_image'] == 1){
                        if(isset($request->file('option2_image')[$value->id])){
                            if ($image = $request->file('option2_image')[$value->id]){
                                $insertData['option2'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                                $destinationPath = public_path('/uploads/question');
                                $image->move($destinationPath, $insertData['option2']);
                                if($question_detail->option2){
                                    if (file_exists(public_path('uploads/question/'.$question_detail->option2))) {
                                        unlink(public_path('uploads/question/'.$question_detail->option2));
                                    }
                                }
                            }
                        }
                    }else{
                        $insertData['option2'] = $request->option2_text[$value->id];
                    }

                    $insertData['is_option3_image'] = $request->is_option3_image[$value->id];
                    if($insertData['is_option3_image'] == 1){
                        if(isset($request->file('option3_image')[$value->id])){
                            if ($image = $request->file('option3_image')[$value->id]){
                                $insertData['option3'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                                $destinationPath = public_path('/uploads/question');
                                $image->move($destinationPath, $insertData['option3']);
                                if($question_detail->option3){
                                    if (file_exists(public_path('uploads/question/'.$question_detail->option3))) {
                                        unlink(public_path('uploads/question/'.$question_detail->option3));
                                    }
                                }
                            }
                        }
                    }else{
                        $insertData['option3'] = $request->option3_text[$value->id];
                    }

                    $insertData['is_option4_image'] = $request->is_option4_image[$value->id];
                    if($insertData['is_option4_image'] == 1){
                        if(isset($request->file('option4_image')[$value->id])){
                            if ($image = $request->file('option4_image')[$value->id]){
                                $insertData['option4'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                                $destinationPath = public_path('/uploads/question');
                                $image->move($destinationPath, $insertData['option4']);
                                if($question_detail->option4){
                                    if (file_exists(public_path('uploads/question/'.$question_detail->option4))) {
                                        unlink(public_path('uploads/question/'.$question_detail->option4));
                                    }
                                }
                            }
                        }
                    }else{
                        $insertData['option4'] = $request->option4_text[$value->id];
                    }

                    $insertData['solution'] = $request->solution[$value->id];

                    $insertData['answer_behavior_tag1'] = $request->answer_behavior_tag1[$value->id];
                    $insertData['answer_behavior_tag2'] = $request->answer_behavior_tag2[$value->id];
                    $insertData['answer_behavior_tag3'] = $request->answer_behavior_tag3[$value->id];
                    $insertData['answer_behavior_tag4'] = $request->answer_behavior_tag4[$value->id];

                    $insertData['status'] = $request->status;
                    $question_detail->update($insertData);
                }else{
                    $insertData = [];
                    $insertData['question_text'] = $request->question_text[$value->id];
                    if(isset($request->file('question_image')[$value->id])){
                        if ($image = $request->file('question_image')[$value->id]){
                            $insertData['question_image'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                            $destinationPath = public_path('/uploads/question');
                            $image->move($destinationPath, $insertData['question_image']);
                        }
                    }
                        

                    $insertData['is_option1_image'] = $request->is_option1_image[$value->id];
                    if($insertData['is_option1_image'] == 1){
                        if(isset($request->file('option1_image')[$value->id])){
                            if ($image = $request->file('option1_image')[$value->id]){
                                $insertData['option1'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                                $destinationPath = public_path('/uploads/question');
                                $image->move($destinationPath, $insertData['option1']);
                            }
                        }
                    }else{
                        $insertData['option1'] = $request->option1_text[$value->id] ?? null;
                    }

                    $insertData['is_option2_image'] = $request->is_option2_image[$value->id];
                    if($insertData['is_option2_image'] == 1){
                        if(isset($request->file('option2_image')[$value->id])){
                            if ($image = $request->file('option2_image')[$value->id]){
                                $insertData['option2'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                                $destinationPath = public_path('/uploads/question');
                                $image->move($destinationPath, $insertData['option2']);
                            }
                        }
                    }else{
                        $insertData['option2'] = $request->option2_text[$value->id] ?? null;
                    }

                    $insertData['is_option3_image'] = $request->is_option3_image[$value->id];
                    if($insertData['is_option3_image'] == 1){
                        if(isset($request->file('option3_image')[$value->id])){
                            if ($image = $request->file('option3_image')[$value->id]){
                                $insertData['option3'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                                $destinationPath = public_path('/uploads/question');
                                $image->move($destinationPath, $insertData['option3']);
                            }
                        }
                    }else{
                        $insertData['option3'] = $request->option3_text[$value->id] ?? null;
                    }

                    $insertData['is_option4_image'] = $request->is_option4_image[$value->id];
                    if($insertData['is_option4_image'] == 1){
                        if(isset($request->file('option4_image')[$value->id])){
                            if ($image = $request->file('option4_image')[$value->id]){
                                $insertData['option4'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                                $destinationPath = public_path('/uploads/question');
                                $image->move($destinationPath, $insertData['option4']);
                            }
                        }
                    }else{
                        $insertData['option4'] = $request->option4_text[$value->id] ?? null;
                    }

                    $insertData['answer_behavior_tag1'] = $request->answer_behavior_tag1[$value->id];
                    $insertData['answer_behavior_tag2'] = $request->answer_behavior_tag2[$value->id];
                    $insertData['answer_behavior_tag3'] = $request->answer_behavior_tag3[$value->id];
                    $insertData['answer_behavior_tag4'] = $request->answer_behavior_tag4[$value->id];

                    $insertData['solution'] = $request->solution[$value->id];
                    $insertData['question_id'] = $request->id;
                    $insertData['language_id'] = $value->id;
                    $insertData['status'] = $request->status;
                    Question_detail::create($insertData);
                }
            }

            toastr()->success('Question updated successfully.');
            return redirect()->route('admin.question');
        }else{
            toastr()->warning('Question already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        $loginCheck = Question::where('id',$request->id)->first();

        if($loginCheck){
            $loginCheck->update(["is_deleted"=>1]);
            Question_detail::forgetDuplicateCache();
        }
        toastr()->success('Question deleted successfully.');
        return redirect()->route('admin.question');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Question::where('id',$request->id)->first();

        if($loginCheck){
            $status = $loginCheck->status ? 0 : 1;
            $loginCheck->update(["status"=>$status]);
            Question_detail::where('question_id', $loginCheck->id)->update(['status' => $status]);
            toastr()->success('Question status change successfully.');
        } else {
            toastr()->error('Record not found.');
        }
        return redirect()->route('admin.question');
    }

    /** Toggle single question between Custom Test (0) and Course Packages premium (1). */
    public function changeSegment(Request $request)
    {
        if (!Schema::hasColumn('questions', 'is_premium')) {
            toastr()->error('Premium column is missing. Run migrations first.');
            return back();
        }

        $question = Question::where('id', $request->id)->where('is_deleted', 0)->first();
        if (!$question) {
            toastr()->error('Question not found.');
            return back();
        }

        $next = (int) $question->is_premium === 1 ? 0 : 1;
        $question->update(['is_premium' => $next]);

        toastr()->success($next
            ? 'Moved to Premium (Course Packages / Chapter Master).'
            : 'Moved to Non-premium (Custom Test).'
        );

        return redirect()->back();
    }

    /** Bulk mark selected questions as premium or non-premium. */
    public function bulkSegment(Request $request)
    {
        if (!Schema::hasColumn('questions', 'is_premium')) {
            toastr()->error('Premium column is missing. Run migrations first.');
            return back();
        }

        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
            'segment' => ['required', 'in:0,1'],
        ]);

        $ids = array_values(array_unique(array_map('intval', $data['ids'])));
        $segment = (int) $data['segment'];

        $query = Question::whereIn('id', $ids)->where('is_deleted', 0);
        if (session()->get('admmin_is_super') == 'T') {
            $query->where('subject_id', session()->get('admmin_subject_id'));
        }

        $updated = $query->update(['is_premium' => $segment]);

        toastr()->success($updated.' question(s) set as '.($segment === 1
            ? 'Premium (Course Packages)'
            : 'Non-premium (Custom Test)'
        ).'.');

        return redirect()->back();
    }

    /**
     * Coverage board: which chapters/topics already have premium questions
     * (Chapter Master shows Coming soon where count is 0).
     */
    public function premiumBank(Request $request)
    {
        if (!Schema::hasColumn('questions', 'is_premium')) {
            toastr()->warning('Run the is_premium migration first.');
            return redirect()->route('admin.question');
        }

        $subjectId = $request->input('subject_id');
        $subjectsQuery = Subject::where('status', 1)->where(function ($q) {
            $q->where('is_deleted', 0)->orWhereNull('is_deleted');
        });
        if (session()->get('admmin_is_super') == 'T') {
            $subjectsQuery->where('id', session()->get('admmin_subject_id'));
        }
        $subjects = $subjectsQuery->orderBy('name')->get();

        if (!$subjectId && $subjects->count()) {
            $subjectId = $subjects->first()->id;
        }

        $chapters = collect();
        $stats = [
            'premium_total' => 0,
            'chapters_ready' => 0,
            'chapters_soon' => 0,
            'topics_ready' => 0,
            'topics_soon' => 0,
        ];

        if ($subjectId) {
            $chapterRows = Chapter::where('subject_id', $subjectId)->where('status', 1)->orderBy('name')->get();
            $chapterCounts = Question::query()
                ->select('chapter_id', DB::raw('COUNT(*) as total'))
                ->where('subject_id', $subjectId)
                ->where('is_deleted', 0)
                ->where('status', 1)
                ->where('is_premium', 1)
                ->whereNotNull('chapter_id')
                ->groupBy('chapter_id')
                ->pluck('total', 'chapter_id');

            $topicCounts = Question::query()
                ->select('topic_id', DB::raw('COUNT(*) as total'))
                ->where('subject_id', $subjectId)
                ->where('is_deleted', 0)
                ->where('status', 1)
                ->where('is_premium', 1)
                ->whereNotNull('topic_id')
                ->groupBy('topic_id')
                ->pluck('total', 'topic_id');

            $topics = Topic::where('subject_id', $subjectId)->where('status', 1)->orderBy('name')->get(['id', 'chapter_id', 'name']);
            $topicsByChapter = $topics->groupBy('chapter_id');

            $chapters = $chapterRows->map(function ($ch) use ($chapterCounts, $topicCounts, $topicsByChapter, &$stats) {
                $qCount = (int) ($chapterCounts[$ch->id] ?? 0);
                $topicList = ($topicsByChapter->get($ch->id) ?? collect())->map(function ($t) use ($topicCounts, &$stats) {
                    $tq = (int) ($topicCounts[$t->id] ?? 0);
                    if ($tq > 0) {
                        $stats['topics_ready']++;
                    } else {
                        $stats['topics_soon']++;
                    }
                    return [
                        'id' => $t->id,
                        'name' => $t->name,
                        'questions' => $tq,
                        'coming_soon' => $tq < 1,
                    ];
                })->values();

                if ($qCount > 0) {
                    $stats['chapters_ready']++;
                    $stats['premium_total'] += $qCount;
                } else {
                    $stats['chapters_soon']++;
                }

                return [
                    'id' => $ch->id,
                    'name' => $ch->name,
                    'questions' => $qCount,
                    'coming_soon' => $qCount < 1,
                    'topics' => $topicList,
                ];
            })->values();
        }

        return view('admin.question.premium_bank', [
            'subjects' => $subjects,
            'subject_id' => $subjectId,
            'chapters' => $chapters,
            'stats' => $stats,
        ]);
    }

    public function upload(){
        $data = [];
        if(session()->get('admmin_is_super') == 'T'){
            $data['subject_list'] = Subject::where('status',1)->where('id',session()->get('admmin_subject_id'))->get();
        }else{
            $data['subject_list'] = Subject::where('status',1)->get();
        }
        return view('admin.question.upload',$data);
    }

    /**
     * Download sample XLSX mixed with normal MCQs + image question/answer examples,
     * plus a zip of referenced images when present.
     */
    public function upload_sample()
    {
        $headers = [
            'Sl No',
            'Chapter',
            'Topic',
            'Sub Topic',
            'Difficulty Level',
            'Source',
            'Question Source',
            'Question Text',
            'Question Image',
            'Option1',
            'Is Option1 Image',
            'Ans Behavior Tag 1',
            'Option2',
            'Is Option2 Image',
            'Ans Behavior Tag 2',
            'Option3',
            'Is Option3 Image',
            'Ans Behavior Tag 3',
            'Option4',
            'Is Option4 Image',
            'Ans Behavior Tag 4',
            'Answer',
            'Solution Video Link',
            'Solution',
            'Premium',
            'Status',
        ];

        $sampleRows = $this->buildMixedSampleQuestions();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Questions');
        $sheet->fromArray($headers, null, 'A1');

        $imageFiles = [];
        $excelRows = [];
        $n = 1;
        foreach ($sampleRows as $row) {
            $qImg = $this->sampleImageBasename($row->question_image);
            $o1 = $row->is_option1_image ? $this->sampleImageBasename($row->option1) : $this->plainSampleText($row->option1);
            $o2 = $row->is_option2_image ? $this->sampleImageBasename($row->option2) : $this->plainSampleText($row->option2);
            $o3 = $row->is_option3_image ? $this->sampleImageBasename($row->option3) : $this->plainSampleText($row->option3);
            $o4 = $row->is_option4_image ? $this->sampleImageBasename($row->option4) : $this->plainSampleText($row->option4);

            foreach ([
                $qImg,
                $row->is_option1_image ? $o1 : null,
                $row->is_option2_image ? $o2 : null,
                $row->is_option3_image ? $o3 : null,
                $row->is_option4_image ? $o4 : null,
            ] as $imgName) {
                if ($imgName) {
                    $imageFiles[$imgName] = public_path('uploads/question/'.$imgName);
                }
            }

            $excelRows[] = [
                $n++,
                (int) $row->chapter_id,
                (int) $row->topic_id,
                (int) $row->sub_topic_id,
                (int) $row->difficulty_level,
                (int) $row->source_id,
                (int) $row->question_source_id,
                $this->plainSampleText($row->question_text),
                $qImg,
                $o1,
                (int) $row->is_option1_image,
                (int) $row->answer_behavior_tag1,
                $o2,
                (int) $row->is_option2_image,
                (int) $row->answer_behavior_tag2,
                $o3,
                (int) $row->is_option3_image,
                (int) $row->answer_behavior_tag3,
                $o4,
                (int) $row->is_option4_image,
                (int) $row->answer_behavior_tag4,
                (int) $row->answer,
                (string) $row->solution_video_link,
                $this->plainSampleText($row->solution),
                (int) ($row->is_premium ?? 0),
                (int) $row->status,
            ];
        }

        if (empty($excelRows)) {
            $excelRows[] = [1, 1, 1, 1, 1, 1, 1, 'Sample question text?', '', 'Option A', 0, 1, 'Option B', 0, 2, 'Option C', 0, 3, 'Option D', 0, 4, 1, '', 'Brief solution', 0, 1];
        }

        $sheet->fromArray($excelRows, null, 'A2');
        foreach (range('A', 'Z') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getStyle('A1:Z1')->getFont()->setBold(true);
        $sheet->freezePane('A2');
        $sheet->getStyle('A1:Z1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E8EEF7');

        $tmpDir = storage_path('app/tmp');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $xlsxName = 'question_sample.xlsx';
        $xlsxPath = $tmpDir.DIRECTORY_SEPARATOR.$xlsxName;
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($xlsxPath);

        $existingImages = [];
        foreach ($imageFiles as $name => $path) {
            if (is_file($path)) {
                $existingImages[$name] = $path;
            }
        }

        if (empty($existingImages)) {
            return response()->download($xlsxPath, $xlsxName)->deleteFileAfterSend(true);
        }

        $zipName = 'question_bulk_sample.zip';
        $zipPath = $tmpDir.DIRECTORY_SEPARATOR.$zipName;
        if (is_file($zipPath)) {
            @unlink($zipPath);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return response()->download($xlsxPath, $xlsxName)->deleteFileAfterSend(true);
        }
        $zip->addFile($xlsxPath, $xlsxName);
        foreach ($existingImages as $name => $path) {
            $zip->addFile($path, 'images/'.$name);
        }
        $zip->addFromString(
            'README.txt',
            "Bulk Question Upload Sample\n\n"
            ."Rows are mixed examples:\n"
            ."  - Normal text MCQs\n"
            ."  - 2 questions where BOTH the question and the answer options use images\n\n"
            ."1. Keep the header row as-is.\n"
            ."2. Chapter / Topic / Sub Topic are numeric IDs from admin lists.\n"
            ."3. For image cells: put the file name only, set Is OptionN Image = 1,\n"
            ."   and include those files in your Image Zip when uploading.\n"
            ."4. On upload: select Subject on the form, then upload XLSX (+ Image Zip).\n"
            ."5. Difficulty: 1=Easy, 2=Medium, 3=Hard. Answer: 1-4.\n"
        );
        $zip->close();
        @unlink($xlsxPath);

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }

    /**
     * Build a mixed sample set: normal MCQs + 2 image question/answer examples.
     */
    protected function buildMixedSampleQuestions()
    {
        $lang = getDefaultLanguage();
        $columns = [
            'questions.id',
            'questions.subject_id',
            'questions.chapter_id',
            'questions.topic_id',
            'questions.sub_topic_id',
            'questions.difficulty_level',
            'questions.question_type_id',
            'questions.source_id',
            'questions.question_source_id',
            'questions.answer',
            'questions.solution_video_link',
            'questions.status',
            'question_details.question_text',
            'question_details.question_image',
            'question_details.option1',
            'question_details.is_option1_image',
            'question_details.option2',
            'question_details.is_option2_image',
            'question_details.option3',
            'question_details.is_option3_image',
            'question_details.option4',
            'question_details.is_option4_image',
            'question_details.answer_behavior_tag1',
            'question_details.answer_behavior_tag2',
            'question_details.answer_behavior_tag3',
            'question_details.answer_behavior_tag4',
            'question_details.solution',
        ];

        $base = Question_detail::query()
            ->select($columns)
            ->join('questions', 'questions.id', '=', 'question_details.question_id')
            ->where('questions.is_deleted', 0)
            ->where('question_details.language_id', $lang);

        // Question image + at least the correct-answer option is an image (prefer all options image)
        $imageBoth = (clone $base)
            ->whereNotNull('question_details.question_image')
            ->where('question_details.question_image', '!=', '')
            ->where(function ($q) {
                $q->where(function ($qq) {
                    $qq->where('questions.answer', 1)->where('question_details.is_option1_image', 1);
                })->orWhere(function ($qq) {
                    $qq->where('questions.answer', 2)->where('question_details.is_option2_image', 1);
                })->orWhere(function ($qq) {
                    $qq->where('questions.answer', 3)->where('question_details.is_option3_image', 1);
                })->orWhere(function ($qq) {
                    $qq->where('questions.answer', 4)->where('question_details.is_option4_image', 1);
                });
            })
            ->orderByDesc('questions.id')
            ->limit(2)
            ->get();

        if ($imageBoth->count() < 2) {
            $extra = (clone $base)
                ->whereNotNull('question_details.question_image')
                ->where('question_details.question_image', '!=', '')
                ->where(function ($q) {
                    $q->where('question_details.is_option1_image', 1)
                        ->orWhere('question_details.is_option2_image', 1)
                        ->orWhere('question_details.is_option3_image', 1)
                        ->orWhere('question_details.is_option4_image', 1);
                })
                ->whereNotIn('questions.id', $imageBoth->pluck('id')->all())
                ->orderByDesc('questions.id')
                ->limit(2 - $imageBoth->count())
                ->get();
            $imageBoth = $imageBoth->concat($extra);
        }

        $excludeIds = $imageBoth->pluck('id')->all();

        $normal = (clone $base)
            ->where(function ($q) {
                $q->whereNull('question_details.question_image')
                    ->orWhere('question_details.question_image', '');
            })
            ->where('question_details.is_option1_image', 0)
            ->where('question_details.is_option2_image', 0)
            ->where('question_details.is_option3_image', 0)
            ->where('question_details.is_option4_image', 0)
            ->when(!empty($excludeIds), function ($q) use ($excludeIds) {
                $q->whereNotIn('questions.id', $excludeIds);
            })
            ->orderByDesc('questions.id')
            ->limit(3)
            ->get();

        // Mix: normal, image, normal, image, normal
        $mixed = collect();
        $img = $imageBoth->values();
        $txt = $normal->values();
        $pattern = ['n', 'i', 'n', 'i', 'n'];
        $ni = 0;
        $ii = 0;
        foreach ($pattern as $kind) {
            if ($kind === 'i' && isset($img[$ii])) {
                $mixed->push($img[$ii++]);
            } elseif ($kind === 'n' && isset($txt[$ni])) {
                $mixed->push($txt[$ni++]);
            }
        }
        // Append leftovers if pattern slots were empty
        while (isset($img[$ii])) {
            $mixed->push($img[$ii++]);
        }
        while (isset($txt[$ni])) {
            $mixed->push($txt[$ni++]);
        }

        if ($mixed->isEmpty()) {
            return (clone $base)->orderByDesc('questions.id')->limit(5)->get();
        }

        return $mixed->take(5)->values();
    }

    public function upload_save(Request $request){
        $request->validate([
            'subject_id' => 'required|integer|exists:subjects,id',
            'csv_file' => 'required|file|max:20480',
            'zip_file' => 'nullable|file|max:51200',
        ], [
            'subject_id.required' => 'Please select a subject.',
            'csv_file.required' => 'Please choose an XLSX file.',
        ]);

        $csvName = strtolower($request->file('csv_file')->getClientOriginalName());
        if (!preg_match('/\.(xlsx|xls|csv)$/', $csvName)) {
            return redirect()->route('admin.question.upload')
                ->with('upload_error', 'Please upload an .xlsx, .xls, or .csv file.');
        }
        if ($request->hasFile('zip_file')) {
            $zipName = strtolower($request->file('zip_file')->getClientOriginalName());
            if (!str_ends_with($zipName, '.zip')) {
                return redirect()->route('admin.question.upload')
                    ->with('upload_error', 'Image package must be a .zip file.');
            }
        }

        $subject_id = (int) $request->subject_id;
        $adminAuth = session()->get('adminAuth');
        $administrator_id = is_array($adminAuth) ? (int) ($adminAuth['id'] ?? 0) : (int) $adminAuth;

        $imageMap = [];
        if ($request->hasFile('zip_file')) {
            $imageMap = $this->extractQuestionImageZip($request->file('zip_file'));
            if ($imageMap === null) {
                return redirect()->route('admin.question.upload')
                    ->with('upload_error', 'Could not read the Image Zip. Use a .zip of png/jpg/jpeg/gif/webp files.');
            }
        }

        $import = new ExcelToDbImport($subject_id, $administrator_id, $imageMap);
        Excel::import($import, $request->file('csv_file'));
        $summary = $import->getSummary();

        return redirect()->route('admin.question.upload')
            ->with('success123', 1)
            ->with('upload_imported', $summary['imported'])
            ->with('upload_skipped', $summary['skipped'])
            ->with('upload_errors', $summary['errors'])
            ->with('message123', $summary['duplicates']);
    }

    /**
     * Flatten-extract image files from zip into public/uploads/question.
     * Returns map of original basename (lowercase) => stored unique filename.
     * Returns null if zip cannot be opened.
     */
    protected function extractQuestionImageZip($uploadedZip): ?array
    {
        $tmpName = 'qzip_'.time().'_'.Str::random(6).'.zip';
        $tmpPath = public_path('uploads/'.$tmpName);
        $uploadedZip->move(public_path('uploads'), $tmpName);

        $zip = new ZipArchive();
        $res = $zip->open($tmpPath);
        if ($res !== true) {
            @unlink($tmpPath);
            return null;
        }

        $dest = public_path('uploads/question');
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        $allowed = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
        $map = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->getNameIndex($i);
            if ($entry === false) {
                continue;
            }
            // Skip directories and macOS junk
            if (substr($entry, -1) === '/' || str_contains($entry, '__MACOSX')) {
                continue;
            }

            $base = basename(str_replace('\\', '/', $entry));
            $ext = strtolower(pathinfo($base, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed, true)) {
                continue;
            }

            $safeBase = preg_replace('/[^A-Za-z0-9._-]/', '_', $base);
            $stored = uniqid('', true).'_'.$safeBase;
            $stream = $zip->getStream($entry);
            if ($stream === false) {
                continue;
            }
            $contents = stream_get_contents($stream);
            fclose($stream);
            if ($contents === false || $contents === '') {
                continue;
            }
            file_put_contents($dest.DIRECTORY_SEPARATOR.$stored, $contents);
            $map[strtolower($base)] = $stored;
            $map[strtolower($safeBase)] = $stored;
        }

        $zip->close();
        @unlink($tmpPath);

        return $map;
    }

    protected function plainSampleText($value): string
    {
        $value = html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = strip_tags($value);
        $value = str_replace("\xc2\xa0", ' ', $value);
        $value = preg_replace('/\s+/u', ' ', $value);
        return trim($value ?? '');
    }

    protected function sampleImageBasename($value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }
        return basename(str_replace('\\', '/', $value));
    }

    /**
     * @return array<int,int>
     */
    private function selectedQuestionSourceIds(Request $request): array
    {
        $raw = $request->input('question_source_id', []);
        if (! is_array($raw)) {
            $raw = ($raw === null || $raw === '') ? [] : [$raw];
        }

        return array_values(array_unique(array_filter(array_map('intval', $raw))));
    }
    
}
