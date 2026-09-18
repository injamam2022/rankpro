<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Ranker;
use App\Models\Exam;
use App\Models\Question_paper_question;
use App\Models\Location;
use App\Models\Exam_user;
use App\Models\Exam_result;
use App\Models\Offline_exam_question;

class Offline_exam_resultController extends Controller
{
    public function upload(){
        $data = [];
        $data['exam_list'] = [];//Exam::where('type',2)->where('status',1)->get();
        $data['location_list'] = Location::where('status',1)->get();
        return view('admin.offline_exam_result.upload',$data);
    }

    public function upload_save(Request $request){

        $input = $request->all();
        $exam_id = $request->exam_id;

        $file = $request->file('csv_file');
        $data = [];

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $header = fgetcsv($handle); // Optional: read the header row
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        $exam_details = Exam::where('id',$exam_id)->first();

        if($exam_details->question_paper_id){
            $exam_question = Question_paper_question::select('question_paper_questions.*','questions.answer','question_papers.no_of_question','question_papers.totals_marks_for_exam','question_papers.marks_per_question','question_papers.negative_marking_applicable','question_papers.negative_marking_per_question')
                            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                            ->leftJoin('question_papers', 'question_papers.id', '=', 'question_paper_questions.question_paper_id')
                            ->where('question_paper_id',$exam_details->question_paper_id)
                            ->orderBy('id','ASC')->get();
        }else{
            $exam_question = Offline_exam_question::select(['offline_exam_questions.*','exams.no_of_question','exams.totals_marks_for_exam','exams.marks_per_question','exams.negative_marking_applicable','exams.negative_marking_per_question'])
                            ->leftJoin('exams', 'exams.id', '=', 'offline_exam_questions.exam_id')
                            ->where('offline_exam_questions.exam_id',$exam_id)->get();
        }
            
        $exam_question_list = [];
        foreach ($exam_question as $key => $value) {
            $exam_question_list[$key+1] = $value;
        }
        // dd($data);
        $no_of_question = count($exam_question);

        if($no_of_question){
            $user_list = [];
            foreach($data as $value){
                // dd($value);
                $offline_exam_question_result = [];
                $user_id = $value['CAND_ID'];
                $user_list[$user_id]['exam_type'] = $value['EXAM TYPE'];
                $user_list[$user_id]['total_answer'] = 0;
                $user_list[$user_id]['total_right_answer'] = 0;

                // dd($value['Q001']);
                foreach ($value as $key => $val) {
                    $val = trim($val);
                    if($key == 'CAND_ID' || $key == "SCANNO" || $key == "EXAM" || $key == "EXAM TYPE"){
                        
                    }else{
                        // dd($exam_question_list[$key]);
                        if(isset($exam_question_list[$key])){
                            // dd($exam_question_list[$key]);
                            $marks_per_question = ($exam_question_list[$key]->marks_per_question)?$exam_question_list[$key]->marks_per_question:1;
                            $totals_marks_for_exam = ($exam_question_list[$key]->totals_marks_for_exam)?$exam_question_list[$key]->totals_marks_for_exam:1;
                            $negative_marking_applicable = ($exam_question_list[$key]->negative_marking_applicable)?$exam_question_list[$key]->negative_marking_applicable:0;
                            $negative_marking_per_question = ($exam_question_list[$key]->negative_marking_per_question)?$exam_question_list[$key]->negative_marking_per_question:0;
                            $insertData = [];
                            $insertData['user_id'] = $user_id;
                            $insertData['exam_id'] = $exam_id;
                            $insertData['exam_question_id'] = $exam_question_list[$key]->id;
                            $insertData['answer'] = $val;
                            $insertData['result'] = 0;
                            if($val){
                                $user_list[$user_id]['total_answer'] = $user_list[$user_id]['total_answer']+1;

                                if($val == $exam_question_list[$key]->answer){
                                    $user_list[$user_id]['total_right_answer'] = $user_list[$user_id]['total_right_answer']+1;
                                    $insertData['result'] = $marks_per_question;
                                }else{
                                    if($negative_marking_applicable){
                                        $insertData['result'] = -($negative_marking_per_question);
                                    }
                                }
                            }
                            // dd($exam_question_list[$key]);
                            
                            $user_list[$user_id]['no_of_question'] = $no_of_question;
                            $user_list[$user_id]['marks_per_question'] = $marks_per_question;
                            $user_list[$user_id]['negative_marking_applicable'] = $negative_marking_applicable;
                            $user_list[$user_id]['negative_marking_per_question'] = $negative_marking_per_question;
                            $user_list[$user_id]['total_mark'] = $totals_marks_for_exam;
                            // dd($user_list);
                            array_push($offline_exam_question_result, $insertData);
                        }
                    }
                }
                $user_list[$user_id]['offline_exam_question_result'] = $offline_exam_question_result;
            }

            // dd($user_list);
            // $user_list = collect($user_list)
            //     ->sortByDesc('total_number')
            //     ->values()
            //     ->all();
                
            foreach ($user_list as $key => $value) {

                $insertData = [];
                $insertData['exam_id'] = $exam_id;
                $insertData['user_id'] = $key;
                $insertData['total_mark'] = $value['total_mark'];
                $insertData['exam_type'] = $value['exam_type'];
                $insertData['total_answer'] = $value['total_answer'];
                $insertData['total_right_answer'] = $value['total_right_answer'];
                if($value['negative_marking_applicable'] == 1 && $value['negative_marking_per_question']){
                    $total_worng_answer = $value['total_answer'] - $value['total_right_answer'];

                    $total_worng_answer = ($total_worng_answer * $value['negative_marking_per_question']);

                    $insertData['total_number'] =  ($value['marks_per_question'] * $value['total_right_answer']) - $total_worng_answer;
                    $insertData['percentage'] = ($insertData['total_number']) * 100 / $value['total_mark'];
                }else{
                    $insertData['total_number'] = $value['marks_per_question'] * $value['total_right_answer'];
                    $insertData['percentage'] = $insertData['total_number'] * 100 / $value['total_mark'];
                }
                    
                // dd($value['offline_exam_question_result']);
                // dd($insertData);
                $exam_user = Exam_user::where('exam_id',$exam_id)->where('user_id',$key)->first();
                if($exam_user){
                    Exam_result::where('exam_id',$exam_id)->where('user_id',$key)->where('exam_user_id',$exam_user->id)->delete();

                    $exam_user->update($insertData);
                }else{
                    
                    $exam_user = Exam_user::create($insertData);
                }

                if(count($value['offline_exam_question_result'])){
                    foreach ($value['offline_exam_question_result'] as $key => $value) {
                        $value['exam_user_id'] = $exam_user->id;
                        Exam_result::create($value);
                    }
                    
                }
            }

            toastr()->success('Question answer uploaded successfully.');
            return redirect()->route('admin.offline_exam_result.upload');
        }else{
            toastr()->warning('Question not found.');
            return redirect()->route('admin.offline_exam_result.upload');
        }
    }
    

