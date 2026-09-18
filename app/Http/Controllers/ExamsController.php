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
        

        $exam_user = Exam_user::where('exam_id',$exam_id)->where('user_id',$user_id)->first();

        // dd($exam_user);
        $exam_user_id = "";
        if($exam_user){
            $exam_user_id = $exam_user->id;
        }else{
            $exam_detail = Exam::where('id',$exam_id)->first();
            // dd($exam_detail);
            $insertData = [];
            $insertData['exam_id'] = $exam_id;
            $insertData['user_id'] = $user_id;
            $insertData['exam_type'] = "OTS";

            $exam_user = Exam_user::create($insertData);
            $exam_user_id = $exam_user->id;
        }

        toastr()->success('Exam start successfully.');
        return redirect()->route('start_online_exam',['id'=>$exam_user_id]);
        
    }

    public function start_online_exam(Request $request){
        $exam_user_id = $request->id;
        $data = [];

        $data['user_exam_id'] = $exam_user_id;
        $data['user_exam_detail'] = Exam_user::where('id',$exam_user_id)->first();
        $data['exam_detail'] = Exam::select(['exams.*','question_papers.*'])
                                ->leftJoin('question_papers', 'question_papers.id', '=', 'exams.question_paper_id')
                                ->where('exams.id',$data['user_exam_detail']->exam_id)->first();

        // dd($data['exam_detail']);

        $data['question_list'] = Question_paper_question::select(['questions.*','question_paper_questions.id as question_paper_question_id'])
                                ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                ->where('question_paper_id',$data['exam_detail']->question_paper_id)->get();
        $user_id = Auth::user()->id;

        foreach($data['question_list'] as $key=>$value){
            $exam_result = Exam_result::where('user_id',$user_id)->where('exam_user_id',$exam_user_id)->where('exam_question_id',$value->question_paper_question_id)->first();
            $question = Question_detail::where("question_id",$value->id)->where("language_id",1)->first();
            $data['question_list'][$key]->question_text = $question->question_text;
            $data['question_list'][$key]->question_image = $question->question_image;
            $data['question_list'][$key]->option1 = $question->option1;
            $data['question_list'][$key]->option2 = $question->option2;
            $data['question_list'][$key]->option3 = $question->option3;
            $data['question_list'][$key]->option4 = $question->option4;
            $data['question_list'][$key]->is_option1_image = $question->is_option1_image;
            $data['question_list'][$key]->is_option2_image = $question->is_option2_image;
            $data['question_list'][$key]->is_option3_image = $question->is_option3_image;
            $data['question_list'][$key]->is_option4_image = $question->is_option4_image;
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

        }
        // dd($data['exam_detail']);
        return view('site.start_online_exam',$data);
        
    }

    public function update_user_exam_question(Request $request){

        $input = $request->all();

        $user_id = Auth::user()->id;
        $exam_question_id = $request->question_paper_question_id;
        $question_id = $request->id;
        $answer = $request->answer;
        $time = $request->time;
        $type = $request->type;
        $exam_user_id = $request->exam_user_id;
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
        $exam_user_id = $request->exam_user_id;

        $user_exam_detail = Exam_user::where('id',$exam_user_id)->first();

        if($user_exam_detail){
            $user_exam_detail->update(['total_time'=>$user_exam_detail->total_time+5]);
        }
    }

    public function save_exam(Request $request){
        $exam_user_id = $request->exam_user_id;
        $exam_id = $request->exam_id;
        $user_id = Auth::user()->id;

        $user_exam_detail = Exam_user::where('id',$exam_user_id)->first();

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

        $user_exam_detail = Exam_user::where('id',$exam_user_id)->first();

        $exam_details = Exam::where('id',$exam_id)->first();
        $question_paper_details = Question_paper::where('id',$exam_details->question_paper_id)->first();

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

        $user_exam_detail->update($insertData);
        echo 1; exit;
    }


}