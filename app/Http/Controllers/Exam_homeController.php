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
use App\Models\Question_type;

use DB;

class Exam_homeController extends Controller
{
    private function getLanguageId(){
        return 1;
    }


    public function getToperResultList($type,$exam_type,$subject_id){
        $return = Exam_result::select([
                        DB::raw('count(*) as total_question'),
                        DB::raw('COUNT(CASE WHEN (exam_results.result != 0 AND exam_results.result != -1) THEN 1 END) as right_answer'),
                        DB::raw('COUNT(CASE WHEN (exam_results.result = 0 AND exam_results.answer = 0) THEN 1 END) as skip_answer'),
                        DB::raw('COUNT(CASE WHEN ((exam_results.result = 0 OR exam_results.result = -1) AND exam_results.answer != 0) THEN 1 END) as wrong_answer'),
                        DB::raw('SUM(result) as total_mark'),
                        'exam_results.user_id'
                    ])
                        ->leftJoin('exam_users', 'exam_users.id', '=', 'exam_results.exam_user_id')
                        ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
                        ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                        ->leftJoin('question_papers', 'question_papers.id', '=', 'question_paper_questions.question_paper_id')
                        ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                        ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                        ->when($subject_id !== null, function ($query) use ($subject_id) {
                            $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                        })
                        ->when($exam_type !== null, function ($query) use ($exam_type) {
                            $query->where('exams.type',$exam_type); 
                        })
                        ->when($type !== null, function ($query) use ($type) {
                            $query->whereRaw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) = ?', [$type]); 
                        })->where('exams.is_deleted',0)->groupBy('exam_results.user_id')
                                ->orderBy('total_mark','DESC')
                                ->first();
        
        if($return){
            $return->totals_marks_for_exam = Exam_user::select(['exam_users.total_mark'])
                        ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                        ->where('user_id',$return->user_id)
                        ->where('exams.is_deleted',0)
                        ->sum('total_mark');
        }
        