    public function question(){
        $data = [];
        $data['exam_list'] = [];//Exam::where('type',2)->where('status',1)->get();
        $data['location_list'] = Location::where('status',1)->get();
        return view('admin.offline_exam_result.question',$data);
    }

    public function question_save(Request $request){

        $input = $request->all();
        $exam_id = $request->exam_id;
        // $file = $request->file('profile_icon');
        // $filePath = $file->store('pdfs');

        $file = $request->file('csv_file');
        $data = [];

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $header = fgetcsv($handle); // Optional: read the header row
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($header) === count($row)) {
                    $data[] = array_combine($header, $row);
                }else{
                    echo count($header)."-".count($row)."<br>";
                }
            }
            fclose($handle);
        }
        
        Offline_exam_question::where('exam_id',$exam_id)->delete();
        $question_number = 1;
        foreach($data as $value){
            // dd($value);
            $insertData = [];
            $insertData['question_number'] = $question_number;
            $insertData['exam_id'] = $exam_id;
            $insertData['question_text'] = $value['QUESTION'];
            $insertData['subject_id'] = $value['Subject'];
            $insertData['chapter_id'] = $value['Chapter'];
            $insertData['topic_id'] = $value['Topic'];
            $insertData['sub_topic_id'] = $value['Sub Topic'];
            $insertData['answer'] = $value['ANSWER'];
            $insertData['difficulty_level'] = $value['Difficulty Level'];
            $insertData['question_type_id'] = $value['Question Type'];
            $insertData['answer_behavior_tag1'] = $value['Ans Behavior Tag 1'];
            $insertData['answer_behavior_tag2'] = $value['Ans Behavior Tag 2'];
            $insertData['answer_behavior_tag3'] = $value['Ans Behavior Tag 3'];
            $insertData['answer_behavior_tag4'] = $value['Ans Behavior Tag 4'];
            $insertData['video_link'] = $value['SOL LINK'];
            
            Offline_exam_question::create($insertData);

            $question_number = $question_number + 1;
            
        }

        toastr()->success('Question uploaded to exam successfully.');
        return redirect()->route('admin.offline_exam_result.question');
    }

    
    
}
