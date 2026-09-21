<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use DB;

use App\Models\Exam;
use App\Models\Exam_user;
use App\Models\Question_paper_question;
use App\Models\User;
use App\Models\Question_detail;
use App\Models\Exam_result;
use App\Models\Question_paper;
use App\Models\Exam_proctoring_event;
use Illuminate\Support\Facades\Storage;

class ExamsController extends Controller
{
    private function getLanguageId(){
        return 1;
    }

    public function online_exam(){
        
        

        $data = [];
        $data['exam_list'] = Exam::select(['exams.*'])
                            ->where('exams.type',1)
                            ->where('exams.status',1)
                            ->where('exams.is_deleted',0)
                            ->where('exams.exam_date','>=',date('Y-m-d'))->get();
        return view('site.online_exam',$data);
        
    }

    public function start_exam(Request $request){
        $exam_id = $request->id;
        $user_id = Auth::user()->id;
        $exam_detail = Exam::where('id',$exam_id)->first();
        $paper = $exam_detail ? Question_paper::where('id', $exam_detail->question_paper_id)->first() : null;
        $durationMinutes = (int)($exam_detail->total_time_for_exam ?: ($paper->total_time_for_exam ?? 0));
        $durationSeconds = $durationMinutes * 60;

        $exam_user = Exam_user::where('exam_id',$exam_id)->where('user_id',$user_id)->orderBy('id','desc')->first();
        $needsNewAttempt = true;
        if ($exam_user) {
            $status = (string)$exam_user->proctoring_status;
            $expired = $durationSeconds > 0 && (int)$exam_user->total_time >= $durationSeconds;
            $finished = in_array($status, ['cancelled', 'auto_submitted', 'completed'], true);
            $scored = $exam_user->percentage !== null && $exam_user->percentage !== '';
            if (!$expired && !$finished && !$scored) {
                $needsNewAttempt = false;
            }
        }

        if ($needsNewAttempt) {
            $exam_user = Exam_user::create([
                'exam_id' => $exam_id,
                'user_id' => $user_id,
                'exam_type' => 'OTS',
                'total_time' => 0,
            ]);
        }

        toastr()->success('Exam start successfully.');
        return redirect()->route('start_online_exam',['id'=>$exam_user->id]);
        
    }

    public function start_online_exam(Request $request){
        $exam_user_id = $request->id;
        $data = [];
        $user_id = Auth::user()->id;

        $data['user_exam_id'] = $exam_user_id;
        $data['user_exam_detail'] = Exam_user::where('id', $exam_user_id)->where('user_id', $user_id)->first();
        if (!$data['user_exam_detail']) {
            abort(403);
        }
        $data['exam_detail'] = Exam::select(['exams.*','question_papers.*'])
                                ->leftJoin('question_papers', 'question_papers.id', '=', 'exams.question_paper_id')
                                ->where('exams.id',$data['user_exam_detail']->exam_id)->first();
        $examOnly = Exam::where('id', $data['user_exam_detail']->exam_id)->first();
        if ($examOnly && !empty($examOnly->total_time_for_exam)) {
            $data['exam_detail']->total_time_for_exam = $examOnly->total_time_for_exam;
        }

        $data['question_list'] = Question_paper_question::select([
                                'questions.id',
                                'question_paper_questions.id as question_paper_question_id'
                            ])
                                ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                ->where('question_paper_id',$data['exam_detail']->question_paper_id)->get();

        $questionIds = $data['question_list']->pluck('id')->filter()->all();
        $paperQuestionIds = $data['question_list']->pluck('question_paper_question_id')->filter()->all();
        $details = Question_detail::whereIn('question_id', $questionIds ?: [0])
                            ->where('language_id', 1)
                            ->get()
                            ->keyBy('question_id');
        $results = Exam_result::where('user_id', $user_id)
                            ->where('exam_user_id', $exam_user_id)
                            ->whereIn('exam_question_id', $paperQuestionIds ?: [0])
                            ->get()
                            ->keyBy('exam_question_id');

        foreach($data['question_list'] as $key=>$value){
            $question = $details->get($value->id);
            $data['question_list'][$key]->question_text = $question->question_text ?? '';
            $data['question_list'][$key]->question_image = $question->question_image ?? '';
            $data['question_list'][$key]->option1 = $question->option1 ?? '';
            $data['question_list'][$key]->option2 = $question->option2 ?? '';
            $data['question_list'][$key]->option3 = $question->option3 ?? '';
            $data['question_list'][$key]->option4 = $question->option4 ?? '';
            $data['question_list'][$key]->is_option1_image = $question->is_option1_image ?? 0;
            $data['question_list'][$key]->is_option2_image = $question->is_option2_image ?? 0;
            $data['question_list'][$key]->is_option3_image = $question->is_option3_image ?? 0;
            $data['question_list'][$key]->is_option4_image = $question->is_option4_image ?? 0;

            $exam_result = $results->get($value->question_paper_question_id);
            if($exam_result){
                $data['question_list'][$key]->reported = $exam_result->reported;
                $data['question_list'][$key]->review_later = $exam_result->review_later;
                $data['question_list'][$key]->time = $exam_result->time;
                $data['question_list'][$key]->answer = $exam_result->answer;
                $data['question_list'][$key]->type = $exam_result->type;
            }else{
                $data['question_list'][$key]->reported = "";
                $data['question_list'][$key]->review_later = "";
                $data['question_list'][$key]->time = "";
                $data['question_list'][$key]->answer = "";
                $data['question_list'][$key]->type = "";
            }
            $data['question_list'][$key]->makeHidden(['solution', 'solution_video_link', 'correct_answer']);
        }
        return view('site.start_online_exam',$data);
        
    }