        return $return;
    }

    public function getAvgResultList($type,$exam_type,$subject_id){
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
                ->when($exam_type !== null, function ($query) use ($exam_type) {
                    $query->where('exams.type',$exam_type); 
                })
                ->when($type !== null, function ($query) use ($type) {
                    $query->whereRaw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) = ?', [$type]); 
                })->where('exams.is_deleted',0)->groupBy('exam_results.user_id');

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

    public function getYourResultList($type,$exam_type,$subject_id){
        $return = Exam_result::select([
                        DB::raw('count(*) as total_question'),
                        DB::raw('COUNT(CASE WHEN (exam_results.result != 0 AND exam_results.result != -1) THEN 1 END) as right_answer'),
                        DB::raw('COUNT(CASE WHEN (exam_results.result = 0 AND exam_results.answer = 0) THEN 1 END) as skip_answer'),
                        DB::raw('COUNT(CASE WHEN ((exam_results.result = 0 OR exam_results.result = -1) AND exam_results.answer != 0) THEN 1 END) as wrong_answer'),
                        DB::raw('SUM(result) as total_mark')
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
                        ->when($exam_type !== null, function ($query) use ($exam_type) {
                            $query->where('exams.type',$exam_type); 
                        });
        if($type){
            $return = $return->whereRaw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) = ?', [$type]);
        }

        $return = $return->where('exams.is_deleted',0)->groupBy('exam_results.user_id')
                                ->orderBy('total_mark','DESC')
                                ->first();
        // dd($return);

            
        if($return){
            $return->totals_marks_for_exam = Exam_user::select(['exam_users.total_mark'])
                            ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                            ->where('user_id',Auth::id())
                            ->where('exams.is_deleted',0)
                            ->sum('total_mark');
        }
        return $return;
    }

    public function answers_analytics(Request $request){
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['subject_details'] = Subject::where('id',$request->subject_id)->first();
        $data['subject_list'] = Subject::where('status',1)->get();

        
        $data['easy_toper_list'] = $this->getToperResultList(1,$data['exam_type'],$data['subject_id']);
        $data['avg_easy_toper_list'] = $this->getAvgResultList(1,$data['exam_type'],$data['subject_id']);
        $data['my_easy_toper_list'] = $this->getYourResultList(1,$data['exam_type'],$data['subject_id']);

        
        $data['medium_toper_list'] = $this->getToperResultList(2,$data['exam_type'],$data['subject_id']);
        $data['avg_medium_toper_list'] = $this->getAvgResultList(2,$data['exam_type'],$data['subject_id']);
        $data['my_medium_toper_list'] = $this->getYourResultList(2,$data['exam_type'],$data['subject_id']);

        
        $data['hard_toper_list'] = $this->getToperResultList(3,$data['exam_type'],$data['subject_id']);
        $data['avg_hard_toper_list'] = $this->getAvgResultList(3,$data['exam_type'],$data['subject_id']);
        $data['my_hard_toper_list'] = $this->getYourResultList(3,$data['exam_type'],$data['subject_id']);

        $data['topper_data'] = $this->getToperResultList(null,$data['exam_type'],$data['subject_id']);
        $data['average_data'] = $this->getAvgResultList(null,$data['exam_type'],$data['subject_id']);
        $data['you_data'] = $this->getYourResultList(null,$data['exam_type'],$data['subject_id']);

        $data['hard_topper_vs_average_you'] = $this->getToperResultList(null,$data['exam_type'],$data['subject_id']);
        $data['avg_topper_vs_average_you'] = $this->getAvgResultList(null,$data['exam_type'],$data['subject_id']);
        $data['my_topper_vs_average_you'] = $this->getYourResultList(null,$data['exam_type'],$data['subject_id']);

        $data['total_exam_attemped'] = Exam_user::select(['exam_users.total_mark'])
                        ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                        ->where('user_id',Auth::id())
                        ->where('exams.is_deleted',0)->count();

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
                            ", [Auth::id()]);

        $data['your_score'] = $userRank[0]->rank ?? 1;
        $data['total_user'] = Exam_user::select(['exam_users.total_mark'])
                        ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                        ->where('exams.is_deleted',0)->count();

        // dd($userRank);

        return view('site.answers_analytics',$data);
    }

    public function strength(Request $request){
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['subject_details'] = Subject::where('id',$request->subject_id)->first();
        $data['subject_list'] = Subject::where('status',1)->get();
        $subject_id = $request->subject_id;
        $exam_type = $request->exam_type;
        $data['chapter_list'] = Exam_result::select([
                                        DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) as chapter_id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.result', '>=',1)
                                    ->where('exam_results.user_id', Auth::id())
                                    ->when($subject_id !== null, function ($query) use ($subject_id) {
                                        $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                                    })
                                    ->when($exam_type !== null, function ($query) use ($exam_type) {
                                        $query->where('exams.type',$exam_type); 
                                    })
                                    ->whereRaw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) IS NOT NULL')
                                    ->where('exams.is_deleted',0)
                                    ->groupBy(DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id)'))
                                    ->orderByDesc('total_result')
                                    ->get();

        foreach($data['chapter_list'] as $key => $value){
            $chapter_detail = Chapter::where('id',$value->chapter_id)->first();
            $data['chapter_list'][$key]->chapter_name = ($chapter_detail)?$chapter_detail->name:'';
        }
        $total_count = Exam_result::where('exam_results.user_id', Auth::id())->count();
        
        if($total_count){
            $data['silly_percentage'] = $this->progressReportAnswerType(1,3,$data['exam_type'],$data['subject_id'],$total_count);
            $data['wrong_percentage'] = $this->progressReportAnswerType(1,2,$data['exam_type'],$data['subject_id'],$total_count);
            $data['irrelevant_percentage'] = $this->progressReportAnswerType(1,4,$data['exam_type'],$data['subject_id'],$total_count);
        }else{
            $data['silly_percentage'] = 0;
            $data['wrong_percentage'] = 0;
            $data['irrelevant_percentage'] = 0;
        }
        
        $data['easy_details'] = $this->progressReportQuestionLevel(1,1,$data['exam_type'],$data['subject_id']);
        $data['medium_details'] = $this->progressReportQuestionLevel(1,2,$data['exam_type'],$data['subject_id']);
        $data['hard_details'] = $this->progressReportQuestionLevel(1,3,$data['exam_type'],$data['subject_id']);

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
                                    ->when($exam_type !== null, function ($query) use ($exam_type) {
                                        $query->where('exams.type',$exam_type); 
                                    })
                                    ->where('exams.is_deleted',0)
                                    ->whereRaw('COALESCE(questions.question_type_id, offline_exam_questions.question_type_id) = ?', [$value->id])
                                    ->groupBy(DB::raw('COALESCE(questions.question_type_id, offline_exam_questions.question_type_id)'))
                                    ->first();
        }
        // dd($data['question_type']);
        return view('site.strength',$data);
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
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['subject_details'] = Subject::where('id',$request->subject_id)->first();
        $data['subject_list'] = Subject::where('status',1)->get();
        $subject_id = $request->subject_id;
        $exam_type = $request->exam_type;

        $data['chapter_list'] = Exam_result::select([
                                        DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) as chapter_id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_results.user_id', Auth::id())
                                    ->where('exam_results.result', '<',1)
                                    ->whereRaw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) IS NOT NULL')
                                    ->when($subject_id !== null, function ($query) use ($subject_id) {
                                        $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                                    })
                                    ->when($exam_type !== null, function ($query) use ($exam_type) {
                                        $query->where('exams.type',$exam_type); 
                                    })
                                    ->where('exams.is_deleted',0)
                                    ->groupBy(DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id)'))
                                    ->orderBy('total_result', 'asc')
                                    ->get();

        foreach($data['chapter_list'] as $key => $value){
            $chapter_detail = Chapter::where('id',$value->chapter_id)->first();
            $data['chapter_list'][$key]->chapter_name = ($chapter_detail)?$chapter_detail->name:'';
        }

        $total_count = Exam_result::where('exam_results.user_id', Auth::id())->count();
        
        if($total_count){
            $data['silly_percentage'] = $this->progressReportAnswerType(2,3,$data['exam_type'],$data['subject_id'],$total_count);
            $data['wrong_percentage'] = $this->progressReportAnswerType(2,2,$data['exam_type'],$data['subject_id'],$total_count);
            $data['irrelevant_percentage'] = $this->progressReportAnswerType(2,4,$data['exam_type'],$data['subject_id'],$total_count);
        }else{
            $data['silly_percentage'] = 0;
            $data['wrong_percentage'] = 0;
            $data['irrelevant_percentage'] = 0;
        }
        
        $data['easy_details'] = $this->progressReportQuestionLevel(2,1,$data['exam_type'],$data['subject_id']);
        $data['medium_details'] = $this->progressReportQuestionLevel(2,2,$data['exam_type'],$data['subject_id']);
        $data['hard_details'] = $this->progressReportQuestionLevel(2,3,$data['exam_type'],$data['subject_id']);

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
                                    ->where('exam_results.result', '<',1)
                                    ->when($subject_id !== null, function ($query) use ($subject_id) {
                                        $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                                    })
                                    ->when($exam_type !== null, function ($query) use ($exam_type) {
                                        $query->where('exams.type',$exam_type); 
                                    })
                                    ->where('exams.is_deleted',0)
                                    ->whereRaw('COALESCE(questions.question_type_id, offline_exam_questions.question_type_id) = ?', [$value->id])
                                    ->groupBy(DB::raw('COALESCE(questions.question_type_id, offline_exam_questions.question_type_id)'))
                                    ->first();
        }

        return view('site.weakness',$data);
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

    public function progressReportQuestionLevel($question_type,$type,$exam_type,$subject_id){

        if($question_type == 1){
            $details = Exam_result::select([DB::raw('count(*) as total'),DB::raw('COUNT(CASE WHEN (exam_results.result > 0) THEN 1 END) as result')]);
        }else if($question_type == 2){
            $details = Exam_result::select([DB::raw('count(*) as total'),DB::raw('COUNT(CASE WHEN (exam_results.result < 1) THEN 1 END) as result')]);         
        }else{
            $details = Exam_result::select([DB::raw('count(*) as total'),DB::raw('COUNT(CASE WHEN (exam_results.result < 1) THEN 1 END) as result')]);
        }
        $details = $details->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
                                ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                ->whereRaw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) = ?', [$type])
                                ->when($subject_id !== null, function ($query) use ($subject_id) {
                                        $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                                    })
                                ->when($exam_type !== null, function ($query) use ($exam_type) {
                                    $query->where('exams.type',$exam_type); 
                                })
                                ->where('exams.is_deleted',0)
                                ->where('exam_results.user_id', Auth::id())
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

    public function progressReportAnswerType($question_type,$type,$exam_type,$subject_id,$total_count){
        if($question_type == 1){
            $details = Exam_result::select([DB::raw('count(*) as total'),DB::raw('COUNT(CASE WHEN (exam_results.result > 0) THEN 1 END) as result')]);
        }else if($question_type == 2){
            $details = Exam_result::select([DB::raw('count(*) as total'),DB::raw('COUNT(CASE WHEN (exam_results.result < 1) THEN 1 END) as result')]);         
        }else{
            $details = Exam_result::select([DB::raw('count(*) as total'),DB::raw('COUNT(CASE WHEN (exam_results.result < 1) THEN 1 END) as result')]);
        }

        $details = $details->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
                        ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                        ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                        ->leftJoin('question_details', 'questions.id', '=', 'question_details.question_id')
                        ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                        ->where('exam_results.user_id', Auth::id())
                        ->when($subject_id !== null, function ($query) use ($subject_id) {
                            $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                        })
                        ->when($exam_type !== null, function ($query) use ($exam_type) {
                            $query->where('exams.type',$exam_type); 
                        })
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
                        ->where('exams.is_deleted',0)
                        ->first();
        // dd($details->toSql(), $details->getBindings());
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
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['subject_details'] = Subject::where('id',$request->subject_id)->first();
        $data['subject_list'] = Subject::where('status',1)->get();

        $data['topper_data'] = $this->getToperResultList(null,$data['exam_type'],$data['subject_id']);
        $data['average_data'] = $this->getAvgResultList(null,$data['exam_type'],$data['subject_id']);
        $data['you_data'] = $this->getYourResultList(null,$data['exam_type'],$data['subject_id']);

        $total_count = Exam_result::where('exam_results.user_id', Auth::id())->count();
        if($total_count){
            $data['silly_percentage'] = $this->progressReportAnswerType('',3,$data['exam_type'],$data['subject_id'],$total_count);
            $data['wrong_percentage'] = $this->progressReportAnswerType('',2,$data['exam_type'],$data['subject_id'],$total_count);
            $data['irrelevant_percentage'] = $this->progressReportAnswerType('',4,$data['exam_type'],$data['subject_id'],$total_count);
        }else{
            $data['silly_percentage'] = 0;
            $data['wrong_percentage'] = 0;
            $data['irrelevant_percentage'] = 0;
        }
            
        
        $data['easy_details'] = $this->progressReportQuestionLevel('',1,$data['exam_type'],$data['subject_id']);
        $data['medium_details'] = $this->progressReportQuestionLevel('',2,$data['exam_type'],$data['subject_id']);
        $data['hard_details'] = $this->progressReportQuestionLevel('',3,$data['exam_type'],$data['subject_id']);

        foreach($data['subject_list'] as $key => $value){
            $exam_result = Exam_result::select([
                                DB::raw('count(*) as total'),
                                DB::raw('COUNT(CASE WHEN (exam_results.result > 0) THEN 1 END) as result'),
                                DB::raw('SUM(result) as total_mark')
                            ])
                            ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                            ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                            ->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$value->id])
                            ->first();
            $value->percentage = "";
            if($exam_result){
                if($exam_result->total){
                    $value->percentage = $exam_result->result/$exam_result->total*100;
                }else{
                    $value->percentage = 0;
                }
                    
            }
        }

        // dd($data['subject_list']);


        return view('site.progress_report',$data);
    }

    public function personal_coach(Request $request){
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['subject_details'] = Subject::where('id',$request->subject_id)->first();
        $data['subject_list'] = Subject::where('status',1)->get();
        return view('site.personal_coach',$data);
    }


    
}
