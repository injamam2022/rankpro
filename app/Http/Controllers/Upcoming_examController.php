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
use App\Models\Exam_status;
use App\Models\Question_paper_subject;
use App\Models\Offline_exam_question;

use DB;

class Upcoming_examController extends Controller
{
    private function getLanguageId(){
        return 1;
    }

    public function upcoming_exam(Request $request){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['subject_list'] = Subject::where('status',1)->get();

        $subject_id = $request->subject_id;
        $exam_type = $request->exam_type;

        $data['upcoming_exam_list'] = Exam::select(['exams.*','locations.location_name','question_papers.no_of_question','question_papers.totals_marks_for_exam','question_papers.total_time_for_exam', 'exam_statuses.type as exam_status'])
                        ->leftJoin('locations', 'exams.location_id', '=', 'locations.id')
                        ->leftJoin('question_papers', 'exams.question_paper_id', '=', 'question_papers.id')
                        ->leftJoin('question_paper_questions', 'question_paper_questions.question_paper_id', '=', 'question_papers.id')
                        ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                        ->leftJoin('offline_exam_questions', 'offline_exam_questions.exam_id', '=', 'exams.id')
                        ->leftJoin('exam_statuses', function ($join) {
                            $join->on('exams.id', '=', 'exam_statuses.exam_id')
                                 ->where('exam_statuses.user_id', Auth::user()->id);
                        })
                        ->where('exams.is_deleted',0)->where('exams.status',1)
                        ->when($subject_id !== null, function ($query) use ($subject_id) {
                            $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                        })
                        ->when($exam_type !== null, function ($query) use ($exam_type) {
                            $query->where('exams.type',$exam_type); 
                        })
                        ->where('exams.exam_date','>=',date('Y-m-d'))
                        ->distinct()->orderBy('exams.exam_date','ASC')->get();

        foreach($data['upcoming_exam_list'] as $key => $value){
            $value->subject_names = "";
            if($value->question_paper_id){
                $subject_list = Question_paper_subject::select(['subjects.name'])
                                        ->leftJoin('subjects', 'subjects.id', '=', 'question_paper_subjects.subject_id')
                                        ->where('question_paper_subjects.question_paper_id',$value->question_paper_id)
                                        ->get();

                if(count($subject_list)){
                    foreach($subject_list as $val){
                        if($value->subject_names){
                            $value->subject_names .= ", ".$val->name;
                        }else{
                            $value->subject_names = $val->name;
                        }
                    }
                }
            }else{
                $subject_list = Offline_exam_question::select(['subjects.name'])
                                        ->leftJoin('subjects', 'subjects.id', '=', 'offline_exam_questions.subject_id')
                                        ->where('offline_exam_questions.exam_id',$value->id)
                                        ->groupBy('offline_exam_questions.subject_id')
                                        ->get();

                if(count($subject_list)){
                    foreach($subject_list as $val){
                        if($value->subject_names){
                            $value->subject_names .= ", ".$val->name;
                        }else{
                            $value->subject_names = $val->name;
                        }
                    }
                }
            }
        }

        return view('site.upcoming_exam',$data);
    }

    public function upcoming_exam_accept(Request $request){
        $loginCheck = Exam::where('id',$request->id)->first();

        $user_id = Auth::user()->id;

        if($loginCheck){
            
            $exam_status = Exam_status::where('exam_id',$loginCheck->id)->where('user_id',$user_id)->first();

            if(!$exam_status){
                $insertData = [];
                $insertData['user_id'] = $user_id;
                $insertData['type'] = 1;
                $insertData['exam_id'] = $loginCheck->id;

                Exam_status::create($insertData);

                toastr()->success('Exam accepted successfully.');
            }else{
                toastr()->success('Exam already accepted.');
            }
        }

        
        return redirect()->route('upcoming_exam');
    }

    public function upcoming_exam_reject(Request $request){
        $loginCheck = Exam::where('id',$request->id)->first();

        $user_id = Auth::user()->id;

        if($loginCheck){
            
            $exam_status = Exam_status::where('exam_id',$loginCheck->id)->where('user_id',$user_id)->first();

            if(!$exam_status){
                $insertData = [];
                $insertData['user_id'] = $user_id;
                $insertData['type'] = 0;
                $insertData['exam_id'] = $loginCheck->id;

                Exam_status::create($insertData);

                toastr()->success('Exam accepted successfully.');
            }else{
                toastr()->success('Exam already accepted.');
            }
        }

        
        return redirect()->route('upcoming_exam');
    }


    
}