    public function update_user_exam_question(Request $request){

        $input = $request->all();

        $user_id = Auth::user()->id;
        $exam_user_id = $request->exam_user_id;
        if (!$this->ownedExamUser($exam_user_id)) {
            abort(403);
        }
        $exam_question_id = $request->question_paper_question_id;
        $question_id = $request->id;
        $answer = $request->answer;
        $time = $request->time;
        $type = $request->type;
        $exam_id = $request->exam_id;
        $reported = ($request->reported == 1)?1:0;
        $review_later = ($request->review_later == 1)?1:0;

        $exam_result = Exam_result::where('user_id',$user_id)->where('exam_user_id',$exam_user_id)->where('exam_question_id',$exam_question_id)->first();

        $insertData = [
            "user_id"=>$user_id,
            "exam_id"=>$exam_id,
            "exam_question_id"=>$exam_question_id,
            "question_id"=>$question_id,
            "answer"=>$answer,
            "time"=>$time,
            "type"=>$type,
            "review_later"=>$review_later,
            "reported"=>$reported,
            "exam_user_id"=>$exam_user_id
        ];
        if($exam_result){
            $exam_result->update($insertData);
        }else{
            Exam_result::create($insertData);
        }
        echo 1; exit;
    }

    public function update_exam_time(Request $request){
        $user_exam_detail = $this->ownedExamUser($request->exam_user_id);
        if(!$user_exam_detail){
            abort(403);
        }

        $exam = Exam::where('id', $user_exam_detail->exam_id)->first();
        $durationMinutes = (int)($exam->total_time_for_exam ?? 0);
        $durationSeconds = $durationMinutes > 0 ? $durationMinutes * 60 : 0;
        $next = (int)$user_exam_detail->total_time + 30;
        if ($durationSeconds > 0 && $next > $durationSeconds) {
            $next = $durationSeconds;
        }
        $user_exam_detail->update(['total_time' => $next]);
    }

