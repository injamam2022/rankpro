<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Models\BannerDescription;
use App\Models\User_exam;
use App\Models\Location;
use App\Models\Exam;
use App\Models\User;
use App\Models\Exam_user;
use App\Models\Question_paper_question;
use App\Models\Exam_result;
use App\Models\Payment;
use App\Models\Ranker;
use App\Models\TestSeries;
use App\Models\TestSeriesHeading;
use App\Models\Problem;
use App\Models\Problem_type;
use App\Models\User_detail;
use App\Models\Question_detail;
use App\Models\Offline_exam_question;
use App\Models\Dashboard_banner;
use App\Models\Dashboard_content;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Setting;

use DB;

class StudentsController extends Controller
{
    private function getLanguageId(){
        return 1;
    }

    public function getLeaderBoardData($type){
        $return = Exam_user::select(['exam_users.*',DB::raw('AVG(exam_users.percentage) as total_result'),DB::raw('SUM(exam_users.total_mark) as total_mark'),DB::raw('SUM(exam_users.total_number) as total_number'),'users.first_name','users.last_name','users.profile_img',DB::raw('count(*) as total_exam')])
                    ->leftJoin('exams', 'exams.id', '=', 'exam_users.user_id')
                    ->leftJoin('users', 'users.id', '=', 'exam_users.user_id')
                    ->groupBy('exam_users.user_id')
                    ->orderBy('total_result','desc')->take(2)->get();
        return $return;
    }

    public function getUserLeaderBoardData($type){
        
    }

