<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Exam_user;
use App\Models\Notice_user;
use App\Models\Exam_result;
use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Sub_topic;
use App\Models\Offline_exam_question;
use App\Models\Question_paper_question;
use App\Models\Mistake_input;
use App\Models\Exam_mistake_input;
use App\Models\Exam_type;
use App\Models\Question_type;

use DB;

class Exam_givenController extends Controller
{
    private function getLanguageId(){
        return 1;
    }

    public function downloadPdf(Request $request){
        $input = $request->all();

        dd($input);
    }

    public function leader_board(Request $request){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['exam_types'] = $request->exam_types;

        $subject_id = $request->subject_id;
        $exam_type = $request->exam_type;

        $data['subject_details'] = Subject::where('id',$request->subject_id)->first();
        $data['subject_list'] = Subject::where('status',1)->get();
        
        // $data['exam_list'] = Exam_user::select([
        //                         'exam_users.user_id',
        //                         DB::raw('SUM(exam_users.total_mark) as total_mark'),
        //                         DB::raw('SUM(exam_users.total_number) as total_result'),
        //                         'users.first_name','users.last_name','users.profile_img','users.rankpro_id',
        //                         DB::raw('count(*) as total_exam')
        //                     ])
        //                     ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
        //                     ->leftJoin('question_papers', 'exams.question_paper_id', '=', 'question_papers.id')
        //                     ->leftJoin('question_paper_questions', 'question_paper_questions.question_paper_id', '=', 'question_papers.id')
        //                     ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
        //                     ->leftJoin('offline_exam_questions', 'offline_exam_questions.exam_id', '=', 'exams.id')
        //                     ->leftJoin('users', 'users.id', '=', 'exam_users.user_id')
        //                     ->where('exams.is_deleted',0)
        //                     ->when($subject_id !== null, function ($query) use ($subject_id) {
        //                         $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
        //                     })
        //                     ->when($exam_type !== null, function ($query) use ($exam_type) {
        //                         $query->where('exams.type',$exam_type); 
        //                     })
        //                     ->groupBy('exam_users.user_id');
                            
        $data['exam_list'] = Exam_user::select([
                                'exam_users.user_id',
                                DB::raw('SUM(exam_users.total_mark) as total_mark'),
                                DB::raw('SUM(exam_users.total_number) as total_result'),
                                'users.first_name','users.last_name','users.profile_img','users.rankpro_id',
                                DB::raw('count(*) as total_exam')
                            ])
                            ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                            ->leftJoin('users', 'users.id', '=', 'exam_users.user_id')
                            ->when($subject_id !== null, function ($query) use ($subject_id) {
                                $query->where(function ($q) use ($subject_id) {
                                    // Online exams (question_paper_id NOT NULL)
                                    $q->where(function ($online) use ($subject_id) {
                                        $online->whereNotNull('exams.question_paper_id')
                                               ->whereExists(function ($sub) use ($subject_id) {
                                                   $sub->select(DB::raw(1))
                                                       ->from('question_paper_questions')
                                                       ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                                       ->whereColumn('question_paper_questions.question_paper_id', 'exams.question_paper_id')
                                                       ->where('questions.subject_id', $subject_id);
                                               });
                                    })
                                    // Offline exams (question_paper_id IS NULL)
                                    ->orWhere(function ($offline) use ($subject_id) {
                                        $offline->whereNull('exams.question_paper_id')
                                                ->whereExists(function ($sub) use ($subject_id) {
                                                    $sub->select(DB::raw(1))
                                                        ->from('offline_exam_questions')
                                                        ->whereColumn('offline_exam_questions.exam_id', 'exams.id')
                                                        ->where('offline_exam_questions.subject_id', $subject_id);
                                                });
                                    });
                                });
                            })
                            ->when($exam_type !== null, function ($query) use ($exam_type) {
                                $query->where('exams.type',$exam_type); 
                            })
                            ->where('exams.is_deleted',0)
                            ->groupBy('exam_users.user_id');

        if($data['exam_types']){
            if($data['exam_types'] == 1){
                $data['exam_list'] = $data['exam_list']->where('exams.type',2)->where('exam_users.exam_type',"RNS");
            }else if($data['exam_types'] == 2){
                $data['exam_list'] = $data['exam_list']->where('exams.type',2)->where('exam_users.exam_type',"RPS");
            }else if($data['exam_types'] == 3){
                $data['exam_list'] = $data['exam_list']->where('exams.type',2)->where('exam_users.exam_type',"SNT");
            }else if($data['exam_types'] == 4){
                $data['exam_list'] = $data['exam_list']->where('exams.type',1);
            }
        }

        $data['exam_list'] = $data['exam_list']->orderBy('total_result','desc')->get();
        
        // dd($data['exam_list']);
        
        foreach($data['exam_list'] as $key => $value){
            $subjectList = [];

            foreach ($data['subject_list'] as $key1 => $value1) {

                $subjectList[$value1->id] = Exam_result::select(['exam_results.id'])
                    ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                    ->whereRaw(
                        'COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?',
                        [$value1->id]
                    )
                    ->where('exams.is_deleted',0)
                    ->where('exam_results.result','>', 0)
                    ->where('exam_results.user_id', $value->user_id)
                    ->count();
            }

            // Now assign once
            // dd($subjectList);
            $value->subject_list = $subjectList;
        }
        return view('site.leader_board',$data);
    }