    public function save_exam(Request $request){
        $exam_user_id = $request->exam_user_id;
        $exam_id = $request->exam_id;
        $user_id = Auth::user()->id;

        $user_exam_detail = $this->ownedExamUser($exam_user_id);
        if (!$user_exam_detail) {
            abort(403);
        }

        $exam_details = Exam::where('id',$exam_id)->first();
        $question_paper_details = Question_paper::where('id',$exam_details->question_paper_id)->first();

        $exam_question = Question_paper_question::select('question_paper_questions.*','question_paper_questions.id as question_paper_question_id','questions.answer')
                            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                            ->where('question_paper_id',$exam_details->question_paper_id)
                            ->orderBy('id','ASC')->get();
        // dd($question_paper_details);

        $marks_per_question = ($question_paper_details->marks_per_question)?$question_paper_details->marks_per_question:1;
        $totals_marks_for_exam = ($question_paper_details->totals_marks_for_exam)?$question_paper_details->totals_marks_for_exam:1;
        $negative_marking_applicable = ($question_paper_details->negative_marking_applicable)?$question_paper_details->negative_marking_applicable:0;
        $negative_marking_per_question = ($question_paper_details->negative_marking_per_question)?$question_paper_details->negative_marking_per_question:1;
        
        $insertData = [];
        $insertData['total_mark'] = 0;
        $insertData['total_answer'] = 0;
        $insertData['total_right_answer'] = 0;
        $insertData['total_number'] = 0;
        // $insertData['total_time'] = 0;
        $insertData['question_number'] = 0;
        foreach($exam_question as $key=>$value){
            $result = 0;
            $exam_result = Exam_result::where('user_id',$user_id)->where('exam_user_id',$exam_user_id)->where('exam_question_id',$value->question_paper_question_id)->first();
            // dd($value);
            if($exam_result){
                // $insertData['total_time'] = $insertData['total_time'] + $exam_result->time;
                if($exam_result->answer){
                    $insertData['total_answer'] = $insertData['total_answer'] + 1;
                    if($value->answer == $exam_result->answer){
                        $insertData['total_right_answer'] = $insertData['total_right_answer'] + 1;
                        $result = $marks_per_question;
                    }else{
                        if($negative_marking_applicable){
                            $result = -($negative_marking_per_question);
                        }
                    }
                }
                $exam_result->update(['result'=>$result]);
            }
            
        }

        if($negative_marking_applicable == 1){
            $total_worng_answer = ($insertData['total_answer'] - $insertData['total_right_answer']);
            $total_worng_answer = $total_worng_answer * $negative_marking_per_question;
            $insertData['total_number'] = ($marks_per_question * $insertData['total_right_answer']) - $total_worng_answer;
            $insertData['percentage'] = ($insertData['total_number']) * 100 / $totals_marks_for_exam;
        }else{
            $insertData['total_number'] = ($marks_per_question * $insertData['total_right_answer']);
            $insertData['percentage'] = ($insertData['total_number']) * 100 / $totals_marks_for_exam;
        }
        $insertData['total_mark'] = $totals_marks_for_exam;

        $user_exam_detail->update($insertData);
        echo 1; exit;
    }

    public function end_exam(Request $request){
        $exam_user_id = $request->exam_user_id;
        $exam_id = $request->exam_id;
        $user_id = Auth::user()->id;

        $user_exam_detail = $this->ownedExamUser($exam_user_id);
        if (!$user_exam_detail) {
            abort(403);
        }

        $exam_details = Exam::where('id',$exam_id)->first();
        if (!$exam_details) {
            abort(404);
        }
        $question_paper_details = Question_paper::where('id',$exam_details->question_paper_id)->first();
        if (!$question_paper_details) {
            abort(404);
        }

        $exam_question = Question_paper_question::select('question_paper_questions.*','question_paper_questions.id as question_paper_question_id','questions.answer')
                            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                            ->where('question_paper_id',$exam_details->question_paper_id)
                            ->orderBy('id','ASC')->get();

        $marks_per_question = ($question_paper_details->marks_per_question)?$question_paper_details->marks_per_question:1;
        $totals_marks_for_exam = ($question_paper_details->totals_marks_for_exam)?$question_paper_details->totals_marks_for_exam:1;
        $negative_marking_applicable = ($question_paper_details->negative_marking_applicable)?$question_paper_details->negative_marking_applicable:0;
        $negative_marking_per_question = ($question_paper_details->negative_marking_per_question)?$question_paper_details->negative_marking_per_question:1;

        $insertData = [];
        $insertData['total_mark'] = 0;
        $insertData['total_answer'] = 0;
        $insertData['total_right_answer'] = 0;
        $insertData['total_number'] = 0;
        // $insertData['total_time'] = 0;
        $insertData['question_number'] = 0;
        foreach($exam_question as $key=>$value){
            $result = 0;
            $exam_result = Exam_result::where('user_id',$user_id)->where('exam_user_id',$exam_user_id)->where('exam_question_id',$value->question_paper_question_id)->first();
            // dd($value);
            if($exam_result){
                // $insertData['total_time'] = $insertData['total_time'] + $exam_result->time;
                if($exam_result->answer){
                    $insertData['total_answer'] = $insertData['total_answer'] + 1;
                    if($value->answer == $exam_result->answer){
                        $insertData['total_right_answer'] = $insertData['total_right_answer'] + 1;
                        $result = $marks_per_question;
                    }else{
                        if($negative_marking_applicable){
                            $result = -($negative_marking_per_question);
                        }
                    }
                }
                $exam_result->update(['result'=>$result]);
            }
            
        }

        if($negative_marking_applicable == 1){
            $total_worng_answer = ($insertData['total_answer'] - $insertData['total_right_answer']);
            $total_worng_answer = $total_worng_answer * $negative_marking_per_question;
            $insertData['total_number'] = ($marks_per_question * $insertData['total_right_answer']) - $total_worng_answer;
            $insertData['percentage'] = ($insertData['total_number']) * 100 / $totals_marks_for_exam;
        }else{
            $insertData['total_number'] = ($marks_per_question * $insertData['total_right_answer']);
            $insertData['percentage'] = ($insertData['total_number']) * 100 / $totals_marks_for_exam;
        }
        $insertData['total_mark'] = $totals_marks_for_exam;

        if ($request->proctoring_cancelled) {
            $insertData['proctoring_status'] = 'cancelled';
        } elseif ($request->proctoring_auto_submit) {
            $insertData['proctoring_status'] = 'auto_submitted';
        } elseif (!empty($user_exam_detail->proctoring_status)) {
            $insertData['proctoring_status'] = 'completed';
        }
        $user_exam_detail->update($insertData);
        echo 1; exit;
    }

