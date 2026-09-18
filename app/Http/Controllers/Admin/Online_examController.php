<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Exam_subject;
use App\Models\Exam_question;
use App\Models\Location;
use App\Models\Question;
use App\Models\Question_detail;
use App\Models\Question_paper;
use App\Models\Exam_user;

class Online_examController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Exam::where('type',1)->where('is_deleted',0)->get();
        return view('admin.online_exam.list',$data);
    }


    public function add(){
        $data = [];
        $data['location_list'] = Location::where('status',1)->get();
        $data['subject_list'] = Subject::select(['id','name'])->where('status',1)->get();
        $data['question_paper_list'] = Question_paper::where('status',1)->get();
        return view('admin.online_exam.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        // dd($input);
        $loginCheck = Exam::where('exam_code',$request->exam_code)->first();

        if (!$loginCheck){
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
            $insertData['type'] = 1;
            $insertData['exam_code'] = $request->exam_code;
            $insertData['name'] = $request->name;
            $insertData['exam_date'] = $request->exam_date;
            $insertData['exam_time'] = $request->exam_time;
            $insertData['price'] = $request->price;
            $insertData['dis_price'] = $request->dis_price;
            $insertData['tax'] = $request->tax;
            $insertData['question_paper_id'] = $request->question_paper_id;
            $insertData['exam_instructions'] = $request->exam_instructions;
            $insertData['description'] = $request->description;
            $insertData['result_title'] = $request->result_title;
            $insertData['result_description'] = $request->result_description;
            $insertData['result_declaration'] = $request->result_declaration;
            $insertData['status'] = $request->status;
            $insertData['is_in_footer'] = ($request->is_in_footer)?$request->is_in_footer:0;
            $insertData['is_trending'] = ($request->is_trending)?$request->is_trending:0;

            $exam = Exam::create($insertData);

            toastr()->success('Online Exam added successfully.');
            return redirect()->route('admin.online_exam');
        }else{
            toastr()->warning('Online Exam already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['details'] = Exam::where('id',$request->id)->first();
        $data['question_paper_list'] = Question_paper::where('status',1)->get();
        return view('admin.online_exam.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Exam::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_icon')){
                $saveData['profile_icon'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/exam/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$saveData['profile_icon']);


                $destinationPath = public_path('/uploads/exam');
                $image->move($destinationPath, $saveData['profile_icon']);

                if($loginCheck->profile_icon){
                    if (file_exists(public_path('uploads/exam/'.$loginCheck->profile_icon))) {
                        unlink(public_path('uploads/exam/'.$loginCheck->profile_icon));
                    }
                    if (file_exists(public_path('uploads/exam/thumbnail/'.$loginCheck->profile_icon))) {
                        unlink(public_path('uploads/exam/thumbnail/'.$loginCheck->profile_icon));
                    }
                }
            }
            if ($image = $request->file('landing_icon')){
                $saveData['landing_icon'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/exam/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$saveData['landing_icon']);


                $destinationPath = public_path('/uploads/exam');
                $image->move($destinationPath, $saveData['landing_icon']);

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
            $insertData['name'] = $request->name;
            $insertData['exam_date'] = $request->exam_date;
            $insertData['exam_time'] = $request->exam_time;
            $insertData['price'] = $request->price;
            $insertData['dis_price'] = $request->dis_price;
            $insertData['tax'] = $request->tax;
            $insertData['question_paper_id'] = $request->question_paper_id;
            $insertData['exam_instructions'] = $request->exam_instructions;
            $insertData['description'] = $request->description;
            $insertData['result_title'] = $request->result_title;
            $insertData['result_description'] = $request->result_description;
            $insertData['result_declaration'] = $request->result_declaration;
            $insertData['is_in_footer'] = ($request->is_in_footer)?$request->is_in_footer:0;
            $insertData['is_trending'] = ($request->is_trending)?$request->is_trending:0;
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Online Exam updated successfully.');
            return redirect()->route('admin.online_exam');
        }else{
            toastr()->warning('Online Exam already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        $exam = Exam::where('id',$id)->first();
        // dd($exam);
        if($exam){
            $exam->update(['is_deleted'=>1]);
        }
        
        toastr()->success('Online Exam deleted successfully.');
        return redirect()->route('admin.online_exam');
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

        toastr()->success('Online Exam status change successfully.');
        return redirect()->route('admin.online_exam');
    }

    public function end(Request $request){
        $id = $request->id;

        $loginCheck = Exam::select(['exams.*','question_papers.totals_marks_for_exam'])
                        ->leftJoin('question_papers', 'exams.question_paper_id', '=', 'question_papers.id')
                        ->where('exams.id',$request->id)->first();

        if($loginCheck){

            $loginCheck->update(['is_ended'=>1,'ended_date'=>Carbon::now()]);

            $exam_user = Exam_user::select(['exam_users.*','users.rank','users.total_mark','users.mark'])
                                ->leftJoin('users', 'exam_users.user_id', '=', 'users.id')
                                ->where('exam_users.exam_id',$loginCheck->id)->orderBy('exam_users.total_number','DESC')->get();
            // dd($exam_user);
            $rank = 1;
            foreach($exam_user as $value){
                Exam_user::where('id',$value->id)->update(["rank"=>$rank,"total_mark"=>$loginCheck->totals_marks_for_exam]);
                $mark = $value->total_number + $value->mark;
                $total_mark = $value->total_mark + $loginCheck->totals_marks_for_exam;

                $mark_avg = $mark / $total_mark * 100;

                User::where('id',$value->user_id)->update(["total_mark"=>$total_mark,"mark"=>$mark,"mark_avg"=>$mark_avg]);
                $rank = $rank + 1;
            }

            $users = User::orderBy('mark_avg','DESC')->get();

            $rank = 1;
            foreach ($users as $key => $value) {
                User::where('id',$value->id)->update(["rank"=>$rank]);
                $rank = $rank + 1;
            }

            toastr()->success('Online Exam ended successfully.');
        }else{
            toastr()->success('Online Exam already ended.');
        }

        
        return redirect()->route('admin.online_exam');
    }

    public function question(Request $request){

        $data = [];
        $data['details'] = Exam::where('id',$request->id)->first();
        $data['list'] = Exam_question::select(['questions.*','exam_questions.exam_id','exam_questions.exam_subject_id','subjects.name as subject_name'])
            ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
            ->leftJoin('subjects', 'questions.subject_id', '=', 'subjects.id')
            ->where('exam_questions.exam_id',$request->id)->get();

        foreach ($data['list'] as $key => $value) {
            $question = Question_detail::where("question_id",$value->id)->where("language_id",1)->first();
            $data['list'][$key]->question_text = $question->question_text;
            $data['list'][$key]->question_image = $question->question_image;
        }
        // dd($data['list']);
        return view('admin.online_exam.question',$data);
    }

    public function result(Request $request){

        $data = [];
        $data['details'] = Exam::where('id',$request->id)->first();
        $data['list'] = Offline_exam_user::select(['offline_exam_users.*','users.first_name','users.last_name','users.email_id'])
            ->leftJoin('users', 'offline_exam_users.user_id', '=', 'users.id')
            ->where('offline_exam_users.exam_id',$request->id)->get();

        
        // dd($data['list'][0]);
        return view('admin.online_exam.exam_result',$data);
    }

    public function result_details(Request $request){

        $data = [];
        $data['details'] = Exam::where('id',$request->id)->first();
        $data['list'] = Offline_exam_question_result::select(['offline_exam_question_results.*','offline_exam_questions.question_id','offline_exam_questions.video_link','offline_exam_questions.answer as right_answer'])
            ->leftJoin('offline_exam_questions', 'offline_exam_question_results.offline_exam_question_id', '=', 'offline_exam_questions.id')
            ->where('offline_exam_question_results.exam_id',$request->exam_id)
            ->where('offline_exam_question_results.offline_exam_user_id',$request->id)
            ->get();

        
        // dd($data['list']);
        return view('admin.online_exam.result_details',$data);
    }
    
}
