<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Question_paper;
use App\Models\Question_paper_subject;
use App\Models\Question_paper_question;
use App\Models\Subject;
use App\Models\Question;
use App\Models\Question_detail;
use App\Models\Question_type;
use App\Models\Question_paper_question_type;

class Question_paperController extends Controller
{
    public function list(){
        $data = [];
        
        if(session()->get('admmin_is_super') == 'T'){
            $data['list'] = Question_paper::where('is_deleted',0)->where('administrator_id',session()->get('adminAuth'))->get();
        }else{
            $data['list'] = Question_paper::where('is_deleted',0)->get();
        }
        
        return view('admin.question_paper.list',$data);
    }


    public function add(){
        $data = [];
        if(session()->get('admmin_is_super') == 'T'){
            $data['subject_list'] = Subject::where('status',1)->where('id',session()->get('admmin_subject_id'))->get();
        }else{
            $data['subject_list'] = Subject::where('status',1)->get();
        }

        $data['question_type_list'] = Question_type::where('status',1)->get();
        return view('admin.question_paper.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        // dd($input);
        $loginCheck = Question_paper::where('name',$request->name)->first();

        // if (!$loginCheck){
        if (1){
            $insertData = [];
            
            $insertData['name'] = $request->name;
            $insertData['no_of_question'] = $request->no_of_question;
            $insertData['totals_marks_for_exam'] = $request->totals_marks_for_exam;
            $insertData['total_time_for_exam'] = $request->total_time_for_exam;
            $insertData['marks_per_question'] = $request->marks_per_question;
            $insertData['time_per_question'] = $request->time_per_question;
            $insertData['negative_marking_applicable'] = $request->negative_marking_applicable;
            $insertData['negative_marking_per_question'] = $request->negative_marking_per_question;

            $insertData['hard_level'] = $request->hard_level;
            $insertData['medium_level'] = $request->medium_level;
            $insertData['easy_level'] = $request->easy_level;
            $insertData['status'] = ($request->status)?$request->status:0;

            if(session()->get('admmin_is_super') == 'T'){
                $insertData['administrator_id'] = session()->get('adminAuth');
            }

            $exam = Question_paper::create($insertData);

            $subject_id = $request->subject_id;
            $total_no_of_question = $request->total_no_of_question;
            $chapter_id = $request->chapter_id;
            $topic_id = $request->topic_id;
            $sub_topic_id = $request->sub_topic_id;
            $question_number = 1;
            foreach($subject_id as $key => $value){
                $insertData = [];
                $insertData['subject_id'] = $value;
                $insertData['question_paper_id'] = $exam->id;
                if(isset($chapter_id[$key]) && !empty($chapter_id[$key])){
                    $insertData['chapter_id'] = $chapter_id[$key];
                }
                if(isset($topic_id[$key]) && !empty($topic_id[$key])){
                    $insertData['topic_id'] = $topic_id[$key];
                }
                if(isset($sub_topic_id[$key]) && !empty($sub_topic_id[$key])){
                    $insertData['sub_topic_id'] = $sub_topic_id[$key];
                }
                
                $insertData['total_no_of_question'] = $total_no_of_question[$key];
                // dd($insertData);
                $insertData['status'] = 1;
                $exam_subject = Question_paper_subject::create($insertData);


                $question = Question::select(['id'])->where("subject_id",$value);

                if(isset($insertData['chapter_id'])){
                    $question = $question->where("chapter_id",$insertData['chapter_id']);
                }
                if(isset($insertData['topic_id'])){
                    $question = $question->where("topic_id",$insertData['topic_id']);
                }
                if(isset($insertData['sub_topic_id'])){
                    $question = $question->where("sub_topic_id",$insertData['sub_topic_id']);
                }
                $question = $question->inRandomOrder()->take($insertData['total_no_of_question'])->get();


                foreach($question as $val){
                    $insertData = [];
                    $insertData['question_number'] = $question_number;
                    $insertData['question_id'] = $val->id;
                    $insertData['question_paper_subject_id'] = $exam_subject->id;
                    $insertData['question_paper_id'] = $exam->id;
                    $insertData['status'] = 1;
                    Question_paper_question::create($insertData);

                    $question_number = $question_number+1;
                }
            }

            $question_type = $request->question_type;
            $insertData1 = [];
            $insertData1['question_paper_id'] = $exam->id;
            foreach($question_type as $key => $value){
                if($value){
                    $insertData1['question_type_id'] = $key;
                    $insertData1['value'] = $value;
                    Question_paper_question_type::create($insertData1);
                }                    
            }
        
            toastr()->success('Question Bank added successfully.');
            return redirect()->route('admin.question_paper');
        }else{
            toastr()->warning('Question Bank already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['details'] = Question_paper::where('id',$request->id)->first();
        return view('admin.question_paper.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Question_paper::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            
            $insertData['name'] = $request->name;
            $insertData['no_of_question'] = $request->no_of_question;
            $insertData['totals_marks_for_exam'] = $request->totals_marks_for_exam;
            $insertData['total_time_for_exam'] = $request->total_time_for_exam;
            $insertData['marks_per_question'] = $request->marks_per_question;
            $insertData['time_per_question'] = $request->time_per_question;
            $insertData['negative_marking_applicable'] = $request->negative_marking_applicable;
            $insertData['negative_marking_per_question'] = $request->negative_marking_per_question;
            $insertData['status'] = ($request->status)?$request->status:0;

            $loginCheck->update($insertData);
            toastr()->success('Question Bank updated successfully.');
            return redirect()->route('admin.question_paper');
        }else{
            toastr()->warning('Question Bank already exist');
            return back()->withInput();
        }
    }
    
    
    public function delete(Request $request){
        $id = $request->id;
        $exam = Question_paper::where('id',$id)->first();
        // dd($exam);
        if($exam){
            $exam->update(['is_deleted'=>1]);
        }
        
        toastr()->success('Question Bank deleted successfully.');
        return redirect()->route('admin.question_paper');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Question_paper::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Question Bank status change successfully.');
        return redirect()->route('admin.question_papers');
    }

    public function question(Request $request){

        $data = [];
        $data['details'] = Question_paper::where('id',$request->id)->first();
        $data['list'] = Question_paper_question::select(['questions.*','question_paper_questions.question_paper_id','question_paper_questions.id as question_paper_question_id','question_paper_questions.question_paper_subject_id','subjects.name as subject_name'])
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->leftJoin('subjects', 'questions.subject_id', '=', 'subjects.id')
            ->where('question_paper_questions.question_paper_id',$request->id)->get();

        $question_ids = [];
        foreach ($data['list'] as $key => $value) {
            $question = Question_detail::where("question_id",$value->id)->where("language_id",1)->first();
            $data['list'][$key]->question_text = $question->question_text;
            $data['list'][$key]->question_image = $question->question_image;

            array_push($question_ids, $value->id);
        }

        $data['question_list'] = Question_detail::select(['question_details.question_id','question_details.question_text'])->leftJoin('questions', 'questions.id', '=', 'question_details.question_id')->whereNotIn('question_details.question_id',$question_ids)->where('questions.is_deleted',0)->where('questions.status',1)->where('question_details.language_id',1)->orderBy('question_details.id','DESC')->get();

        return view('admin.question_paper.question',$data);
    }

    public function download_question(Request $request){
        $data = [];
        $data['details'] = Question_paper::where('id',$request->id)->first();
        $data['subject_list'] = Question_paper_subject::select(['question_paper_subjects.*','subjects.name as subject_name'])
                                    ->leftJoin('subjects', 'question_paper_subjects.subject_id', '=', 'subjects.id')
                                    ->where('question_paper_id',$request->id)->get();
        
        // dd($data['subject_list']);

        $data['question_list'] = [];
        $question_list = [];
        foreach($data['subject_list'] as $key => $value){

            $indexA = 0;
            $indexB = $key+1;
            $value->question_list = Question_paper_question::select(['questions.*','question_paper_questions.question_paper_id','question_paper_questions.question_paper_subject_id'])
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->where('question_paper_questions.question_paper_id',$request->id)
            ->where('question_paper_questions.question_paper_subject_id',$value->id)
            ->get();

            foreach ($value->question_list as $k1 => $v1) {
                $question = Question_detail::where("question_id",$v1->id)->where("language_id",1)->first();
                $v1->question_text = $question->question_text;
                $v1->question_image = $question->question_image;
                $v1->option1 = $question->option1;
                $v1->option2 = $question->option2;
                $v1->option3 = $question->option3;
                $v1->option4 = $question->option4;
                $v1->is_option1_image = $question->is_option1_image;
                $v1->is_option2_image = $question->is_option2_image;
                $v1->is_option3_image = $question->is_option3_image;
                $v1->is_option4_image = $question->is_option4_image;
            }
        }

        // dd($data['question_list']);
        // return view('template.exam_question_pdf',$data); exit;
        $pdf = PDF::loadView('template.exam_question_pdf', $data);

        return $pdf->download('exam_question.pdf');
    }

    public function download_answer(Request $request){
        $data = [];
        $data['details'] = Question_paper::where('id',$request->id)->first();
        $data['subject_list'] = Question_paper_subject::select(['question_paper_subjects.*','subjects.name as subject_name'])
                                    ->leftJoin('subjects', 'question_paper_subjects.subject_id', '=', 'subjects.id')
                                    ->where('question_paper_id',$request->id)->get();
        
        // dd($data['subject_list']);
                      
        foreach($data['subject_list'] as $key => $value){
            $data['subject_list'][$key]->question_list = Question_paper_question::select(['questions.*','question_paper_questions.question_paper_id','question_paper_questions.question_paper_subject_id'])
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->where('question_paper_questions.question_paper_id',$request->id)
            ->where('question_paper_questions.question_paper_subject_id',$value->id)
            ->get();

            foreach ($data['subject_list'][$key]->question_list as $k1 => $v1) {
                $question = Question_detail::where("question_id",$v1->id)->where("language_id",1)->first();
                $data['subject_list'][$key]->question_list[$k1]->question_text = $question->question_text;
                $data['subject_list'][$key]->question_list[$k1]->question_image = $question->question_image;
                $data['subject_list'][$key]->question_list[$k1]->option1 = $question->option1;
                $data['subject_list'][$key]->question_list[$k1]->option2 = $question->option2;
                $data['subject_list'][$key]->question_list[$k1]->option3 = $question->option3;
                $data['subject_list'][$key]->question_list[$k1]->option4 = $question->option4;
                $data['subject_list'][$key]->question_list[$k1]->is_option1_image = $question->is_option1_image;
                $data['subject_list'][$key]->question_list[$k1]->is_option2_image = $question->is_option2_image;
                $data['subject_list'][$key]->question_list[$k1]->is_option3_image = $question->is_option3_image;
                $data['subject_list'][$key]->question_list[$k1]->is_option4_image = $question->is_option4_image;
            }
        }
        // dd($data['subject_list']);

        $pdf = PDF::loadView('template.exam_answer_pdf', $data);

        return $pdf->download('exam_answer.pdf');
    }

    public function change_question(Request $request){
        $data = [];
        $question_paper_id = $request->question_paper_id;
        $question_paper_question_id = $request->question_paper_question_id;
        $question_id = $request->question_id;

        $question_paper_question = Question_paper_question::where('id',$question_paper_question_id)->where('question_paper_id',$question_paper_id)->first();


        $question_paper_question->update(['question_id'=>$question_id]);

        toastr()->success('Question change successfully.');
        return redirect()->route('admin.question_paper.question',['id'=>$question_paper_id]);
    }

    public function view(Request $request){
        $input = $request->all();
        $data = [];
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['details'] = Question_paper::where('id',$request->id)->first();
        $data['question_paper_subject'] = Question_paper_subject::select(['question_paper_subjects.*','subjects.name as subject_name','chapters.name as chapter_name','topics.name as topic_name','sub_topics.name as sub_topic_name'])
                ->leftJoin('subjects', 'question_paper_subjects.subject_id', '=', 'subjects.id')
                ->leftJoin('chapters', 'question_paper_subjects.chapter_id', '=', 'chapters.id')
                ->leftJoin('topics', 'question_paper_subjects.topic_id', '=', 'topics.id')
                ->leftJoin('sub_topics', 'question_paper_subjects.sub_topic_id', '=', 'sub_topics.id')
                ->where('question_paper_id',$data['details']->id)->get();

        $data['question_paper_question_type'] = Question_paper_question_type::select(['question_paper_question_types.*','question_types.name'])
                ->leftJoin('question_types', 'question_paper_question_types.question_type_id', '=', 'question_types.id')
                ->where('question_paper_question_types.question_paper_id',$data['details']->id)
                ->get();

        // dd($data['question_paper_subject']);
        return view('admin.question_paper.view',$data);
    }
    
}
