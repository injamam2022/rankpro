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
use App\Models\Exam_mistake_input;
use App\Models\Mistake_input;
use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Sub_topic;
use App\Support\MistakeMonitorStats;

use DB;

class Common_confusionController extends Controller
{
    private function getLanguageId(){
        return 1;
    }

    public function common_confusion(Request $request){
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['mm'] = MistakeMonitorStats::dashboard($data['exam_type'], $data['subject_id'], [
            'status' => $request->input('mm_status', 'All'),
            'reason' => $request->input('mm_reason', 'All'),
            'page' => $request->input('mm_page', 1),
        ]);

        return view('site.common_confusion',$data);
    }

    public function master(Request $request)
    {
        $id = (int) $request->input('id');
        if ($id > 0) {
            MistakeMonitorStats::toggleMastered($id, Auth::id());
        }

        $return = (string) $request->input('return', '');
        if ($return !== '') {
            $host = parse_url($return, PHP_URL_HOST);
            if (!$host || $host === $request->getHost()) {
                return redirect()->to($return);
            }
        }

        return redirect()->route('common_confusion', array_filter([
            'exam_type' => $request->exam_type,
            'subject_id' => $request->subject_id,
            'mm_subject' => $request->mm_subject,
            'mm_status' => $request->mm_status,
            'mm_reason' => $request->mm_reason,
        ], function ($v) {
            return $v !== null && $v !== '';
        }));
    }

    public function common_confusion_topics(Request $request){
        $chapter_id = $request->chapter_id;
        $reason_id = $request->reason_id;
        $exam_type = $request->exam_type;
        $subject_id = $request->subject_id;
        $data = [];
        $data['list'] = Exam_mistake_input::select([
                                        DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id) as topic_id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('exam_results', 'exam_results.id', '=', 'exam_mistake_inputs.question_id')
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_mistake_inputs.user_id',Auth::user()->id)
                                    ->when($subject_id !== null, function ($query) use ($subject_id) {
                                        $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                                    })
                                    ->when($exam_type !== null, function ($query) use ($exam_type) {
                                        $query->where('exams.type',$exam_type); 
                                    })
                                    ->when($reason_id !== null, function ($query) use ($reason_id) {
                                        $query->where('exam_mistake_inputs.mistake_input_id',$reason_id); 
                                    })
                                    ->whereRaw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) = ?', [$chapter_id])
                                    ->groupBy(DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id)'))
                                    ->orderBy('total_result', 'asc')
                                    ->take(8)->get();

        foreach($data['list'] as $key => $value){
            $topic_detail = Topic::where('id',$value->topic_id)->first();
            $data['list'][$key]->topic_name = ($topic_detail)?$topic_detail->name:'';
        }
        echo json_encode($data);
    }

    public function common_confusion_subtopics(Request $request){
        $chapter_id = $request->chapter_id;
        $topic_id = $request->topic_id;
        $reason_id = $request->reason_id;
        $exam_type = $request->exam_type;
        $subject_id = $request->subject_id;
        $data = [];
        $data['list'] = Exam_mistake_input::select([
                                        DB::raw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id) as sub_topic_id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('exam_results', 'exam_results.id', '=', 'exam_mistake_inputs.question_id')
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_mistake_inputs.user_id',Auth::user()->id)
                                    ->when($subject_id !== null, function ($query) use ($subject_id) {
                                        $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                                    })
                                    ->when($exam_type !== null, function ($query) use ($exam_type) {
                                        $query->where('exams.type',$exam_type); 
                                    })
                                    ->when($reason_id !== null, function ($query) use ($reason_id) {
                                        $query->where('exam_mistake_inputs.mistake_input_id',$reason_id); 
                                    })
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

    public function common_confusion_question(Request $request){
        $chapter_id = $request->chapter_id;
        $topic_id = $request->topic_id;
        $sub_topic_id = $request->sub_topic_id;
        $reason_id = $request->reason_id;
        $exam_type = $request->exam_type;
        $subject_id = $request->subject_id;
        $data = [];
        $data['list'] = Exam_mistake_input::select([
                                        DB::raw('COALESCE(question_details.question_text, offline_exam_questions.question_text) as question_text'),
                                        DB::raw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) as difficulty_level'),
                                        DB::raw('COALESCE(questions.id, offline_exam_questions.sub_topic_id) as id'),
                                        DB::raw('SUM(exam_results.result) as total_result')
                                    ])
                                    ->leftJoin('exam_results', 'exam_results.id', '=', 'exam_mistake_inputs.question_id')
                                    ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->leftJoin('question_details', 'questions.id', '=', 'question_details.question_id')
                                    ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
                                    ->where('exam_mistake_inputs.user_id',Auth::user()->id)
                                    ->when($subject_id !== null, function ($query) use ($subject_id) {
                                        $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subject_id]); 
                                    })
                                    ->when($exam_type !== null, function ($query) use ($exam_type) {
                                        $query->where('exams.type',$exam_type); 
                                    })
                                    ->when($reason_id !== null, function ($query) use ($reason_id) {
                                        $query->where('exam_mistake_inputs.mistake_input_id',$reason_id); 
                                    })
                                    ->whereRaw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id) = ?', [$sub_topic_id])
                                    ->groupBy(DB::raw('COALESCE(questions.id, offline_exam_questions.id)'))
                                    ->orderBy('total_result', 'asc')
                                    ->take(8)->get();

        foreach($data['list'] as $key => $value){
            $topic_detail = Sub_topic::where('id',$value->sub_topic_id)->first();
            $data['list'][$key]->sub_topic_name = ($topic_detail)?$topic_detail->name:'';
        }
        echo json_encode($data);
    }



    
}
