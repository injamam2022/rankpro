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
use App\Models\User;
use App\Models\Subject;
use App\Models\Exam_user;
use App\Models\Notice_user;
use App\Models\Exam_mistake_input;
use App\Models\Mistake_input;
use App\Models\Chapter;
use App\Models\Exam_result;

use DB;

class ResultController extends Controller
{
    private function getLanguageId(){
        return 1;
    }

    public function trending_exam(Request $request){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['subject_list'] = Subject::where('status',1)->get();

        $subject_id = $request->subject_id;
        $exam_type = $request->exam_type;

        $data['upcoming_exam_list'] = Exam::select(['exams.*','locations.location_name','question_papers.no_of_question','question_papers.totals_marks_for_exam','question_papers.total_time_for_exam'])
                        ->leftJoin('locations', 'exams.location_id', '=', 'locations.id')
                        ->leftJoin('question_papers', 'exams.question_paper_id', '=', 'question_papers.id')
                        ->leftJoin('question_paper_questions', 'question_paper_questions.question_paper_id', '=', 'question_papers.id')
                        ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                        ->leftJoin('offline_exam_questions', 'offline_exam_questions.exam_id', '=', 'exams.id')
                        ->where('exams.is_deleted',0)->where('exams.status',1)
                        ->where('exams.exam_date','>=',date('Y-m-d'))
                        ->when($subject_id !== null, function ($query) use ($subject_id) {
                            $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                        })
                        ->when($exam_type !== null, function ($query) use ($exam_type) {
                            $query->where('exams.type',$exam_type); 
                        })->distinct()->orderBy('exams.exam_date','ASC')->get();

        return view('site.trending_exam',$data);
    }

    public function air(Request $request){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['year'] = $request->year;
        $data['subject_list'] = Subject::where('status',1)->get();        

        $data['highest_score'] = User::orderBy('total_mark','DESC')->first();
        $data['average_score'] = User::select([DB::raw('SUM(total_mark) as total_mark'),DB::raw('count(*) as total_user')])->orderBy('total_mark','DESC')->first();

        $data['my_highest_score'] = Exam_user::where('user_id',Auth::user()->id)->orderBy('total_number','DESC')->first();
        $data['my_average_score'] = Exam_user::select([DB::raw('SUM(total_number) as total_number'),DB::raw('count(*) as total_exam')])->where('user_id',Auth::user()->id)->orderBy('total_number','ASC')->first();
        $data['my_lowest_score'] = Exam_user::where('user_id',Auth::user()->id)->orderBy('total_number','ASC')->first();
        // dd($data);

        $data['my_leader_board'] = Exam_user::select(['exam_users.*',DB::raw('AVG(exam_users.percentage) as total_result'),DB::raw('SUM(exam_users.total_mark) as total_mark'),DB::raw('SUM(exam_users.total_number) as total_number'),'users.first_name','users.last_name','users.profile_img','users.rankpro_id',DB::raw('count(*) as total_exam')])
                    ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                    ->leftJoin('users', 'users.id', '=', 'exam_users.user_id');

        $data['my_leader_board'] = $data['my_leader_board']->where('exam_users.user_id', Auth::id())->where('exams.is_deleted',0)->groupBy('exam_users.user_id')->first();

        $userRank = DB::select("
                                SELECT COUNT(*) + 1 AS rank
                                FROM (
                                    SELECT exam_users.user_id, SUM(exam_users.total_number) as total_number
                                    FROM exam_users
                                    LEFT JOIN exams ON exams.id = exam_users.exam_id
                                    WHERE exams.is_deleted = 0 
                                    GROUP BY exam_users.user_id
                                ) as totals
                                WHERE total_number > (
                                    SELECT SUM(exam_users.total_number)
                                    FROM exam_users
                                    LEFT JOIN exams ON exams.id = exam_users.exam_id
                                    WHERE exams.is_deleted = 0
                                    AND exam_users.user_id = ?
                                )
                            ", [
                                    Auth::id()
                                ]);

        $data['your_score'] = $userRank[0]->rank ?? 1;

        $data['total_question'] = Exam_result::select(['exam_results.id'])
                                ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
                                ->where('exam_results.user_id',Auth::user()->id)
                                ->where('exams.is_deleted',0)
                                ->count();