    public function exam_given(Request $request){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['subject_details'] = Subject::where('id',$request->subject_id)->first();
        $data['subject_list'] = Subject::where('status',1)->get();

        $subject_id = $request->subject_id;
        $exam_type = $request->exam_type;

        $data['exam_list'] = Exam_user::select(['exams.*','exam_users.id as user_exam_id','exam_users.total_answer','exam_users.total_right_answer','exam_users.total_number','exam_users.rank','exam_users.created_at','exam_users.total_mark'])
                                ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                                ->leftJoin('question_papers', 'exams.question_paper_id', '=', 'question_papers.id')
                                ->leftJoin('question_paper_questions', 'question_paper_questions.question_paper_id', '=', 'question_papers.id')
                                ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                ->leftJoin('offline_exam_questions', 'offline_exam_questions.exam_id', '=', 'exams.id')
                                ->where('exams.is_deleted',0)
                                ->where('exam_users.user_id',Auth::user()->id)
                                ->when($subject_id !== null, function ($query) use ($subject_id) {
                                    $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                                })
                                ->when($exam_type !== null, function ($query) use ($exam_type) {
                                    $query->where('exams.type',$exam_type); 
                                })->distinct()->orderBy('exams.exam_date','DESC')->get();
        // dd($data['exam_list']);
        return view('site.exam_given',$data);
    }

    public function exam_result_detail(Request $request){
        $language_id = $this->getLanguageId();
        $exam_user_id = $request->id;
        $user_id = Auth::user()->id;
        $data = [];
        $data['offline_exam'] = Exam_user::select(['exams.*','exam_users.id as user_exam_id','exam_users.total_answer','exam_users.total_right_answer','exam_users.total_number','exam_users.proctoring_status'])
                                ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                                ->where('exam_users.id',$exam_user_id)->first();
        if($data['offline_exam']->question_paper_id){
            $data['exam_question'] = Question_paper_question::select(['question_paper_questions.*','questions.answer','questions.solution_video_link as video_link'])
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->where('question_paper_id',$data['offline_exam']->question_paper_id)->orderBy('question_paper_questions.id','ASC')->get();
        }else{
            $data['exam_question'] = Offline_exam_question::select(['offline_exam_questions.*'])
                ->where('offline_exam_questions.exam_id',$data['offline_exam']->id)->get();
        }
            

        $data['exam_id'] = str_pad($data['offline_exam']->exam_code, 3, '0', STR_PAD_LEFT);

        $data['user_id'] = str_pad($user_id, 8, '0', STR_PAD_LEFT);
        $data['answer_list1'] = [];
        $data['answer_list2'] = [];
        $data['answer_list3'] = [];
        $data['answer_list4'] = [];
        // dd(count($data['exam_question']));
        $total_qus_count = (int) (count($data['exam_question']) / 4);
        $question_count = 0;
        foreach ($data['exam_question'] as $key => $value) {
            $exam_result = Exam_result::where('user_id',$user_id)->where('exam_id',$data['offline_exam']->id)->where('exam_question_id',$value->id)->first();
            // $question_detail = Question_detail::where('question_id',$value->question_id)->where('language_id',1)->first();
            // dd($value);
            $value->user_answer = "";
            $result = "";
            $value->class_name1 = "";
            $value->class_name2 = "";
            $value->class_name3 = "";
            $value->class_name4 = "";

            if($exam_result){
                if($exam_result->answer){
                    $value->user_answer = $exam_result->answer;
                    if(!empty($exam_result->answer)){
                        $result = "0";
                        if($exam_result->answer == $value->answer){
                            $result = "1";
                        }
                    }
                }
                $value->exam_result_id = $exam_result->id;
            }else{
                $value->exam_result_id = "";
            }

            if($result == "1"){
                if($value->answer == 1){
                    $value->class_name1 = "candidateId_fill_green";
                }
                if($value->answer == 2){
                    $value->class_name2 = "candidateId_fill_green";
                }
                if($value->answer == 3){
                    $value->class_name3 = "candidateId_fill_green";
                }
                if($value->answer == 4){
                    $value->class_name4 = "candidateId_fill_green";
                }
            }else if($result == "0"){
                if($value->answer == 1){
                    $value->class_name1 = "candidateId_fill_green";
                }
                if($value->answer == 2){
                    $value->class_name2 = "candidateId_fill_green";
                }
                if($value->answer == 3){
                    $value->class_name3 = "candidateId_fill_green";
                }
                if($value->answer == 4){
                    $value->class_name4 = "candidateId_fill_green";
                }
                if($exam_result->answer == 1){
                    $value->class_name1 = "candidateId_fill_red";
                }
                if($exam_result->answer == 2){
                    $value->class_name2 = "candidateId_fill_red";
                }
                if($exam_result->answer == 3){
                    $value->class_name3 = "candidateId_fill_red";
                }
                if($exam_result->answer == 4){
                    $value->class_name4 = "candidateId_fill_red";
                }
            }else{
                if($value->answer == 1){
                    $value->class_name1 = "candidateId_fill_blue";
                }
                if($value->answer == 2){
                    $value->class_name2 = "candidateId_fill_blue";
                }
                if($value->answer == 3){
                    $value->class_name3 = "candidateId_fill_blue";
                }
                if($value->answer == 4){
                    $value->class_name4 = "candidateId_fill_blue";
                }
            }

            $question_count = $question_count + 1;
            $value->question_count = $question_count;
            $value->result = $result;
            

            if(count($data['answer_list1']) < $total_qus_count){
                array_push($data['answer_list1'], $value);
            }else if(count($data['answer_list2']) < $total_qus_count){
                array_push($data['answer_list2'], $value);
            }else if(count($data['answer_list3']) < $total_qus_count){
                array_push($data['answer_list3'], $value);
            }else{
                array_push($data['answer_list4'], $value);
            }
        }
        
        // dd($data['answer_list4'][0]);
        return view('site.exam_result_detail',$data);
    }

