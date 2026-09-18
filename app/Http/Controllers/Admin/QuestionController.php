<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use ZipArchive;
use Illuminate\Support\Str;
use App\Imports\ExcelToDbImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Question;
use App\Models\Question_detail;
use App\Models\Chapter;
use App\Models\Subject;
use App\Models\Question_exam;
use App\Models\Question_source;
use App\Models\Language;
use App\Models\Source;
use App\Models\Topic;
use App\Models\Sub_topic;
use App\Models\Question_type;

class QuestionController extends Controller
{
    public function list(Request $request){
        $data = [];

        $data['subject_id'] = $request->subject_id;
        $data['chapter_id'] = $request->chapter_id;
        $data['topic_id'] = $request->topic_id;
        $data['sub_topic_id'] = $request->sub_topic_id;

        $language_id = getDefaultLanguage();
        $data['list'] = Question_detail::select(['questions.*','question_details.question_text','question_details.question_image',
                                                    'chapters.name as chapter_name','subjects.name as subject_name','sources.name as source_name',
                                                    'question_details.is_option1_image','question_details.option1','question_details.is_option2_image',
                                                    'question_details.option2','question_details.is_option3_image','question_details.option3',
                                                    'question_details.is_option4_image','question_details.option4'
                                                ])
                        ->leftJoin('questions', 'questions.id', '=', 'question_details.question_id')
                        ->leftJoin('subjects', 'questions.subject_id', '=', 'subjects.id')
                        ->leftJoin('chapters', 'questions.chapter_id', '=', 'chapters.id')
                        ->leftJoin('sources', 'questions.source_id', '=', 'sources.id')
                        ->where('questions.is_deleted',0)
                        ->where('question_details.language_id',$language_id);

        $data['chapter_list'] = [];
        $data['topic_list'] = [];
        $data['sub_topic_list'] = [];

        if($request->subject_id){ 
            $data['list'] = $data['list']->where('questions.subject_id',$request->subject_id);
            $data['chapter_list'] = Chapter::where('subject_id',$request->subject_id)->where('status',1)->get();
        }

        if($request->chapter_id){ 
            $data['list'] = $data['list']->where('questions.chapter_id',$request->chapter_id);
            $data['topic_list'] = Topic::where('chapter_id',$request->chapter_id)->where('status',1)->get();
        }

        if($request->topic_id){ 
            $data['list'] = $data['list']->where('questions.topic_id',$request->topic_id);
            $data['sub_topic_list'] = Sub_topic::where('topic_id',$request->topic_id)->where('status',1)->get();
        }

        if($request->sub_topic_id){ 
            $data['list'] = $data['list']->where('questions.sub_topic_id',$request->sub_topic_id);
        }

        if(session()->get('admmin_is_super') == 'T'){
            $data['list'] = $data['list']->where('questions.subject_id',session()->get('admmin_subject_id'));
            $data['subject_list'] = Subject::where('id',session()->get('admmin_subject_id'))->where('status',1)->get();
        }else{
            $data['subject_list'] = Subject::where('status',1)->get();
        }

        $data['list'] = $data['list']->orderBy('id','DESC')->paginate(25);
        return view('admin.question.list',$data);
    }

    public function add(){
        $data = [];
        $data['question_source_list'] = Question_source::where('status',1)->get();
        $data['question_exam_list'] = Question_exam::where('status',1)->get();
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['language_list'] = Language::where('status',1)->get();
        $data['question_type_list'] = Question_type::where('status',1)->get();
        return view('admin.question.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'subject_id' => 'required'
                        ]);

        $input = $request->all();
        // dd($input);
        // $loginCheck = Ranker::where('email',$request->email)->first();