    public function dashboard(Request $request){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['user'] = Auth::user();
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['exam_user_type'] = $request->exam_user_type;
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;

        $subject_id = $request->subject_id;
        $exam_type = $request->exam_type;

        $data['exam_list'] = Exam_user::select(['exams.*','exam_users.id as user_exam_id','exam_users.total_answer','exam_users.total_right_answer','exam_users.total_number'])
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
                                })->distinct()->orderBy('exams.exam_date','DESC')->take(5)->get();

        $data['upcoming_list'] = Exam::select(['exams.*','locations.location_name'])
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
                                    ->distinct()->orderBy('exams.exam_date','ASC')->take(5)->get();

        $data['leader_board'] = Exam_user::select(['exam_users.*',DB::raw('AVG(exam_users.percentage) as total_result'),DB::raw('SUM(exam_users.total_mark) as total_mark'),DB::raw('SUM(exam_users.total_number) as total_number'),'users.first_name','users.last_name','users.profile_img','users.rankpro_id',DB::raw('count(*) as total_exam')])
                    ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                    ->leftJoin('users', 'users.id', '=', 'exam_users.user_id');

        if($data['exam_user_type']){
            if($data['exam_user_type'] == "OTS"){
                $data['leader_board'] = $data['leader_board']->where('exams.type',1);
            }else{
                $data['leader_board'] = $data['leader_board']->where('exams.type',2)->where('exam_users.exam_type',$data['exam_user_type']);
            }
        }

        $data['leader_board'] = $data['leader_board']->where('exams.is_deleted',0)->groupBy('exam_users.user_id')
                    ->orderBy('total_number','desc')->take(2)->get();

                    // dd($data['leader_board']);
        
            
        $data['my_leader_board'] = Exam_user::select(['exam_users.*',DB::raw('AVG(exam_users.percentage) as total_result'),DB::raw('SUM(exam_users.total_mark) as total_mark'),DB::raw('SUM(exam_users.total_number) as total_number'),'users.first_name','users.last_name','users.profile_img','users.rankpro_id',DB::raw('count(*) as total_exam')])
                    ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                    ->leftJoin('users', 'users.id', '=', 'exam_users.user_id');

        if($data['exam_user_type']){
            if($data['exam_user_type'] == "OTS"){
                $data['my_leader_board'] = $data['my_leader_board']->where('exams.type',1);
            }else{
                $data['my_leader_board'] = $data['my_leader_board']->where('exams.type',2)->where('exam_users.exam_type',$data['exam_user_type']);
            }
        }

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

        $data['trending_test'] = Exam::select(['exams.*','locations.location_name'])
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
                                    ->where('exams.is_trending',1)->distinct()->orderBy('exams.exam_date','ASC')->take(5)->get();
                            
        $data['dashboard_banner'] = Dashboard_banner::where('status',1)->get();

        $data['dashboard_content'] = Dashboard_content::first();
        $data['neet_exam'] = Setting::where('key_name','neet_exam')->first();

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
        return view('site.dashboard',$data);
    }

    public function dashboard_exam_type(Request $request){
        $chapter_id = $request->chapter_id;
        $topic_id = $request->topic_id;
        $data = [];
        $data['list'] = Exam_result::select([
                                        DB::raw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id) as sub_topic_id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->whereRaw('COALESCE(questions.topic_id, offline_exam_questions.topic_id) = ?', [$topic_id])
                                    ->groupBy(DB::raw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id)'))
                                    ->orderBy('total_result', 'asc')
                                    ->take(8)->get();

        foreach($data['list'] as $key => $value){
            $topic_detail = Sub_topic::where('id',$value->sub_topic_id)->first();
            $data['list'][$key]->sub_topic_name = ($topic_detail)?$topic_detail->name:'';
        }
        echo json_encode($data);
    }

    
    public function getIdName($detail,$type){
        $name = "";
        if($detail){
            // dd($detail);
            $id = $detail->id;
            if($type == 1){
                $table_data = Subject::where('id',$detail->subject_id)->first();
                $name = ($table_data)?$table_data->name:"";
            }else if($type == 2){
                $table_data = Chapter::where('id',$detail->chapter_id)->first();
                $name = ($table_data)?$table_data->name:"";
            }else if($type == 3){
                $table_data = Topic::where('id',$detail->topic_id)->first();
                $name = ($table_data)?$table_data->name:"";
            }
        }        

        return $name;
    }

    public function strong_areas(){
        $language_id = $this->getLanguageId();
        $data = [];
        $subject_detail = Exam_result::select([
                                        DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) as subject_id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('exam_questions', 'exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) IS NOT NULL')
                                    ->groupBy(DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id)'))
                                    ->orderByDesc('total_result')
                                    ->first();
            // dd($subject_detail);

        $chapter_detail = Exam_result::select([
                                        DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) as chapter_id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('exam_questions', 'exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->whereRaw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) IS NOT NULL')
                                    ->groupBy(DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id)'))
                                    ->orderByDesc('total_result')
                                    ->first();

        $topic_detail = Exam_result::select([
                                        DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id) as topic_id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('exam_questions', 'exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->whereRaw('COALESCE(questions.topic_id, offline_exam_questions.topic_id) IS NOT NULL')
                                    ->groupBy(DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id)'))
                                    ->orderByDesc('total_result')
                                    ->first();
        
        $data['user_detail'] = [];
        $data['user_detail']['subject'] = $this->getIdName($subject_detail,1);
        $data['user_detail']['chapter'] = $this->getIdName($chapter_detail,2);
        $data['user_detail']['topic'] = $this->getIdName($topic_detail,3);


        return view('site.strong_areas',$data);
    }

    public function can_improve(){
        $language_id = $this->getLanguageId();
        $data = [];
        
        $subject_detail = Exam_result::select([
                                        DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) as subject_id'),
                                        DB::raw('SUM(
                                            CASE
                                                WHEN exam_results.result = 1 AND (
                                                    (exam_results.answer = 1 AND (question_details.answer_behavior_tag1 IN (2,3) OR offline_exam_questions.answer_behavior_tag1 IN (2,3))) OR
                                                    (exam_results.answer = 2 AND (question_details.answer_behavior_tag2 IN (2,3) OR offline_exam_questions.answer_behavior_tag2 IN (2,3))) OR
                                                    (exam_results.answer = 3 AND (question_details.answer_behavior_tag3 IN (2,3) OR offline_exam_questions.answer_behavior_tag3 IN (2,3))) OR
                                                    (exam_results.answer = 4 AND (question_details.answer_behavior_tag4 IN (2,3) OR offline_exam_questions.answer_behavior_tag4 IN (2,3)))
                                                )
                                                THEN 1 ELSE 0
                                            END
                                        ) as total_result')
                                    ])
                                    ->leftJoin('exam_questions', 'exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
                                    ->leftJoin('question_details', 'questions.id', '=', 'exam_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) IS NOT NULL')
                                    ->groupBy(DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id)'))
                                    ->orderBy('total_result', 'DESC')
                                    ->first();
            // dd($subject_detail);
        $chapter_detail = Exam_result::select([
                                        DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) as chapter_id'),
                                        DB::raw('SUM(
                                            CASE
                                                WHEN exam_results.result = 1 AND (
                                                    (exam_results.answer = 1 AND (question_details.answer_behavior_tag1 IN (2,3) OR offline_exam_questions.answer_behavior_tag1 IN (2,3))) OR
                                                    (exam_results.answer = 2 AND (question_details.answer_behavior_tag2 IN (2,3) OR offline_exam_questions.answer_behavior_tag2 IN (2,3))) OR
                                                    (exam_results.answer = 3 AND (question_details.answer_behavior_tag3 IN (2,3) OR offline_exam_questions.answer_behavior_tag3 IN (2,3))) OR
                                                    (exam_results.answer = 4 AND (question_details.answer_behavior_tag4 IN (2,3) OR offline_exam_questions.answer_behavior_tag4 IN (2,3)))
                                                )
                                                THEN 1 ELSE 0
                                            END
                                        ) as total_result')
                                    ])
                                    ->leftJoin('exam_questions', 'exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
                                    ->leftJoin('question_details', 'questions.id', '=', 'exam_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->whereRaw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) IS NOT NULL')
                                    ->groupBy(DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id)'))
                                    ->orderBy('total_result', 'DESC')
                                    ->first();

        $topic_detail = Exam_result::select([
                                        DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id) as topic_id'),
                                        DB::raw('SUM(
                                            CASE
                                                WHEN exam_results.result = 1 AND (
                                                    (exam_results.answer = 1 AND (question_details.answer_behavior_tag1 IN (2,3) OR offline_exam_questions.answer_behavior_tag1 IN (2,3))) OR
                                                    (exam_results.answer = 2 AND (question_details.answer_behavior_tag2 IN (2,3) OR offline_exam_questions.answer_behavior_tag2 IN (2,3))) OR
                                                    (exam_results.answer = 3 AND (question_details.answer_behavior_tag3 IN (2,3) OR offline_exam_questions.answer_behavior_tag3 IN (2,3))) OR
                                                    (exam_results.answer = 4 AND (question_details.answer_behavior_tag4 IN (2,3) OR offline_exam_questions.answer_behavior_tag4 IN (2,3)))
                                                )
                                                THEN 1 ELSE 0
                                            END
                                        ) as total_result')
                                    ])
                                    ->leftJoin('exam_questions', 'exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
                                    ->leftJoin('question_details', 'questions.id', '=', 'exam_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->whereRaw('COALESCE(questions.topic_id, offline_exam_questions.topic_id) IS NOT NULL')
                                    ->groupBy(DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id)'))
                                    ->orderBy('total_result', 'DESC')
                                    ->first();
        
        $data['user_detail'] = [];
        $data['user_detail']['subject'] = $this->getIdName($subject_detail,1);
        $data['user_detail']['chapter'] = $this->getIdName($chapter_detail,2);
        $data['user_detail']['topic'] = $this->getIdName($topic_detail,3);
        
        return view('site.can_improve',$data);
        
    }

    public function need_to_work_hard(){
        $language_id = $this->getLanguageId();
        $data = [];
        $subject_detail = Exam_result::select([
                                        DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) as subject_id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('exam_questions', 'exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->groupBy(DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id)'))
                                    ->orderBy('total_result', 'asc')
                                    ->first();

        $chapter_detail = Exam_result::select([
                                        DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) as chapter_id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('exam_questions', 'exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->groupBy(DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id)'))
                                    ->orderBy('total_result', 'asc')
                                    ->first();

        $topic_detail = Exam_result::select([
                                        DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id) as topic_id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('exam_questions', 'exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->groupBy(DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id)'))
                                    ->orderBy('total_result', 'asc')
                                    ->first();
        
        $data['user_detail'] = [];
        $data['user_detail']['subject'] = $this->getIdName($subject_detail,1);
        $data['user_detail']['chapter'] = $this->getIdName($chapter_detail,2);
        $data['user_detail']['topic'] = $this->getIdName($topic_detail,3);

        return view('site.need_to_work_hard',$data);
        
    }

    public function profile(){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['user'] = Auth::user();
        return view('site.profile',$data);
    }

    public function update_profile(Request $request){

        $validatedData = $request->validate([
            'first_name' => 'required',
        ]);

        $language_id = $this->getLanguageId();

        $user_id = Auth::user()->id;

        $loginCheck = User::where('id',$user_id)->first();

        if($loginCheck){
            // $input = $request->all();
            // dd($input);
            $insertData = [];
            if ($image = $request->file('profileImage')){
                $insertData['profile_img'] = time().'.'.$image->getClientOriginalExtension();


                $destinationPath = public_path('/uploads/profileImage');
                $image->move($destinationPath, $insertData['profile_img']);

                if($loginCheck->profile_img){
                    if (file_exists(public_path($loginCheck->profile_img))) {
                        unlink(public_path($loginCheck->profile_img));
                    }
                }
                // $insertData['profile_img'] = 'uploads/profileImage/'.$insertData['profile_img'];
            }
            if ($image = $request->file('certificate')){
                $insertData['certificate'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/certificate');
                $image->move($destinationPath, $insertData['certificate']);

                if($loginCheck->certificate){
                    if (file_exists(public_path($loginCheck->certificate))) {
                        unlink(public_path($loginCheck->certificate));
                    }
                }
                $insertData['certificate'] = 'uploads/certificate/'.$insertData['certificate'];
            }
            
            /*if ($image = $request->file('guardian_signature')){
                $insertData['guardian_signature'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/guardian_signature');
                $image->move($destinationPath, $insertData['guardian_signature']);

                if($loginCheck->guardian_signature){
                    if (file_exists(public_path($loginCheck->guardian_signature))) {
                        unlink(public_path($loginCheck->guardian_signature));
                    }
                }
                $insertData['guardian_signature'] = 'uploads/guardian_signature/'.$insertData['guardian_signature'];
            }
            if ($image = $request->file('student_signature')){
                $insertData['student_signature'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/student_signature');
                $image->move($destinationPath, $insertData['student_signature']);

                if($loginCheck->student_signature){
                    if (file_exists(public_path($loginCheck->student_signature))) {
                        unlink(public_path($loginCheck->student_signature));
                    }
                }
                $insertData['student_signature'] = 'uploads/student_signature/'.$insertData['student_signature'];
            }
            */


            $insertData['first_name'] = $request->first_name;
            $insertData['last_name'] = $request->last_name;
            $insertData['address'] = $request->address;
            // $insertData['mobile_number'] = $request->mobile_number;
            $insertData['is_whatsapp'] = ($request->is_whatsapp)?1:0;
            // $insertData['email_id'] = $request->email_id;
            // $insertData['father_full_name'] = $request->father_full_name;
            // $insertData['father_occupation'] = $request->father_occupation;
            // $insertData['father_mobile_number'] = $request->father_mobile_number;
            $insertData['father_qualification'] = $request->father_qualification;
            $insertData['mother_full_name'] = $request->mother_full_name;
            // $insertData['mother_occupation'] = $request->mother_occupation;
            $insertData['mother_mobile_number'] = $request->mother_mobile_number;
            $insertData['mother_qualification'] = $request->mother_qualification;
            $insertData['qualification_details'] = $request->qualification_details;
            $insertData['facebook_link'] = $request->facebook_link;
            $insertData['instagram_link'] = $request->instagram_link;
            $insertData['youtube_link'] = $request->youtube_link;
            $insertData['twitter_link'] = $request->twitter_link;
            $insertData['whats_app_link'] = $request->whats_app_link;
            $insertData['linkedin_link'] = $request->linkedin_link;

            $loginCheck->update($insertData);
            toastr()->success('Profile updated successfully.');
        }else{
            toastr()->warning('Something wrong please try again later');
        }

        return redirect()->route('profile');
    }

    public function my_courses(){
        $language_id = $this->getLanguageId();
        $data = [];

        return view('site.my_courses',$data);
    }

    public function schedule(){
        $language_id = $this->getLanguageId();
        $data = [];

        return view('site.schedule',$data);
    }

    public function result(){
        $language_id = $this->getLanguageId();
        $data = [];

        $data['exam_result'] = Exam_user::select(['exams.*','exam_users.id as user_exam_id','exam_users.total_answer','exam_users.total_right_answer','exam_users.total_number'])
                                ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                                ->where('exam_users.user_id',Auth::user()->id)->where('exams.is_deleted',0)->get();
        return view('site.result',$data);
    }

    public function rankers_for_rankers(){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['ranker_list'] = Payment::select(['payments.*','rankers.name','rankers.icon'])
                                ->leftJoin('rankers', 'rankers.id', '=', 'payments.ranker_id')
                                ->where('payments.user_id',Auth::user()->id)
                                ->where('payments.type',1)->get();
        return view('site.rankers_for_rankers',$data);
    }

    public function plans(Request $request){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['payment_list'] = Payment::select(['payments.*'])
                                ->where('payments.user_id',Auth::user()->id)
                                ->get();
        $data['user'] = Auth::user();
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;

       

        return view('site.plans',$data);
    }

    public function terms_and_condition(){
        $language_id = $this->getLanguageId();
        $data = [];

        return view('site.terms_and_condition',$data);
    }

    public function report_problem(){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['type_list'] = Problem_type::where('status',1)->get();
        return view('site.report_problem',$data);
    }

    public function report_problem_save(Request $request){
        $insertData = [];
        $insertData["user_id"] = Auth::user()->id;
        $insertData['message'] = $request->message;
        $insertData['type'] = $request->type;
        
        Problem::create($insertData);
        return redirect()->route('report_problem')->with('success', 'Thanks for contact us');
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }

    
}