    public function mistake_monitor_input(Request $request){
        $language_id = $this->getLanguageId();
        $exam_result_id = $request->id;
        $user_exam_id = $request->user_exam_id;
        $user_id = Auth::user()->id;
        $data = [];
        $data['exam_result_id'] = $exam_result_id;
        $data['user_exam_id'] = $user_exam_id;
        $data['user'] = Auth::user();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['exam_types'] = $request->exam_types;
        $data['subject_details'] = Subject::where('id',$request->subject_id)->first();
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['mistake_input_list'] = Mistake_input::where('status',1)->get();
        
        $subject_id = $request->subject_id;

        $data['exam_user'] = Exam_user::select(['exams.*','exam_users.id as user_exam_id','exam_users.total_answer','exam_users.total_right_answer','exam_users.total_number'])
                                ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                                ->where('exam_users.id',$user_exam_id)->first();
        // dd($data['exam_user']);
        if($data['exam_user']->question_paper_id){
            $data['question_list'] = Exam_result::select(['exam_results.*','question_details.question_id','question_details.question_text','question_details.option1','question_details.option2','question_details.option3','question_details.option4','question_details.is_option1_image','question_details.is_option2_image','question_details.is_option3_image','question_details.is_option4_image','questions.solution_video_link as video_link','questions.difficulty_level','question_paper_questions.question_number','exam_mistake_inputs.mistake_input_id'])
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('question_details', 'questions.id', '=', 'question_details.question_id')
                                    ->leftJoin('exam_mistake_inputs', function ($join) {
                                        $join->on('exam_mistake_inputs.exam_user_id', '=', 'exam_results.exam_user_id')
                                            ->on('exam_mistake_inputs.question_id', '=', 'exam_results.id')
                                            ->on('exam_mistake_inputs.exam_id', '=', 'exam_results.exam_id')
                                            ->on('exam_mistake_inputs.user_id', '=', 'exam_results.user_id');
                                    })
                                    ->when($subject_id !== null, function ($query) use ($subject_id) {
                                        $query->where('questions.subject_id', $subject_id); 
                                    })
                                    ->where('exam_results.exam_user_id',$user_exam_id)->orderBy('question_paper_questions.id','ASC')
                                    ->where('exam_results.result','<',1)
                                    ->where('exam_results.answer','>',0)
                                    ->orderBy('question_paper_questions.question_number','ASC')->get();
        }else{
            $data['question_list'] = Exam_result::select(['exam_results.*','offline_exam_questions.question_text','offline_exam_questions.video_link','offline_exam_questions.difficulty_level','offline_exam_questions.question_number','exam_mistake_inputs.mistake_input_id'])
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('exam_mistake_inputs', function ($join) {
                                        $join->on('exam_mistake_inputs.exam_user_id', '=', 'exam_results.exam_user_id')
                                            ->on('exam_mistake_inputs.question_id', '=', 'exam_results.id')
                                            ->on('exam_mistake_inputs.exam_id', '=', 'exam_results.exam_id')
                                            ->on('exam_mistake_inputs.user_id', '=', 'exam_results.user_id');
                                    })
                                    ->when($subject_id !== null, function ($query) use ($subject_id) {
                                        $query->where('offline_exam_questions.subject_id', $subject_id); 
                                    })
                                    ->where('exam_results.exam_user_id',$user_exam_id)
                                    ->where('exam_results.result','<',1)
                                    ->where('exam_results.answer','>',0)
                                    ->orderBy('offline_exam_questions.question_number','ASC')->get();
        }

        foreach ($data['question_list'] as $key => $value) {
            $value->student_attempted = Exam_result::where('exam_question_id',$value->exam_question_id)->where('exam_results.exam_id',$value->exam_id)->count();
            $value->attempted_correct = Exam_result::where('exam_question_id',$value->exam_question_id)->where('exam_results.exam_id',$value->exam_id)->where('result','>=',1)->count();
            $value->time_spent_by_you = "";
            $value->average_time_spent = "";
            $value->time_spent_by_topper = "";
        }
        // dd($data['question_list'][0]);
        return view('site.mistake_monitor_input',$data);
    }

    public function mistake_monitor_input_reason(Request $request){
        $data = [];
        $data['question_id'] = $request->question_id;
        $data['mistake_input_id'] = $request->mistake_input_id;
        $data['exam_user_id'] = $request->user_exam_id;
        $data['exam_id'] = $request->exam_id;
        $data['user_id'] = Auth::user()->id;

        $exam_mistake_input = Exam_mistake_input::where('question_id',$data['question_id'])->where('exam_user_id',$data['exam_user_id'])->where('user_id',$data['user_id'])->first();
        if($exam_mistake_input){
            // Exam_mistake_input
            $exam_mistake_input->update($data);
        }else{
            Exam_mistake_input::create($data);
        }
    }