        if (1){
            $insertData = [];
            
            $insertData['subject_id'] = $request->subject_id;
            $insertData['chapter_id'] = $request->chapter_id;
            $insertData['source_id'] = $request->source_id;
            $insertData['topic_id'] = $request->topic_id;
            $insertData['sub_topic_id'] = $request->sub_topic_id;
            $insertData['question_type_id'] = $request->question_type_id;
            $insertData['difficulty_level'] = $request->difficulty_level;
            $insertData['question_source_id'] = $request->question_source_id;
            $insertData['solution_video_link'] = $request->solution_video_link;
            $insertData['answer'] = $request->answer;
            $insertData['status'] = $request->status;
            $insertData['administrator_id'] = session()->get('adminAuth');
            $insertData['is_deleted'] = 0;
            
            $question = Question::create($insertData);
            
            $language_list = Language::where('status',1)->get();

            foreach($language_list as $value){
                $insertData = [];
                $insertData['question_text'] = $request->question_text[$value->id];
                if(isset($request->file('question_image')[$value->id])){
                    if ($image = $request->file('question_image')[$value->id]){
                        $insertData['question_image'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                        $destinationPath = public_path('/uploads/question');
                        $image->move($destinationPath, $insertData['question_image']);
                    }
                }
                    

                $insertData['is_option1_image'] = $request->is_option1_image[$value->id];
                if($insertData['is_option1_image'] == 1){
                    if ($image = $request->file('option1_image')[$value->id]){
                        $insertData['option1'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                        $destinationPath = public_path('/uploads/question');
                        $image->move($destinationPath, $insertData['option1']);
                    }
                }else{
                    $insertData['option1'] = $request->option1_text[$value->id];
                }

                $insertData['is_option2_image'] = $request->is_option2_image[$value->id];
                if($insertData['is_option2_image'] == 1){
                    if ($image = $request->file('option2_image')[$value->id]){
                        $insertData['option2'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                        $destinationPath = public_path('/uploads/question');
                        $image->move($destinationPath, $insertData['option2']);
                    }
                }else{
                    $insertData['option2'] = $request->option2_text[$value->id];
                }

                $insertData['is_option3_image'] = $request->is_option3_image[$value->id];
                if($insertData['is_option3_image'] == 1){
                    if ($image = $request->file('option3_image')[$value->id]){
                        $insertData['option3'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                        $destinationPath = public_path('/uploads/question');
                        $image->move($destinationPath, $insertData['option3']);
                    }
                }else{
                    $insertData['option3'] = $request->option3_text[$value->id];
                }

                $insertData['is_option4_image'] = $request->is_option4_image[$value->id];
                if($insertData['is_option4_image'] == 1){
                    if ($image = $request->file('option4_image')[$value->id]){
                        $insertData['option4'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                        $destinationPath = public_path('/uploads/question');
                        $image->move($destinationPath, $insertData['option4']);
                    }
                }else{
                    $insertData['option4'] = $request->option4_text[$value->id];
                }

                $insertData['answer_behavior_tag1'] = $request->answer_behavior_tag1[$value->id];
                $insertData['answer_behavior_tag2'] = $request->answer_behavior_tag2[$value->id];
                $insertData['answer_behavior_tag3'] = $request->answer_behavior_tag3[$value->id];
                $insertData['answer_behavior_tag4'] = $request->answer_behavior_tag4[$value->id];

                $insertData['solution'] = $request->solution[$value->id];
                $insertData['question_id'] = $question->id;
                $insertData['language_id'] = $value->id;
                $insertData['status'] = $request->status;
                Question_detail::create($insertData);
            }

            toastr()->success('Question added successfully.');
            return redirect()->route('admin.question');
        }else{
            toastr()->warning('Question already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Question::where('id',$request->id)->first();
        $data['question_source_list'] = Question_source::where('status',1)->get();
        $data['question_exam_list'] = Question_exam::where('status',1)->get();
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['chapter_list'] = Chapter::where('subject_id',$data['details']->subject_id)->where('status',1)->get();
        $data['topic_list'] = Topic::where('chapter_id',$data['details']->chapter_id)->where('status',1)->get();
        $data['sub_topic_list'] = Sub_topic::where('topic_id',$data['details']->topic_id)->where('status',1)->get();
        $data['source_list'] = Source::where('subject_id',$data['details']->subject_id)->where('status',1)->get();
        $data['language_list'] = Language::where('status',1)->get();
        $data['question_type_list'] = Question_type::where('status',1)->get();

        foreach($data['language_list'] as $key => $value){
            $question_detail = Question_detail::where('question_id',$request->id)->where('language_id',$value->id)->first();
            $data['language_list'][$key]->question_detail = $question_detail;
            if($question_detail){
                $data['language_list'][$key]->question_text = $question_detail->question_text;
                $data['language_list'][$key]->question_image = $question_detail->question_image;
                $data['language_list'][$key]->option1 = $question_detail->option1;
                $data['language_list'][$key]->is_option1_image = $question_detail->is_option1_image;
                $data['language_list'][$key]->option2 = $question_detail->option2;
                $data['language_list'][$key]->is_option2_image = $question_detail->is_option2_image;
                $data['language_list'][$key]->option3 = $question_detail->option3;
                $data['language_list'][$key]->is_option3_image = $question_detail->is_option3_image;
                $data['language_list'][$key]->option4 = $question_detail->option4;
                $data['language_list'][$key]->is_option4_image = $question_detail->is_option4_image;
                $data['language_list'][$key]->solution = $question_detail->solution;
                $data['language_list'][$key]->answer_behavior_tag1 = $question_detail->answer_behavior_tag1;
                $data['language_list'][$key]->answer_behavior_tag2 = $question_detail->answer_behavior_tag2;
                $data['language_list'][$key]->answer_behavior_tag3 = $question_detail->answer_behavior_tag3;
                $data['language_list'][$key]->answer_behavior_tag4 = $question_detail->answer_behavior_tag4;
            }else{
                $data['language_list'][$key]->question_text = "";
                $data['language_list'][$key]->question_image = "";
                $data['language_list'][$key]->option1 = "";
                $data['language_list'][$key]->is_option1_image = "";
                $data['language_list'][$key]->option2 = "";
                $data['language_list'][$key]->is_option2_image = "";
                $data['language_list'][$key]->option3 = "";
                $data['language_list'][$key]->is_option3_image = "";
                $data['language_list'][$key]->option4 = "";
                $data['language_list'][$key]->is_option4_image = "";
                $data['language_list'][$key]->solution = "";
                $data['language_list'][$key]->answer_behavior_tag1 = "";
                $data['language_list'][$key]->answer_behavior_tag2 = "";
                $data['language_list'][$key]->answer_behavior_tag3 = "";
                $data['language_list'][$key]->answer_behavior_tag4 = "";
            }
        }

        return view('admin.question.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'subject_id' => 'required'
                        ]);

        $input = $request->all();
        // dd($input);
        $loginCheck = Question::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            
            $insertData['subject_id'] = $request->subject_id;
            $insertData['chapter_id'] = $request->chapter_id;
            $insertData['source_id'] = $request->source_id;
            $insertData['topic_id'] = $request->topic_id;
            $insertData['sub_topic_id'] = $request->sub_topic_id;
            $insertData['question_type_id'] = $request->question_type_id;
            $insertData['difficulty_level'] = $request->difficulty_level;
            $insertData['question_source_id'] = $request->question_source_id;
            $insertData['solution_video_link'] = $request->solution_video_link;
            $insertData['answer'] = $request->answer;
            $insertData['status'] = $request->status;
            
            $loginCheck->update($insertData);

            $language_list = Language::where('status',1)->get();

            foreach($language_list as $value){
                $question_detail = Question_detail::where('question_id',$request->id)->where('language_id',$value->id)->first();
                if($question_detail){
                    $insertData = [];
                    $insertData['question_text'] = $request->question_text[$value->id];
                    if(isset($request->file('question_image')[$value->id])){
                        if ($image = $request->file('question_image')[$value->id]){
                            $insertData['question_image'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                            $destinationPath = public_path('/uploads/question');
                            $image->move($destinationPath, $insertData['question_image']);
                            if($question_detail->question_image){
                                if (file_exists(public_path('uploads/question/'.$question_detail->question_image))) {
                                    unlink(public_path('uploads/question/'.$question_detail->question_image));
                                }
                            }
                        }
                    }
                        

                    $insertData['is_option1_image'] = $request->is_option1_image[$value->id];
                    if($insertData['is_option1_image'] == 1){
                        if(isset($request->file('option1_image')[$value->id])){
                            if ($image = $request->file('option1_image')[$value->id]){
                                $insertData['option1'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                                $destinationPath = public_path('/uploads/question');
                                $image->move($destinationPath, $insertData['option1']);
                                if($question_detail->option1){
                                    if (file_exists(public_path('uploads/question/'.$question_detail->option1))) {
                                        unlink(public_path('uploads/question/'.$question_detail->option1));
                                    }
                                }
                            }
                        }
                    }else{
                        $insertData['option1'] = $request->option1_text[$value->id];
                    }

                    $insertData['is_option2_image'] = $request->is_option2_image[$value->id];
                    if($insertData['is_option2_image'] == 1){
                        if(isset($request->file('option2_image')[$value->id])){
                            if ($image = $request->file('option2_image')[$value->id]){
                                $insertData['option2'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                                $destinationPath = public_path('/uploads/question');
                                $image->move($destinationPath, $insertData['option2']);
                                if($question_detail->option2){
                                    if (file_exists(public_path('uploads/question/'.$question_detail->option2))) {
                                        unlink(public_path('uploads/question/'.$question_detail->option2));
                                    }
                                }
                            }
                        }
                    }else{
                        $insertData['option2'] = $request->option2_text[$value->id];
                    }

                    $insertData['is_option3_image'] = $request->is_option3_image[$value->id];
                    if($insertData['is_option3_image'] == 1){
                        if(isset($request->file('option3_image')[$value->id])){
                            if ($image = $request->file('option3_image')[$value->id]){
                                $insertData['option3'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                                $destinationPath = public_path('/uploads/question');
                                $image->move($destinationPath, $insertData['option3']);
                                if($question_detail->option3){
                                    if (file_exists(public_path('uploads/question/'.$question_detail->option3))) {
                                        unlink(public_path('uploads/question/'.$question_detail->option3));
                                    }
                                }
                            }
                        }
                    }else{
                        $insertData['option3'] = $request->option3_text[$value->id];
                    }

                    $insertData['is_option4_image'] = $request->is_option4_image[$value->id];
                    if($insertData['is_option4_image'] == 1){
                        if(isset($request->file('option4_image')[$value->id])){
                            if ($image = $request->file('option4_image')[$value->id]){
                                $insertData['option4'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                                $destinationPath = public_path('/uploads/question');
                                $image->move($destinationPath, $insertData['option4']);
                                if($question_detail->option4){
                                    if (file_exists(public_path('uploads/question/'.$question_detail->option4))) {
                                        unlink(public_path('uploads/question/'.$question_detail->option4));
                                    }
                                }
                            }
                        }
                    }else{
                        $insertData['option4'] = $request->option4_text[$value->id];
                    }

                    $insertData['solution'] = $request->solution[$value->id];

                    $insertData['answer_behavior_tag1'] = $request->answer_behavior_tag1[$value->id];
                    $insertData['answer_behavior_tag2'] = $request->answer_behavior_tag2[$value->id];
                    $insertData['answer_behavior_tag3'] = $request->answer_behavior_tag3[$value->id];
                    $insertData['answer_behavior_tag4'] = $request->answer_behavior_tag4[$value->id];

                    $question_detail->update($insertData);
                }else{
                    $insertData = [];
                    $insertData['question_text'] = $request->question_text[$value->id];
                    if(isset($request->file('question_image')[$value->id])){
                        if ($image = $request->file('question_image')[$value->id]){
                            $insertData['question_image'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                            $destinationPath = public_path('/uploads/question');
                            $image->move($destinationPath, $insertData['question_image']);
                        }
                    }
                        

                    $insertData['is_option1_image'] = $request->is_option1_image[$value->id];
                    if($insertData['is_option1_image'] == 1){
                        if ($image = $request->file('option1')[$value->id]){
                            $insertData['option1'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                            $destinationPath = public_path('/uploads/question');
                            $image->move($destinationPath, $insertData['option1']);
                        }
                    }else{
                        $insertData['option1'] = $request->option1[$value->id];
                    }

                    $insertData['is_option2_image'] = $request->is_option2_image[$value->id];
                    if($insertData['is_option2_image'] == 1){
                        if ($image = $request->file('option2')[$value->id]){
                            $insertData['option2'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                            $destinationPath = public_path('/uploads/question');
                            $image->move($destinationPath, $insertData['option2']);
                        }
                    }else{
                        $insertData['option2'] = $request->option2[$value->id];
                    }

                    $insertData['is_option3_image'] = $request->is_option3_image[$value->id];
                    if($insertData['is_option3_image'] == 1){
                        if ($image = $request->file('option3')[$value->id]){
                            $insertData['option3'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                            $destinationPath = public_path('/uploads/question');
                            $image->move($destinationPath, $insertData['option3']);
                        }
                    }else{
                        $insertData['option3'] = $request->option3[$value->id];
                    }

                    $insertData['is_option4_image'] = $request->is_option4_image[$value->id];
                    if($insertData['is_option4_image'] == 1){
                        if ($image = $request->file('option4')[$value->id]){
                            $insertData['option4'] = uniqid()."_".time().'.'.$image->getClientOriginalExtension();

                            $destinationPath = public_path('/uploads/question');
                            $image->move($destinationPath, $insertData['option4']);
                        }
                    }else{
                        $insertData['option4'] = $request->option4[$value->id];
                    }

                    $insertData['answer_behavior_tag1'] = $request->answer_behavior_tag1[$value->id];
                    $insertData['answer_behavior_tag2'] = $request->answer_behavior_tag2[$value->id];
                    $insertData['answer_behavior_tag3'] = $request->answer_behavior_tag3[$value->id];
                    $insertData['answer_behavior_tag4'] = $request->answer_behavior_tag4[$value->id];

                    $insertData['solution'] = $request->solution[$value->id];
                    $insertData['question_id'] = $request->id;
                    $insertData['language_id'] = $value->id;
                    $insertData['status'] = $request->status;
                    Question_detail::create($insertData);
                }
            }

            toastr()->success('Question updated successfully.');
            return redirect()->route('admin.question');
        }else{
            toastr()->warning('Question already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        $loginCheck = Question::where('id',$request->id)->first();

        if($loginCheck){
            $loginCheck->update(["is_deleted"=>1]);
        }
        toastr()->success('Question deleted successfully.');
        return redirect()->route('admin.question');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Question::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Question status change successfully.');
        return redirect()->route('admin.question');
    }

    public function upload(){
        $data = [];
        if(session()->get('admmin_is_super') == 'T'){
            $data['subject_list'] = Subject::where('status',1)->where('id',session()->get('admmin_subject_id'))->get();
        }else{
            $data['subject_list'] = Subject::where('status',1)->get();
        }
        return view('admin.question.upload',$data);
    }

    public function upload_save(Request $request){
        $input = $request->all();

        $adminAuth = session()->get('adminAuth');
        $subject_id = $request->subject_id;

        if ($image = $request->file('zip_file')){
            $zip_file = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $zip_file);
            
            $zip = new ZipArchive;
            $res = $zip->open(public_path('/uploads')."/".$zip_file);
            if ($res === TRUE){
                $path = public_path('/uploads/question');
                $zip->extractTo($path);
                $zip->close();
            }
        }

        $data = [];
        if($file = $request->file('csv_file')){
            $import = new ExcelToDbImport($subject_id);
            $rows = Excel::import($import, $request->file('csv_file'));

            $message = $import->getMessage();
            // dd($message);
        }
        return redirect()->route('admin.question.upload')->with('success123', 1)->with('message123', $message);
            
    }
    
}
