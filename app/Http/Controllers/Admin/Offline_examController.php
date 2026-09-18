<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Barryvdh\DomPDF\Facade\Pdf;

use Carbon\Carbon;

use App\Models\Exam;
use App\Models\User;
use App\Models\Subject;
use App\Models\Exam_subject;
use App\Models\Exam_question;
use App\Models\Location;
use App\Models\Language;
use App\Models\Question;
use App\Models\Question_detail;
use App\Models\User_exam;
use App\Models\ExamLanguage;
use App\Models\ExamLocation;
use App\Models\ExamDate;
use App\Models\Exam_user;
use App\Models\Exam_result;
use App\Models\Question_paper;
use App\Models\Question_paper_question;
use App\Models\Offline_exam_question;
use App\Models\Exam_status;
use App\Models\Exam_mistake_input;

class Offline_examController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Exam::select(['exams.*','locations.location_name'])
                        ->leftJoin('locations', 'exams.location_id', '=', 'locations.id')
                        ->where('type',2)->where('is_deleted',0)->get();
        return view('admin.offline_exam.list',$data);
    }


    public function add(){
        $data = [];
        $data['language_list'] = Language::where('status',1)->get();
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['location_list'] = Location::where('status',1)->get();
        $data['question_paper_list'] = Question_paper::where('status',1)->where('is_deleted',0)->get();
        return view('admin.offline_exam.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'exam_code' => 'required',
                            'name' => 'required'
                        ]);

        $input = $request->all();
        // dd($input);
        $loginCheck = Exam::where('exam_code',$request->exam_code)->first();

        // if (!$loginCheck){
        if (1){
            $insertData = [];
            if ($image = $request->file('exam_logo')){
                $insertData['exam_logo'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/exam/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['exam_logo']);


                $destinationPath = public_path('/uploads/exam');
                $image->move($destinationPath, $insertData['exam_logo']);
            }
            if ($image = $request->file('landing_icon')){
                $insertData['landing_icon'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/exam/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['landing_icon']);


                $destinationPath = public_path('/uploads/exam');
                $image->move($destinationPath, $insertData['landing_icon']);
            }
            $insertData['type'] = 2;
            $insertData['exam_code'] = $request->exam_code;
            $insertData['exam_date'] = $request->exam_date;
            $insertData['exam_time'] = $request->exam_time;
            $insertData['location_id'] = $request->location_id;
            $insertData['name'] = $request->name;
            $insertData['question_type'] = $request->question_type;

            if($request->question_paper_id){
                $insertData['question_paper_id'] = $request->question_paper_id;
            }else{
                $insertData['no_of_question'] = $request->no_of_question;
                $insertData['marks_per_question'] = $request->marks_per_question;
                $insertData['time_per_question'] = $request->time_per_question;
                $insertData['totals_marks_for_exam'] = $request->no_of_question * $request->marks_per_question;
                $insertData['total_time_for_exam'] = $request->no_of_question * $request->time_per_question;
                $insertData['negative_marking_applicable'] = $request->negative_marking_applicable;
                $insertData['negative_marking_per_question'] = $request->negative_marking_per_question;
            }
            
            $insertData['exam_instructions'] = $request->exam_instructions;
            $insertData['description'] = $request->description;
            $insertData['result_title'] = $request->result_title;
            $insertData['result_description'] = $request->result_description;
            $insertData['result_declaration'] = $request->result_declaration;
            $insertData['price'] = $request->price;
            $insertData['dis_price'] = $request->dis_price;
            $insertData['tax'] = $request->tax;
            $insertData['is_in_footer'] = ($request->is_in_footer)?$request->is_in_footer:0;
            $insertData['is_trending'] = ($request->is_trending)?$request->is_trending:0;
            $insertData['status'] = ($request->status)?$request->status:0;

            $exam = Exam::create($insertData);

            if ($request->language_name && is_array($request->language_name)) {
                foreach ($request->language_name as $languageName) {
                    if (!empty($languageName)) {
                        ExamLanguage::create([
                            'exam_id' => $exam->id,
                            'language_id' => $languageName
                        ]);
                    }
                }
            }

            if ($request->location_name && is_array($request->location_name)) {
                foreach ($request->location_name as $locationName) {
                    if (!empty($locationName)) {
                        ExamLocation::create([
                            'exam_id' => $exam->id,
                            'location_id' => $locationName
                        ]);
                    }
                }
            }

            if ($request->date_name && is_array($request->date_name)) {
                foreach ($request->date_name as $dateName) {
                    if (!empty($dateName)) {
                        ExamDate::create([
                            'exam_id' => $exam->id,
                            'date_name' => $dateName
                        ]);
                    }
                }
            }
        
            toastr()->success('Offline Exam added successfully.');
            return redirect()->route('admin.offline_exam');
        }else{
            toastr()->warning('Offline Exam already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Exam::where('id',$request->id)->first();
        $data['exam_language_list'] = ExamLanguage::where('exam_id',$request->id)->get();
        $data['exam_location_list'] = ExamLocation::where('exam_id',$request->id)->get();
        $data['exam_date_list'] = ExamDate::where('exam_id',$request->id)->get();
        $data['language_list'] = Language::where('status',1)->get();
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['location_list'] = Location::where('status',1)->get();
        $data['question_paper_list'] = Question_paper::where('status',1)->where('is_deleted',0)->get();
        return view('admin.offline_exam.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'exam_code' => 'required',
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Exam::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            if ($image = $request->file('exam_logo')){
                $insertData['exam_logo'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/exam/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['exam_logo']);


                $destinationPath = public_path('/uploads/exam');
                $image->move($destinationPath, $insertData['exam_logo']);

                if($loginCheck->exam_logo){
                    if (file_exists(public_path('uploads/exam/'.$loginCheck->exam_logo))) {
                        unlink(public_path('uploads/exam/'.$loginCheck->exam_logo));
                    }
                    if (file_exists(public_path('uploads/exam/thumbnail/'.$loginCheck->exam_logo))) {
                        unlink(public_path('uploads/exam/thumbnail/'.$loginCheck->exam_logo));
                    }
                }
            }
            if ($image = $request->file('landing_icon')){
                $insertData['landing_icon'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/exam/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['landing_icon']);


                $destinationPath = public_path('/uploads/exam');
                $image->move($destinationPath, $insertData['landing_icon']);

                if($loginCheck->landing_icon){
                    if (file_exists(public_path('uploads/exam/'.$loginCheck->landing_icon))) {
                        unlink(public_path('uploads/exam/'.$loginCheck->landing_icon));
                    }
                    if (file_exists(public_path('uploads/exam/thumbnail/'.$loginCheck->landing_icon))) {
                        unlink(public_path('uploads/exam/thumbnail/'.$loginCheck->landing_icon));
                    }
                }
            }
            
            $insertData['exam_code'] = $request->exam_code;
            $insertData['exam_date'] = $request->exam_date;
            $insertData['exam_time'] = $request->exam_time;
            $insertData['location_id'] = $request->location_id;
            $insertData['name'] = $request->name;
            $insertData['question_type'] = $request->question_type;

            if($request->question_paper_id){
                $insertData['question_paper_id'] = $request->question_paper_id;
            }else{
                $insertData['no_of_question'] = $request->no_of_question;
                $insertData['marks_per_question'] = $request->marks_per_question;
                $insertData['time_per_question'] = $request->time_per_question;
                $insertData['totals_marks_for_exam'] = $request->no_of_question * $request->marks_per_question;
                $insertData['total_time_for_exam'] = $request->no_of_question * $request->time_per_question;
                $insertData['negative_marking_applicable'] = $request->negative_marking_applicable;
                $insertData['negative_marking_per_question'] = $request->negative_marking_per_question;
            }
            
            $insertData['exam_instructions'] = $request->exam_instructions;
            $insertData['description'] = $request->description;
            $insertData['result_title'] = $request->result_title;
            $insertData['result_description'] = $request->result_description;
            $insertData['result_declaration'] = $request->result_declaration;
            $insertData['price'] = $request->price;
            $insertData['dis_price'] = $request->dis_price;
            $insertData['tax'] = $request->tax;
            $insertData['status'] = ($request->status)?$request->status:0;
            $insertData['is_in_footer'] = ($request->is_in_footer)?$request->is_in_footer:0;
            $insertData['is_trending'] = ($request->is_trending)?$request->is_trending:0;

            $loginCheck->update($insertData);
            toastr()->success('Offline Exam updated successfully.');
            return redirect()->route('admin.offline_exam');
        }else{
            toastr()->warning('Offline Exam already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        $exam = Exam::where('id',$id)->first();
        // dd($exam);
        if($exam){
            Exam::where('id',$id)->delete();
            ExamLanguage::where('exam_id',$id)->delete();
            ExamDate::where('exam_id',$id)->delete();
            ExamLocation::where('exam_id',$id)->delete();
            Exam_status::where('exam_id',$id)->delete();
            Exam_user::where('exam_id',$id)->delete();
            Exam_result::where('exam_id',$id)->delete();
            Offline_exam_question::where('exam_id',$id)->delete();
            Exam_subject::where('exam_id',$id)->delete();
            Exam_question::where('exam_id',$id)->delete();
            Exam_mistake_input::where('exam_id',$id)->delete();
            Exam_question::where('exam_id',$id)->delete();
        }
        
        toastr()->success('Offline Exam deleted successfully.');
        return redirect()->route('admin.offline_exam');
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

        toastr()->success('Offline Exam status change successfully.');
        return redirect()->route('admin.offline_exam');
    }

    public function view(Request $request){
        $id = $request->id;
        $data = [];
        $data['list'] = Exam_status::select(['exam_statuses.type','exam_statuses.created_at','users.first_name','users.last_name','users.email_id'])
                        ->leftJoin('users', 'exam_statuses.user_id', '=', 'users.id')
                        ->where('exam_statuses.exam_id',$id)->get();
        return view('admin.offline_exam.view',$data);
    }

    public function end(Request $request){
        $id = $request->id;

        $loginCheck = Exam::select(['exams.*','question_papers.totals_marks_for_exam as question_paper_totals_marks_for_exam'])
                        ->leftJoin('question_papers', 'exams.question_paper_id', '=', 'question_papers.id')
                        ->where('exams.id',$request->id)->first();

        if($loginCheck){

            if(!$loginCheck->totals_marks_for_exam){
                $loginCheck->totals_marks_for_exam = $loginCheck->question_paper_totals_marks_for_exam;
            }
            $loginCheck->update(['is_ended'=>1,'ended_date'=>Carbon::now()]);
            $exam_user = Exam_user::select(['exam_users.*','users.rank','users.total_mark','users.mark'])
                                ->leftJoin('users', 'exam_users.user_id', '=', 'users.id')
                                ->where('exam_users.exam_id',$loginCheck->id)->where('exam_users.user_id',98)->orderBy('exam_users.total_number','DESC')->get();
            // dd($exam_user);
            $rank = 1;
            foreach($exam_user as $value){
                Exam_user::where('id',$value->id)->update(["rank"=>$rank,"total_mark"=>$loginCheck->totals_marks_for_exam]);
                $mark = $value->total_number + $value->mark;
                $total_mark = $value->total_mark + $loginCheck->totals_marks_for_exam;

                if($total_mark){
                    $mark_avg = $mark / $total_mark * 100;
                }else{
                    $mark_avg = 0;
                }
                
                // dd(["total_mark"=>$total_mark,"mark"=>$mark,"mark_avg"=>$mark_avg]);

                User::where('id',$value->user_id)->update(["total_mark"=>$total_mark,"mark"=>$mark,"mark_avg"=>$mark_avg]);
                $rank = $rank + 1;
            }

            $users = User::orderBy('mark_avg','DESC')->get();

            $rank = 1;
            foreach ($users as $key => $value) {
                User::where('id',$value->id)->update(["rank"=>$rank]);
                $rank = $rank + 1;
            }
        }

        toastr()->success('Offline Exam ended successfully.');
        return redirect()->route('admin.offline_exam');
    }

    public function question(Request $request){

        $data = [];
        $data['details'] = Exam::where('id',$request->id)->first();

        if($data['details']->question_paper_id){
            $question_paper_id = $data['details']->question_paper_id;
            $data = [];
            $data['details'] = Question_paper::where('id',$question_paper_id)->first();
            $data['list'] = Question_paper_question::select(['questions.*','question_paper_questions.question_paper_id','question_paper_questions.id as question_paper_question_id','question_paper_questions.question_paper_subject_id','subjects.name as subject_name','chapters.name as chapter_name','topics.name as topic_name','sub_topics.name as sub_topic_name'])
                ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                ->leftJoin('subjects', 'questions.subject_id', '=', 'subjects.id')
                ->leftJoin('chapters', 'questions.chapter_id', '=', 'chapters.id')
                ->leftJoin('topics', 'questions.topic_id', '=', 'topics.id')
                ->leftJoin('sub_topics', 'questions.sub_topic_id', '=', 'sub_topics.id')
                ->where('question_paper_questions.question_paper_id',$question_paper_id)->get();

            $question_ids = [];
            foreach ($data['list'] as $key => $value) {
                $question = Question_detail::where("question_id",$value->id)->where("language_id",1)->first();
                $data['list'][$key]->question_text = $question->question_text;
                $data['list'][$key]->question_image = $question->question_image;

                array_push($question_ids, $value->id);
            }

            $data['question_list'] = Question_detail::select(['question_id','question_text'])->whereNotIn('question_id',$question_ids)->where('language_id',1)->get();

            return view('admin.offline_exam.question',$data);
        }else{
            $data['list'] = Offline_exam_question::select(['offline_exam_questions.*','subjects.name as subject_name','chapters.name as chapter_name','topics.name as topic_name','sub_topics.name as sub_topic_name'])
                        ->leftJoin('subjects', 'offline_exam_questions.subject_id', '=', 'subjects.id')
                        ->leftJoin('chapters', 'offline_exam_questions.chapter_id', '=', 'chapters.id')
                        ->leftJoin('topics', 'offline_exam_questions.topic_id', '=', 'topics.id')
                        ->leftJoin('sub_topics', 'offline_exam_questions.sub_topic_id', '=', 'sub_topics.id')
                        ->where('offline_exam_questions.exam_id',$request->id)->get();

            return view('admin.offline_exam.question_uploaded',$data);
        }
            
    }

    public function download_question(Request $request){
        $data = [];
        $data['details'] = Exam::where('id',$request->id)->first();
        $data['subject_list'] = Exam_subject::select(['exam_subjects.*','subjects.name as subject_name'])
                                    ->leftJoin('subjects', 'exam_subjects.subject_id', '=', 'subjects.id')
                                    ->where('exam_id',$request->id)->get();

        foreach($data['subject_list'] as $key => $value){
            $data['subject_list'][$key]->question_list = Exam_question::select(['questions.*','exam_questions.exam_id','exam_questions.exam_subject_id'])
            ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
            ->where('exam_questions.exam_id',$request->id)
            ->where('exam_questions.exam_subject_id',$value->id)
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

        $pdf = PDF::loadView('template.exam_question_pdf', $data);

        return $pdf->stream('exam_que.pdf');
    }

    public function download_answer(Request $request){
        $data = [];
        $data['details'] = Exam::where('id',$request->id)->first();

        $data['subject_list'] = Exam_subject::select(['exam_subjects.*','subjects.name as subject_name'])
                                    ->leftJoin('subjects', 'exam_subjects.subject_id', '=', 'subjects.id')
                                    ->where('exam_id',$request->id)->get();

        foreach($data['subject_list'] as $key => $value){
            $data['subject_list'][$key]->question_list = Exam_question::select(['questions.*','exam_questions.exam_id','exam_questions.exam_subject_id'])
            ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
            ->where('exam_questions.exam_id',$request->id)
            ->where('exam_questions.exam_subject_id',$value->id)
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

        return $pdf->download('exam_que.pdf');
    }

    public function result(Request $request){

        $data = [];
        $data['details'] = Exam::where('id',$request->id)->first();
        $data['list'] = Exam_user::select(['exam_users.*','users.first_name','users.last_name','users.email_id'])
            ->leftJoin('users', 'exam_users.user_id', '=', 'users.id')
            ->where('exam_users.exam_id',$request->id)->get();

        
        // dd($data['list'][0]);
        return view('admin.offline_exam.exam_result',$data);
    }

    
    public function result_details(Request $request){

        $data = [];
        $data['offline_exam'] = Exam_user::select(['exams.*','exam_users.id as user_exam_id','exam_users.total_answer','exam_users.total_right_answer','exam_users.total_number'])
                                ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                                ->where('exam_users.id',$request->id)->first();
                                
        $data['list'] = Question_paper_question::select(['question_paper_questions.*','questions.answer','questions.solution_video_link as video_link'])
                                    ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                    ->where('question_paper_id',$data['offline_exam']->question_paper_id)->get();

        foreach($data['list'] as $key => $value){
            $exam_result = Exam_result::where('user_id',$request->user_id)->where('exam_id',$data['offline_exam']->id)->where('exam_question_id',$value->id)->first();
            $data['list'][$key]->right_answer = ($exam_result)?$exam_result->answer:'';
            
            $question_detail = Question_detail::where('language_id',1)->where('question_id',$value->question_id)->first();
            $data['list'][$key]->question_text = ($question_detail)?$question_detail->question_text:'';

        }
        // dd($data['list']);
        return view('admin.offline_exam.result_details',$data);
    }

    public function change_question(Request $request){
        $data = [];
        $exam_id = $request->exam_id;
        $offline_exam_question_id = $request->offline_exam_question_id;
        $question_text = $request->question_text;
        $answer = $request->answer;
        $video_link = $request->video_link;

        $question_paper_question = Offline_exam_question::where('id',$offline_exam_question_id)->first();


        $question_paper_question->update(['question_text'=>$question_text,'answer'=>$answer,'video_link'=>$video_link]);

        toastr()->success('Question change successfully.');
        return redirect()->route('admin.offline_exam.question',['id'=>$exam_id]);
    }
    
}
