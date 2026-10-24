<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Exam;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\Exam_subject;
use App\Models\Exam_question;
use App\Models\Location;
use App\Models\Question;
use App\Models\Question_detail;
use App\Models\Question_paper;
use App\Models\Exam_user;
use App\Models\Exam_status;
use App\Models\Exam_proctoring_event;
use App\Models\CustomTest;
use Illuminate\Support\Facades\Schema;
use DB;

class Online_examController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Exam::where('type',1)
            ->where('is_deleted',0)
            ->where(function ($q) {
                $q->whereNull('exam_code')->orWhere('exam_code', 'not like', 'CT-%');
            })
            ->whereNotIn('id', CustomTest::examIdQuery())
            ->orderByDesc('id')
            ->get();
        return view('admin.online_exam.list',$data);
    }


    public function add(){
        $data = [];
        $data['location_list'] = Location::where('status',1)->where('is_deleted', 0)->get();
        $data['subject_list'] = Subject::select(['id','name'])->where('status',1)->get();
        $data['question_paper_list'] = Question_paper::where('status',1)
            ->whereNotIn('id', CustomTest::questionPaperIdQuery())
            ->get();
        $data['batch_list'] = $this->activeBatches();
        $data['selected_batch_ids'] = [];
        return view('admin.online_exam.add',$data);
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required',
        ]);

        $schedule = $this->schedulePayload($request);
        $batchIds = $this->selectedBatchIds($request);
        if ($redirect = $this->scheduleOrBatchRedirect($schedule, $batchIds)) {
            return $redirect;
        }

        $loginCheck = Exam::where('exam_code',$request->exam_code)->first();

        if (!$loginCheck){
            $insertData = $this->examFieldPayload($request, $schedule);
            $insertData['type'] = 1;
            if ($image = $request->file('exam_logo')){
                $insertData['exam_logo'] = $this->storeExamImage($image);
            }
            if ($image = $request->file('landing_icon')){
                $insertData['landing_icon'] = $this->storeExamImage($image);
            }

            $exam = Exam::create($insertData);
            $this->syncExamBatches($exam, $batchIds);

            toastr()->success('Online Exam added successfully.');
            return redirect()->route('admin.online_exam');
        }else{
            toastr()->warning('Online Exam already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $data = [];
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['details'] = Exam::where('id',$request->id)->first();
        $data['question_paper_list'] = Question_paper::where('status',1)
            ->whereNotIn('id', CustomTest::questionPaperIdQuery())
            ->get();
        $data['batch_list'] = $this->activeBatches();
        $data['selected_batch_ids'] = $data['details']
            ? $data['details']->batches()->pluck('batches.id')->map(fn ($id) => (int) $id)->values()->all()
            : [];
        return view('admin.online_exam.edit',$data);
    }

    public function update(Request $request){
        $request->validate([
            'name' => 'required',
        ]);

        $schedule = $this->schedulePayload($request);
        $batchIds = $this->selectedBatchIds($request);
        if ($redirect = $this->scheduleOrBatchRedirect($schedule, $batchIds)) {
            return $redirect;
        }

        $loginCheck = Exam::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = $this->examFieldPayload($request, $schedule);
            if ($image = $request->file('exam_logo')){
                $insertData['exam_logo'] = $this->storeExamImage($image);
                $this->deleteExamImage($loginCheck->exam_logo);
            }
            if ($image = $request->file('landing_icon')){
                $insertData['landing_icon'] = $this->storeExamImage($image);
                $this->deleteExamImage($loginCheck->landing_icon);
            }

            $loginCheck->update($insertData);
            $this->syncExamBatches($loginCheck, $batchIds);
            toastr()->success('Online Exam updated successfully.');
            return redirect()->route('admin.online_exam');
        }else{
            toastr()->warning('Online Exam already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        $exam = Exam::where('id',$id)->first();
        // dd($exam);
        if($exam){
            $exam->update(['is_deleted'=>1]);
        }
        
        toastr()->success('Online Exam deleted successfully.');
        return redirect()->route('admin.online_exam');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Exam::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Online Exam status change successfully.');
        return redirect()->route('admin.online_exam');
    }

    public function view(Request $request)
    {
        $exam = Exam::where('id', $request->id)->where('is_deleted', 0)->firstOrFail();
        $list = Exam_status::select([
                'exam_statuses.type',
                'exam_statuses.created_at',
                'users.first_name',
                'users.last_name',
                'users.email_id',
            ])
            ->leftJoin('users', 'exam_statuses.user_id', '=', 'users.id')
            ->where('exam_statuses.exam_id', $exam->id)
            ->orderByDesc('exam_statuses.id')
            ->get();

        return view('admin.online_exam.view', [
            'details' => $exam,
            'list' => $list,
        ]);
    }

    public function end(Request $request){
        $id = $request->id;

        $loginCheck = Exam::select(['exams.*','question_papers.totals_marks_for_exam'])
                        ->leftJoin('question_papers', 'exams.question_paper_id', '=', 'question_papers.id')
                        ->where('exams.id',$request->id)->first();

        if($loginCheck){

            $loginCheck->update(['is_ended'=>1,'ended_date'=>Carbon::now()]);

            $exam_user = Exam_user::select(['exam_users.*','users.rank','users.total_mark','users.mark'])
                                ->leftJoin('users', 'exam_users.user_id', '=', 'users.id')
                                ->where('exam_users.exam_id',$loginCheck->id)->orderBy('exam_users.total_number','DESC')->get();
            // dd($exam_user);
            $rank = 1;
            foreach($exam_user as $value){
                Exam_user::where('id',$value->id)->update(["rank"=>$rank,"total_mark"=>$loginCheck->totals_marks_for_exam]);
                $mark = $value->total_number + $value->mark;
                $total_mark = $value->total_mark + $loginCheck->totals_marks_for_exam;

                $mark_avg = $mark / $total_mark * 100;

                User::where('id',$value->user_id)->update(["total_mark"=>$total_mark,"mark"=>$mark,"mark_avg"=>$mark_avg]);
                $rank = $rank + 1;
            }

            $users = User::orderBy('mark_avg','DESC')->get();

            $rank = 1;
            foreach ($users as $key => $value) {
                User::where('id',$value->id)->update(["rank"=>$rank]);
                $rank = $rank + 1;
            }

            toastr()->success('Online Exam ended successfully.');
        }else{
            toastr()->success('Online Exam already ended.');
        }

        
        return redirect()->route('admin.online_exam');
    }

    public function question(Request $request){

        $data = [];
        $data['details'] = Exam::where('id',$request->id)->first();
        $data['list'] = Exam_question::select(['questions.*','exam_questions.exam_id','exam_questions.exam_subject_id','subjects.name as subject_name'])
            ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
            ->leftJoin('subjects', 'questions.subject_id', '=', 'subjects.id')
            ->where('exam_questions.exam_id',$request->id)->get();

        foreach ($data['list'] as $key => $value) {
            $question = Question_detail::where("question_id",$value->id)->where("language_id",1)->first();
            $data['list'][$key]->question_text = $question->question_text;
            $data['list'][$key]->question_image = $question->question_image;
        }
        // dd($data['list']);
        return view('admin.online_exam.question',$data);
    }

    public function result(Request $request){

        $data = [];
        $data['details'] = Exam::where('id',$request->id)->first();
        $data['list'] = Offline_exam_user::select(['offline_exam_users.*','users.first_name','users.last_name','users.email_id'])
            ->leftJoin('users', 'offline_exam_users.user_id', '=', 'users.id')
            ->where('offline_exam_users.exam_id',$request->id)->get();

        
        // dd($data['list'][0]);
        return view('admin.online_exam.exam_result',$data);
    }

    public function result_details(Request $request){

        $data = [];
        $data['details'] = Exam::where('id',$request->id)->first();
        $data['list'] = Offline_exam_question_result::select(['offline_exam_question_results.*','offline_exam_questions.question_id','offline_exam_questions.video_link','offline_exam_questions.answer as right_answer'])
            ->leftJoin('offline_exam_questions', 'offline_exam_question_results.offline_exam_question_id', '=', 'offline_exam_questions.id')
            ->where('offline_exam_question_results.exam_id',$request->exam_id)
            ->where('offline_exam_question_results.offline_exam_user_id',$request->id)
            ->get();

        
        // dd($data['list']);
        return view('admin.online_exam.result_details',$data);
    }

    public function proctoring(Request $request){
        $data = [];
        $data['details'] = Exam::where('id',$request->id)->first();
        $data['list'] = Exam_user::select([
                'exam_users.*',
                'users.first_name',
                'users.last_name',
                'users.email_id',
                DB::raw('(select count(*) from exam_proctoring_events where exam_proctoring_events.exam_user_id = exam_users.id and exam_proctoring_events.event_type != "snapshot") as violation_count'),
                DB::raw('(select count(*) from exam_proctoring_events where exam_proctoring_events.exam_user_id = exam_users.id and exam_proctoring_events.event_type = "snapshot") as snapshot_count'),
            ])
            ->leftJoin('users', 'users.id', '=', 'exam_users.user_id')
            ->where('exam_users.exam_id', $request->id)
            ->orderBy('exam_users.id', 'desc')
            ->get();

        return view('admin.online_exam.proctoring',$data);
    }

    public function proctoring_detail(Request $request){
        $data = [];
        $data['user_exam'] = Exam_user::select(['exam_users.*','users.first_name','users.last_name','users.email_id'])
            ->leftJoin('users', 'users.id', '=', 'exam_users.user_id')
            ->where('exam_users.id', $request->id)
            ->first();
        $data['details'] = Exam::where('id', optional($data['user_exam'])->exam_id)->first();
        $data['events'] = Exam_proctoring_event::where('exam_user_id', $request->id)->orderBy('id', 'desc')->get();
        return view('admin.online_exam.proctoring_detail',$data);
    }

    public function proctoring_snapshot(Request $request){
        $event = Exam_proctoring_event::where('id', $request->id)->where('event_type', 'snapshot')->first();
        if (!$event || !$event->image_path) {
            abort(404);
        }

        $path = ltrim(str_replace('\\', '/', $event->image_path), '/');
        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }
        if (str_starts_with($path, 'uploads/')) {
            $full = public_path($path);
        } else {
            $full = storage_path('app/'.$path);
        }
        if (!is_file($full)) {
            abort(404);
        }

        return response()->file($full, ['Content-Type' => 'image/jpeg']);
    }

    private function activeBatches()
    {
        return Batch::where('status', 1)->orderBy('name')->get(['id', 'name']);
    }

    private function selectedBatchIds(Request $request): array
    {
        $ids = $request->input('batches', $request->input('batch_id'));
        if (!is_array($ids)) {
            $ids = ($ids !== null && $ids !== '') ? [$ids] : [];
        }

        return array_values(array_unique(array_filter(array_map('intval', $ids))));
    }

    private function normalizeClock($value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }
        if (preg_match('/^\d{2}:\d{2}$/', $value)) {
            return $value.':00';
        }
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $value)) {
            return $value;
        }

        return null;
    }

    private function schedulePayload(Request $request): array
    {
        $examDate = $request->filled('exam_date') ? $request->exam_date : null;
        $examTime = $this->normalizeClock($request->exam_time);
        $endDate = $request->filled('exam_end_date') ? $request->exam_end_date : null;
        $endTime = $this->normalizeClock($request->exam_end_time);

        if ($endDate && !$endTime) {
            $endTime = '23:59:59';
        }
        if ($endTime && !$endDate) {
            $endDate = $examDate;
        }

        return [
            'exam_date' => $examDate,
            'exam_time' => $examTime,
            'exam_end_date' => $endDate,
            'exam_end_time' => $endTime,
        ];
    }

    private function scheduleOrBatchRedirect(array $schedule, array $batchIds)
    {
        if (empty($schedule['exam_date']) || empty($schedule['exam_time'])) {
            toastr()->warning('Exam date and time are required.');
            return back()->withInput();
        }

        if ($schedule['exam_end_date'] && $schedule['exam_end_time']) {
            $start = Carbon::parse($schedule['exam_date'].' '.$schedule['exam_time']);
            $end = Carbon::parse($schedule['exam_end_date'].' '.$schedule['exam_end_time']);
            if ($end->lte($start)) {
                toastr()->warning('Exam end date and time must be after the exam date and time.');
                return back()->withInput();
            }
        }

        if (empty($batchIds)) {
            toastr()->warning('Please select a batch.');
            return back()->withInput();
        }

        return null;
    }

    private function examFieldPayload(Request $request, array $schedule): array
    {
        return array_merge($schedule, [
            'exam_code' => $request->exam_code,
            'name' => $request->name,
            'price' => $request->price,
            'dis_price' => $request->dis_price,
            'tax' => $request->tax,
            'question_paper_id' => $request->question_paper_id,
            'exam_instructions' => $request->exam_instructions,
            'description' => $request->description,
            'result_title' => $request->result_title,
            'result_description' => $request->result_description,
            'result_declaration' => $request->result_declaration,
            'status' => $request->status,
            'is_in_footer' => $request->is_in_footer ? $request->is_in_footer : 0,
            'is_trending' => $request->is_trending ? $request->is_trending : 0,
            'is_proctored' => $request->is_proctored ? 1 : 0,
            'proctoring_max_violations' => $request->proctoring_max_violations ? (int) $request->proctoring_max_violations : 5,
        ]);
    }

    private function syncExamBatches(Exam $exam, array $batchIds): void
    {
        $exam->batches()->sync($batchIds);
        if (Schema::hasColumn('exams', 'batch_id')) {
            $exam->forceFill(['batch_id' => $batchIds[0] ?? null])->save();
        }
    }

    private function storeExamImage($image): string
    {
        $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
        $thumbnailPath = public_path('/uploads/exam/thumbnail');
        if (!is_dir($thumbnailPath)) {
            mkdir($thumbnailPath, 0755, true);
        }
        $img = Image::make($image->getRealPath());
        $img->resize(100, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($thumbnailPath.'/'.$filename);

        $destinationPath = public_path('/uploads/exam');
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        $image->move($destinationPath, $filename);

        return $filename;
    }

    private function deleteExamImage($filename): void
    {
        if (!$filename) {
            return;
        }
        foreach (['uploads/exam/', 'uploads/exam/thumbnail/'] as $dir) {
            $path = public_path($dir.$filename);
            if (is_file($path)) {
                unlink($path);
            }
        }
    }
}