    public function mistake_monitor_output(Request $request){
        
        dd("Check link");
        return view('site.mistake_monitor_output',$data);
    }

    public function getToperResultList($type,$exam_type,$subject_id,$exam_id){
        $return = Exam_result::select([
                        'exam_results.user_id',
                        DB::raw('count(*) as total_question'),
                        DB::raw('COUNT(CASE WHEN (exam_results.result > 0) THEN 1 END) as right_answer'),
                        DB::raw('COUNT(CASE WHEN (exam_results.result = 0 AND exam_results.answer = 0) THEN 1 END) as skip_answer'),
                        DB::raw('COUNT(CASE WHEN ((exam_results.result < 1) AND exam_results.answer != 0) THEN 1 END) as wrong_answer'),
                        DB::raw('SUM(result) as total_mark'),
                        DB::raw('COALESCE(exams.totals_marks_for_exam, question_papers.totals_marks_for_exam) as totals_marks_for_exam')
                    ])
                        ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
                        ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                        ->leftJoin('question_papers', 'question_papers.id', '=', 'question_paper_questions.question_paper_id')
                        ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                        ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                        ->where('exam_results.exam_id', $exam_id)
                        ->when($subject_id !== null, function ($query) use ($subject_id) {
                            $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                        });
        if($type){
            $return = $return->whereRaw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) = ?', [$type]);
        }

        $return = $return->groupBy('exam_results.user_id')
                                ->orderBy('total_mark','DESC')
                                ->first();