    public function log_proctoring_event(Request $request){
        $user_id = Auth::user()->id;
        $exam_user = Exam_user::where('id', $request->exam_user_id)->where('user_id', $user_id)->first();
        if (!$exam_user) {
            return response()->json(['ok' => 0], 403);
        }

        $allowed = ['tab_switch','fullscreen_exit','copy_attempt','paste_attempt','right_click','camera_lost','camera_covered','no_face','multiple_faces','started','warning'];
        $event_type = $request->event_type;
        if (!in_array($event_type, $allowed, true)) {
            return response()->json(['ok' => 0], 422);
        }

        Exam_proctoring_event::create([
            'exam_id' => $exam_user->exam_id,
            'exam_user_id' => $exam_user->id,
            'user_id' => $user_id,
            'event_type' => $event_type,
            'message' => substr((string)$request->message, 0, 255),
        ]);

        if (in_array($event_type, ['tab_switch', 'fullscreen_exit', 'camera_lost', 'camera_covered', 'no_face', 'multiple_faces'], true)) {
            $exam_user->update([
                'tab_switch_count' => ((int)$exam_user->tab_switch_count) + 1,
                'proctoring_status' => $exam_user->proctoring_status ?: 'started',
            ]);
        } elseif ($event_type === 'started') {
            $exam_user->update(['proctoring_status' => 'started']);
        }

        return response()->json(['ok' => 1, 'tab_switch_count' => $exam_user->tab_switch_count]);
    }

    public function save_proctoring_snapshot(Request $request){
        $user_id = Auth::user()->id;
        $exam_user = Exam_user::where('id', $request->exam_user_id)->where('user_id', $user_id)->first();
        if (!$exam_user) {
            return response()->json(['ok' => 0], 403);
        }

        $snapshotCount = Exam_proctoring_event::where('exam_user_id', $exam_user->id)
            ->where('event_type', 'snapshot')
            ->count();
        if ($snapshotCount >= 20) {
            return response()->json(['ok' => 1, 'skipped' => 1]);
        }

        $snapshot = (string)$request->snapshot;
        if (!preg_match('/^data:image\/jpeg;base64,/', $snapshot)) {
            return response()->json(['ok' => 0], 422);
        }

        $binary = base64_decode(substr($snapshot, strpos($snapshot, ',') + 1), true);
        if ($binary === false || strlen($binary) < 100 || strlen($binary) > 400000) {
            return response()->json(['ok' => 0], 422);
        }

        $filename = date('Ymd_His').'_'.uniqid().'.jpg';
        $image_path = 'proctoring/'.$exam_user->id.'/'.$filename;
        Storage::disk('local')->put($image_path, $binary);

        Exam_proctoring_event::create([
            'exam_id' => $exam_user->exam_id,
            'exam_user_id' => $exam_user->id,
            'user_id' => $user_id,
            'event_type' => 'snapshot',
            'message' => 'Webcam snapshot',
            'image_path' => $image_path,
        ]);

        return response()->json(['ok' => 1]);
    }

    private function ownedExamUser($examUserId)
    {
        if (!$examUserId) {
            return null;
        }
        return Exam_user::where('id', $examUserId)->where('user_id', Auth::id())->first();
    }

}