        foreach($data['subject_list'] as $value){
            $exam_result = Exam_result::select([
                                DB::raw('count(*) as total'),
                                DB::raw('COUNT(CASE WHEN (exam_results.result > 0) THEN 1 END) as result'),
                                DB::raw('SUM(result) as total_mark')
                            ])
                            ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
                            ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                            ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                            ->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$value->id])
                            ->where('exam_results.user_id',Auth::user()->id)
                            ->where('exams.is_deleted',0)
                            ->first();

            $value->count = "";
            $value->score = "";

            if($exam_result){
                $value->count = $exam_result->result;
                $value->score = $exam_result->total_mark;
            }
        }

        // dd($data);

        return view('site.air',$data);
        return view('site.air',$data);
    }

    public function common_confusion(Request $request){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['year'] = $request->year;
        $data['subject_list'] = Subject::where('status',1)->get();   
        $data['mistake_input_list'] = Mistake_input::where('status',1)->get();     

        $data['question_list'] = Exam_mistake_input::select([
                                        'exam_mistake_inputs.*',
                                        DB::raw('COALESCE(questions.solution_video_link, offline_exam_questions.video_link) as video_link'),
                                        DB::raw('COALESCE(question_details.question_text, offline_exam_questions.question_text) as question_text'),
                                        DB::raw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) as difficulty_level'),
                                        'mistake_inputs.name as mistake_input_name'
                                    ])
                                    ->leftJoin('mistake_inputs', 'mistake_inputs.id', '=', 'exam_mistake_inputs.mistake_input_id')
                                    ->leftJoin('exam_results', 'exam_results.id', '=', 'exam_mistake_inputs.question_id')
                                    ->leftJoin('exam_questions', 'exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
                                    ->leftJoin('question_details', 'questions.id', '=', 'question_details.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_mistake_inputs.user_id',Auth::user()->id)->get();   

        $data['chapter_list'] = Exam_mistake_input::select([
                                        DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) as chapter_id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('exam_results', 'exam_results.id', '=', 'exam_mistake_inputs.question_id')
                                    ->leftJoin('exam_questions', 'exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->whereRaw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) IS NOT NULL')
                                    ->groupBy(DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id)'))
                                    ->orderByDesc('total_result')
                                    ->where('exam_mistake_inputs.user_id',Auth::user()->id)
                                    ->take(10)->get();

        foreach($data['chapter_list'] as $key => $value){
            $chapter_detail = Chapter::where('id',$value->chapter_id)->first();
            $data['chapter_list'][$key]->chapter_name = ($chapter_detail)?$chapter_detail->name:'';
        }

        // dd($data['chapter_list']);
        return view('site.common_confusion',$data);
    }

    public function exam_given_old(Request $request){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['subject_details'] = Subject::where('id',$request->subject_id)->first();
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['exam_list'] = Exam_user::select(['exams.*','exam_users.id as user_exam_id','exam_users.total_answer','exam_users.total_right_answer','exam_users.total_number','exam_users.created_at'])
                            ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                            ->leftJoin('question_papers', 'exams.question_paper_id', '=', 'question_papers.id')
                            ->leftJoin('question_paper_subjects', 'question_papers.id', '=', 'question_paper_subjects.question_paper_id')
                            ->where('exam_users.user_id',Auth::user()->id);

        if($data['exam_type']){
            if($data['exam_type'] == 1){
                $data['exam_list'] = $data['exam_list']->where('exams.type',1);
            }else if($data['exam_type'] == 2){
                $data['exam_list'] = $data['exam_list']->where('exams.type',2);
            }
        }

        if($data['subject_id']){
            $data['exam_list'] = $data['exam_list']->where('question_paper_subjects.subject_id',$data['subject_id']);
        }

        $data['exam_list'] = $data['exam_list'] ->distinct()->orderBy('exams.exam_date','ASC')->get();
        // dd($data['exam_list']);
        return view('site.exam_given',$data);
    }

    public function blank_two(){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['user'] = Auth::user();
        

        return view('site.blank_two',$data);
    }

    public function blank_three(){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['user'] = Auth::user();
        

        return view('site.blank_three',$data);
    }

    public function blank_four(){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['user'] = Auth::user();
        

        return view('site.blank_four',$data);
    }

    public function blank_five(){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['user'] = Auth::user();
        

        return view('site.blank_five',$data);
    }


    
}