        // dd($return);
        if ($return) {
            if($return->total_question){

            }
        }
        return $return;
    }

    public function getAvgResultList($type,$exam_type,$subject_id,$exam_user_id){
        $sub = Exam_result::select([
                        DB::raw('count(*) as total_question'),
                        DB::raw('COUNT(CASE WHEN (exam_results.result != 0 AND exam_results.result != -1) THEN 1 END) as right_answer'),
                        DB::raw('COUNT(CASE WHEN (exam_results.result = 0 AND exam_results.answer = 0) THEN 1 END) as skip_answer'),
                        DB::raw('COUNT(CASE WHEN ((exam_results.result = 0 OR exam_results.result = -1) AND exam_results.answer != 0) THEN 1 END) as wrong_answer'),
                        DB::raw('SUM(result) as total_mark'),
                        DB::raw('COALESCE(exams.totals_marks_for_exam, question_papers.totals_marks_for_exam) as totals_marks_for_exam')
                    ])
                ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
                ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                ->leftJoin('question_papers', 'question_papers.id', '=', 'question_paper_questions.question_paper_id')
                ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                ->when($subject_id !== null, function ($query) use ($subject_id) {
                    $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                })
                ->where('exam_results.exam_id', $exam_user_id);
        if($type){
            $sub = $sub->whereRaw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) = ?', [$type]);
        }

        if($exam_type){
            if($exam_type == 1){
                $sub = $sub->where('exams.type',1);
            }else if($exam_type == 2){
                $sub = $sub->where('exams.type',2);
            }
        }

        $sub = $sub->groupBy('exam_results.user_id');

        $return = DB::query()
            ->fromSub($sub, 'user_stats')
            ->select([
                DB::raw('AVG(total_question) as total_question'),
                DB::raw('AVG(right_answer) as right_answer'),
                DB::raw('AVG(skip_answer) as skip_answer'),
                DB::raw('AVG(wrong_answer) as wrong_answer'),
                DB::raw('AVG(totals_marks_for_exam) as totals_marks_for_exam'),
                DB::raw('AVG(total_mark) as total_mark')
            ])
            ->first();
        // dd($return->total_question);

        return $return;
    }

    public function getYourResultList($type,$exam_type,$subject_id,$exam_user_id){
        $return = Exam_result::select([
                        DB::raw('count(*) as total_question'),
                        DB::raw('COUNT(CASE WHEN (exam_results.result != 0 AND exam_results.result != -1) THEN 1 END) as right_answer'),
                        DB::raw('COUNT(CASE WHEN (exam_results.result = 0 AND exam_results.answer = 0) THEN 1 END) as skip_answer'),
                        DB::raw('COUNT(CASE WHEN ((exam_results.result = 0 OR exam_results.result = -1) AND exam_results.answer != 0) THEN 1 END) as wrong_answer'),
                        DB::raw('SUM(result) as total_mark'),
                        DB::raw('COALESCE(exams.totals_marks_for_exam, question_papers.totals_marks_for_exam) as totals_marks_for_exam')
                    ])
                        ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
                        ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                        ->leftJoin('question_papers', 'question_papers.id', '=', 'question_paper_questions.question_paper_id')
                        ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                        ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                        ->where('exam_results.user_id', Auth::id())
                        ->when($subject_id !== null, function ($query) use ($subject_id) {
                            $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                        })
                        ->where('exam_results.exam_user_id', $exam_user_id);
        if($type){
            $return = $return->whereRaw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) = ?', [$type]);
        }

        if($exam_type){
            if($exam_type == 1){
                $return = $return->where('exams.type',1);
            }else if($exam_type == 2){
                $return = $return->where('exams.type',2);
            }
        }

        $return = $return->groupBy('exam_results.user_id')
                                ->orderBy('total_mark','DESC')
                                ->first();
        return $return;
    }

    public function answers_analytics(Request $request){
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_id'] = $request->exam_id;
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['subject_details'] = Subject::where('id',$request->subject_id)->first();
        $data['subject_list'] = Subject::where('status',1)->get();

        $data['exam_user'] = Exam_user::where('id',$data['exam_id'])->first();
        
        $data['topper_data'] = $this->getToperResultList('',$data['exam_type'],$data['subject_id'],$data['exam_user']->exam_id);
        $data['average_data'] = $this->getAvgResultList('',$data['exam_type'],$data['subject_id'],$data['exam_user']->exam_id);
        $data['you_data'] = $this->getYourResultList('',$data['exam_type'],$data['subject_id'],$data['exam_id']);
        // dd($data);

        $data['easy_toper_list'] = $this->getToperResultList(1,$data['exam_type'],$data['subject_id'],$data['exam_user']->exam_id);
        $data['avg_easy_toper_list'] = $this->getAvgResultList(1,$data['exam_type'],$data['subject_id'],$data['exam_user']->exam_id);
        $data['my_easy_toper_list'] = $this->getYourResultList(1,$data['exam_type'],$data['subject_id'],$data['exam_id']);

        
        $data['medium_toper_list'] = $this->getToperResultList(2,$data['exam_type'],$data['subject_id'],$data['exam_user']->exam_id);
        $data['avg_medium_toper_list'] = $this->getAvgResultList(2,$data['exam_type'],$data['subject_id'],$data['exam_user']->exam_id);
        $data['my_medium_toper_list'] = $this->getYourResultList(2,$data['exam_type'],$data['subject_id'],$data['exam_id']);

        
        $data['hard_toper_list'] = $this->getToperResultList(3,$data['exam_type'],$data['subject_id'],$data['exam_user']->exam_id);
        $data['avg_hard_toper_list'] = $this->getAvgResultList(3,$data['exam_type'],$data['subject_id'],$data['exam_user']->exam_id);
        $data['my_hard_toper_list'] = $this->getYourResultList(3,$data['exam_type'],$data['subject_id'],$data['exam_id']);
        // dd($data);
        $data['toper_score'] = 1;

        $userRank = DB::select("
                                SELECT COUNT(*) + 1 AS rank
                                FROM (
                                    SELECT exam_users.user_id, SUM(exam_users.total_number) as total_number
                                    FROM exam_users
                                    LEFT JOIN exams ON exams.id = exam_users.exam_id
                                    WHERE exams.is_deleted = 0 AND
                                    exam_users.exam_id = ? 
                                    GROUP BY exam_users.user_id
                                ) as totals
                                WHERE total_number > (
                                    SELECT SUM(exam_users.total_number)
                                    FROM exam_users
                                    LEFT JOIN exams ON exams.id = exam_users.exam_id
                                    WHERE exams.is_deleted = 0 AND
                                    exam_users.exam_id = ?
                                    AND exam_users.user_id = ?
                                )
                            ", [
                                    $data['exam_user']->exam_id,
                                    $data['exam_user']->exam_id,
                                    Auth::id()
                                ]);

        $data['your_score'] = $userRank[0]->rank ?? 1;
        $data['total_user'] = Exam_user::select(['exam_users.total_mark'])
                        ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                        ->where('exams.is_deleted',0)
                        ->where('exam_users.exam_id', $data['exam_user']->exam_id)->count();

        return view('site.exam_answers_analytics',$data);
    }

    public function strength(Request $request){
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_type'] = $request->exam_type;
        $data['exam_id'] = $request->exam_id;
        $data['subject_id'] = $request->subject_id;
        $data['subject_details'] = Subject::where('id',$request->subject_id)->first();
        $data['subject_list'] = Subject::where('status',1)->get();
        $subject_id = $request->subject_id;
        $data['chapter_list'] = Exam_result::select([
                                        DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) as chapter_id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->where('exam_results.result', '>=',1)
                                    ->whereRaw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) IS NOT NULL')
                                    ->when($subject_id !== null, function ($query) use ($subject_id) {
                                        $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                                    })
                                    ->where('exam_results.exam_user_id', $data['exam_id'])
                                    ->groupBy(DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id)'))
                                    ->orderByDesc('total_result')
                                    ->get();

        foreach($data['chapter_list'] as $key => $value){
            $chapter_detail = Chapter::where('id',$value->chapter_id)->first();
            $data['chapter_list'][$key]->chapter_name = ($chapter_detail)?$chapter_detail->name:'';
        }
        $exam_id = $data['exam_id'];
        $total_count = Exam_result::where('exam_results.user_id', Auth::id())->where('exam_results.exam_user_id', $data['exam_id'])->count();
        
        $data['silly_percentage'] = $this->progressReportAnswerType(1,3,$exam_id,$data['subject_id'],$total_count);
        $data['wrong_percentage'] = $this->progressReportAnswerType(1,2,$exam_id,$data['subject_id'],$total_count);
        $data['irrelevant_percentage'] = $this->progressReportAnswerType(1,4,$exam_id,$data['subject_id'],$total_count);
        
        $data['easy_details'] = $this->progressReportQuestionLevel(1,1,$exam_id,$data['subject_id']);
        $data['medium_details'] = $this->progressReportQuestionLevel(1,2,$exam_id,$data['subject_id']);
        $data['hard_details'] = $this->progressReportQuestionLevel(1,3,$exam_id,$data['subject_id']);

        $data['question_type'] = Question_type::select(['id','name'])->where('status',1)->get();

        foreach($data['question_type'] as $key => $value){
            $data['question_type'][$key]->data = Exam_result::select([
                                        DB::raw('count(*) as total_question'),
                                        DB::raw('COUNT(CASE WHEN (exam_results.result != 0 AND exam_results.result != -1) THEN 1 END) as right_answer'),
                                        DB::raw('COUNT(CASE WHEN (exam_results.result = 0 AND exam_results.answer = 0) THEN 1 END) as skip_answer'),
                                        DB::raw('COUNT(CASE WHEN ((exam_results.result = 0 OR exam_results.result = -1) AND exam_results.answer != 0) THEN 1 END) as wrong_answer'),
                                        DB::raw('SUM(result) as total_mark'),
                                        DB::raw('COALESCE(exams.totals_marks_for_exam, question_papers.totals_marks_for_exam) as totals_marks_for_exam')
                                    ])
                                    ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('question_papers', 'question_papers.id', '=', 'question_paper_questions.question_paper_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->when($subject_id !== null, function ($query) use ($subject_id) {
                                        $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                                    })
                                    ->whereRaw('COALESCE(questions.question_type_id, offline_exam_questions.question_type_id) = ?', [$value->id])
                                    ->groupBy(DB::raw('COALESCE(questions.question_type_id, offline_exam_questions.question_type_id)'))
                                    ->first();
        }
        // dd($data['chapter_list']);
        return view('site.exam_strength',$data);
    }

    public function strength_topics(Request $request){
        $chapter_id = $request->chapter_id;
        $data = [];
        $data['list'] = Exam_result::select([
                                        DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id) as topic_id'),
                                        'topics.name as topic_name',
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('topics', function ($join) {
                                        $join->on('topics.id', '=', DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id)'));
                                    })
                                    ->where('exam_results.user_id', Auth::id())
                                    ->where('exam_results.result', '>=',1)
                                    ->whereNotNull('topics.name')
                                    ->whereRaw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) = ?', [$chapter_id])
                                    ->groupBy(DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id)'))
                                    ->orderBy('total_result', 'asc')
                                    ->get();
        echo json_encode($data);
    }

    public function strength_subtopics(Request $request){
        $chapter_id = $request->chapter_id;
        $topic_id = $request->topic_id;
        $data = [];
        $data['list'] = Exam_result::select([
                                        DB::raw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id) as sub_topic_id'),
                                        'sub_topics.name as sub_topic_name',
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('sub_topics', function ($join) {
                                        $join->on('sub_topics.id', '=', DB::raw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id)'));
                                    })
                                    ->where('exam_results.user_id', Auth::id())
                                    ->where('exam_results.result', '>=',1)
                                    ->whereNotNull('sub_topics.name')
                                    ->whereRaw('COALESCE(questions.topic_id, offline_exam_questions.topic_id) = ?', [$topic_id])
                                    ->groupBy(DB::raw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id)'))
                                    ->orderBy('total_result', 'asc')
                                    ->get();

        foreach($data['list'] as $key => $value){
            $topic_detail = Sub_topic::where('id',$value->sub_topic_id)->first();
            $data['list'][$key]->sub_topic_name = ($topic_detail)?$topic_detail->name:'';
        }
        echo json_encode($data);
    }

    public function strength_question(Request $request){
        $chapter_id = $request->chapter_id;
        $topic_id = $request->topic_id;
        $sub_topic_id = $request->sub_topic_id;
        $data = [];
        $data['list'] = Exam_result::select([
                                        DB::raw('COALESCE(question_details.question_text, offline_exam_questions.question_text) as question_text'),
                                        DB::raw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) as difficulty_level'),
                                        DB::raw('COALESCE(questions.id, offline_exam_questions.sub_topic_id) as id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('question_details', 'questions.id', '=', 'question_details.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->where('exam_results.result', '>=',1)
                                    ->whereRaw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id) = ?', [$sub_topic_id])
                                    ->groupBy(DB::raw('COALESCE(questions.id, offline_exam_questions.id)'))
                                    ->orderBy('total_result', 'asc')
                                    ->get();

        foreach($data['list'] as $key => $value){
            $topic_detail = Sub_topic::where('id',$value->sub_topic_id)->first();
            $data['list'][$key]->sub_topic_name = ($topic_detail)?$topic_detail->name:'';
        }
        echo json_encode($data);
    }

    public function weakness(Request $request){
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_id'] = $request->exam_id;
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['subject_details'] = Subject::where('id',$request->subject_id)->first();
        $data['subject_list'] = Subject::where('status',1)->get();
        $subject_id = $request->subject_id;
        $data['chapter_list'] = Exam_result::select([
                                        DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) as chapter_id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->where('exam_results.result', '<',1)
                                    ->whereRaw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) IS NOT NULL')
                                    ->where('exam_results.exam_user_id', $data['exam_id'])
                                    ->when($subject_id !== null, function ($query) use ($subject_id) {
                                        $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                                    })
                                    ->groupBy(DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id)'))
                                    ->orderBy('total_result', 'asc')
                                    ->get();
        // dd($data['chapter_list']);
        foreach($data['chapter_list'] as $key => $value){
            $chapter_detail = Chapter::where('id',$value->chapter_id)->first();
            $data['chapter_list'][$key]->chapter_name = ($chapter_detail)?$chapter_detail->name:'';
        }
        $exam_id = $data['exam_id'];
        $total_count = Exam_result::where('exam_results.user_id', Auth::id())->where('exam_results.exam_user_id', $data['exam_id'])->count();
        
        $data['silly_percentage'] = $this->progressReportAnswerType(2,3,$exam_id,$data['subject_id'],$total_count);
        $data['wrong_percentage'] = $this->progressReportAnswerType(2,2,$exam_id,$data['subject_id'],$total_count);
        $data['irrelevant_percentage'] = $this->progressReportAnswerType(2,4,$exam_id,$data['subject_id'],$total_count);
        
        $data['easy_details'] = $this->progressReportQuestionLevel(2,1,$exam_id,$data['subject_id']);
        $data['medium_details'] = $this->progressReportQuestionLevel(2,2,$exam_id,$data['subject_id']);
        $data['hard_details'] = $this->progressReportQuestionLevel(2,3,$exam_id,$data['subject_id']);

        $data['question_type'] = Question_type::select(['id','name'])->where('status',1)->get();

        foreach($data['question_type'] as $key => $value){
            $data['question_type'][$key]->data = Exam_result::select([
                                        DB::raw('count(*) as total_question'),
                                        DB::raw('COUNT(CASE WHEN (exam_results.result != 0 AND exam_results.result != -1) THEN 1 END) as right_answer'),
                                        DB::raw('COUNT(CASE WHEN (exam_results.result = 0 AND exam_results.answer = 0) THEN 1 END) as skip_answer'),
                                        DB::raw('COUNT(CASE WHEN ((exam_results.result = 0 OR exam_results.result = -1) AND exam_results.answer != 0) THEN 1 END) as wrong_answer'),
                                        DB::raw('SUM(result) as total_mark'),
                                        DB::raw('COALESCE(exams.totals_marks_for_exam, question_papers.totals_marks_for_exam) as totals_marks_for_exam')
                                    ])
                                    ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('question_papers', 'question_papers.id', '=', 'question_paper_questions.question_paper_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->when($subject_id !== null, function ($query) use ($subject_id) {
                                        $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                                    })
                                    ->whereRaw('COALESCE(questions.question_type_id, offline_exam_questions.question_type_id) = ?', [$value->id])
                                    ->groupBy(DB::raw('COALESCE(questions.question_type_id, offline_exam_questions.question_type_id)'))
                                    ->first();
        }

        return view('site.exam_weakness',$data);
    }

    public function weakness_topics(Request $request){
        $chapter_id = $request->chapter_id;
        $data = [];
        $data['list'] = Exam_result::select([
                                        DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id) as topic_id'),
                                        'topics.name as topic_name',
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('topics', function ($join) {
                                        $join->on('topics.id', '=', DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id)'));
                                    })
                                    ->where('exam_results.user_id', Auth::id())
                                    ->where('exam_results.result', '<',1)
                                    ->whereNotNull('topics.name')
                                    ->whereRaw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) = ?', [$chapter_id])
                                    ->groupBy(DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id)'))
                                    ->orderBy('total_result', 'asc')
                                    ->get();

        foreach($data['list'] as $key => $value){
            $topic_detail = Topic::where('id',$value->topic_id)->first();
            $data['list'][$key]->topic_name = ($topic_detail)?$topic_detail->name:'';
        }
        echo json_encode($data);
    }

    public function weakness_subtopics(Request $request){
        $chapter_id = $request->chapter_id;
        $topic_id = $request->topic_id;
        $data = [];
        $data['list'] = Exam_result::select([
                                        DB::raw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id) as sub_topic_id'),
                                        'sub_topics.name as sub_topic_name',
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('sub_topics', function ($join) {
                                        $join->on('sub_topics.id', '=', DB::raw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id)'));
                                    })
                                    ->where('exam_results.user_id', Auth::id())
                                    ->where('exam_results.result', '<',1)
                                    ->whereNotNull('sub_topics.name')
                                    ->whereRaw('COALESCE(questions.topic_id, offline_exam_questions.topic_id) = ?', [$topic_id])
                                    ->groupBy(DB::raw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id)'))
                                    ->orderBy('total_result', 'asc')
                                    ->get();

        echo json_encode($data);
    }

    public function weakness_question(Request $request){
        $chapter_id = $request->chapter_id;
        $topic_id = $request->topic_id;
        $sub_topic_id = $request->sub_topic_id;
        $data = [];
        $data['list'] = Exam_result::select([
                                        DB::raw('COALESCE(question_details.question_text, offline_exam_questions.question_text) as question_text'),
                                        DB::raw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) as difficulty_level'),
                                        DB::raw('COALESCE(questions.id, offline_exam_questions.sub_topic_id) as id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('question_details', 'questions.id', '=', 'question_details.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->where('exam_results.result', '<',1)
                                    ->whereRaw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id) = ?', [$sub_topic_id])
                                    ->groupBy(DB::raw('COALESCE(questions.id, offline_exam_questions.id)'))
                                    ->orderBy('total_result', 'asc')
                                    ->get();

        foreach($data['list'] as $key => $value){
            $topic_detail = Sub_topic::where('id',$value->sub_topic_id)->first();
            $data['list'][$key]->sub_topic_name = ($topic_detail)?$topic_detail->name:'';
        }
        echo json_encode($data);
    }

    public function progressReportQuestionLevel($question_type,$type,$exam_id,$subject_id){
        if($question_type == 1){
            $details = Exam_result::select([DB::raw('count(*) as total'),DB::raw('COUNT(CASE WHEN (exam_results.result != 0 AND exam_results.result != -1) THEN 1 END) as result')]);
        }else if($question_type == 2){
            $details = Exam_result::select([DB::raw('count(*) as total'),DB::raw('COUNT(CASE WHEN (exam_results.result = 0 OR exam_results.result = -1) THEN 1 END) as result')]);
        }else{
            $details = Exam_result::select([DB::raw('count(*) as total'),DB::raw('COUNT(CASE WHEN (exam_results.result != 0 AND exam_results.result != -1) THEN 1 END) as result')]);
        }
        $details = $details->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                ->whereRaw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) = ?', [$type])
                                ->when($subject_id !== null, function ($query) use ($subject_id) {
                                        $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                                    })
                                ->where('exam_results.user_id', Auth::id())
                                ->where('exam_results.exam_user_id', $exam_id)
                                ->first();
        $return = [
            "total"=>0,
            "result"=>0,
            "percentage"=>0
        ];
        if($details){
            if($details->total && $details->result){
                $return['result'] = $details->result;
                $return['total'] = $details->total;
                $return['percentage'] = $details->result/$details->total*100;
            }
        }

        return $return;
    }

    public function progressReportAnswerType($question_type,$type,$exam_id,$subject_id,$total_count){

        if($question_type == 1){
            $details = Exam_result::select([DB::raw('count(*) as total'),DB::raw('COUNT(CASE WHEN (exam_results.result > 0) THEN 1 END) as result')]);
        }else if($question_type == 2){
            $details = Exam_result::select([DB::raw('count(*) as total'),DB::raw('COUNT(CASE WHEN (exam_results.result < 1) THEN 1 END) as result')]);         
        }else{
            $details = Exam_result::select([DB::raw('count(*) as total'),DB::raw('COUNT(CASE WHEN (exam_results.result < 1) THEN 1 END) as result')]);
        }
        $details = $details->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                        ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                        ->leftJoin('question_details', 'questions.id', '=', 'question_details.question_id')
                        ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                        ->where('exam_results.user_id', Auth::id())
                        ->when($subject_id !== null, function ($query) use ($subject_id) {
                                $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                            })
                        ->where('exam_results.exam_user_id', $exam_id)
                        ->whereRaw("
                            COALESCE(
                                CASE exam_results.answer
                                    WHEN 1 THEN question_details.answer_behavior_tag1
                                    WHEN 2 THEN question_details.answer_behavior_tag2
                                    WHEN 3 THEN question_details.answer_behavior_tag3
                                    WHEN 4 THEN question_details.answer_behavior_tag4
                                END,
                                CASE exam_results.answer
                                    WHEN 1 THEN offline_exam_questions.answer_behavior_tag1
                                    WHEN 2 THEN offline_exam_questions.answer_behavior_tag2
                                    WHEN 3 THEN offline_exam_questions.answer_behavior_tag3
                                    WHEN 4 THEN offline_exam_questions.answer_behavior_tag4
                                END
                            ) = ?
                        ", [$type])
                        ->first();
        // dd($details);
        $percentage = 0;

        if($details){
            $percentage = (int) ($details->result/$total_count*100);
        }
        return $percentage;
    }

    public function progress_report(Request $request){
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_id'] = $request->exam_id;
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['subject_details'] = Subject::where('id',$request->subject_id)->first();
        $data['subject_list'] = Subject::where('status',1)->get();

        $exam_id = $data['exam_id'];
        $data['exam_detail'] = Exam_user::where('id',$exam_id)->first();
        $total_count = Exam_result::where('exam_results.user_id', Auth::id())->where('exam_results.exam_user_id', $data['exam_id'])->count();
        
        $data['silly_percentage'] = $this->progressReportAnswerType('',3,$exam_id,$data['subject_id'],$total_count);
        $data['wrong_percentage'] = $this->progressReportAnswerType('',2,$exam_id,$data['subject_id'],$total_count);
        $data['irrelevant_percentage'] = $this->progressReportAnswerType('',4,$exam_id,$data['subject_id'],$total_count);
        
        $data['easy_details'] = $this->progressReportQuestionLevel('',1,$exam_id,$data['subject_id']);
        $data['medium_details'] = $this->progressReportQuestionLevel('',2,$exam_id,$data['subject_id']);
        $data['hard_details'] = $this->progressReportQuestionLevel('',3,$exam_id,$data['subject_id']);
        // dd($data['exam_detail']);

        return view('site.exam_progress_report',$data);
    }

    public function personal_coach(Request $request){
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_id'] = $request->exam_id;
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['subject_details'] = Subject::where('id',$request->subject_id)->first();
        $data['subject_list'] = Subject::where('status',1)->get();
        return view('site.exam_personal_coach',$data);
    }


    
}
