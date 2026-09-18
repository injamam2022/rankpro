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
            $exam_question = Offline_exam_question::select(['offline_exam_questions.*'])
                ->where('offline_exam_questions.exam_id',$exam_id)->get();
        }
            
        $exam_question_list = [];
        foreach ($exam_question as $key => $value) {
            $exam_question_list[$key+1] = $value;
        }
        // dd($exam_question_list[1]);
        $no_of_question = count($exam_question);
        if($no_of_question){
            $user_list = [];
            foreach($data as $value){
                // dd($value);
                $offline_exam_question_result = [];
                $user_id = $value['CAND_ID'];
                $user_list[$user_id]['total_answer'] = 0;
                $user_list[$user_id]['total_right_answer'] = 0;

                // dd($value['Q001']);
                foreach ($value as $key => $val) {
                    if($key == 'CAND_ID' || $key == "SCANNO"){
                        
                    }else{
                        // dd($exam_question_list[$key]);
                        if(isset($exam_question_list[$key])){
                            // dd($exam_question_list[$key]);
                            $marks_per_question = ($exam_question_list[$key]->marks_per_question)?$exam_question_list[$key]->marks_per_question:1;
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
                            }
                            // dd($exam_question_list[$key]);
                            if($val == $exam_question_list[$key]->answer){
                                $user_list[$user_id]['total_right_answer'] = $user_list[$user_id]['total_right_answer']+1;
                                $insertData['result'] = $exam_question_list[$key]->marks_per_question;
                            }
                            $user_list[$user_id]['no_of_question'] = $no_of_question;
                            $user_list[$user_id]['marks_per_question'] = $marks_per_question;
                            $user_list[$user_id]['negative_marking_applicable'] = $negative_marking_applicable;
                            $user_list[$user_id]['negative_marking_per_question'] = $negative_marking_per_question;
                            // dd($insertData);
                            array_push($offline_exam_question_result, $insertData);
                        }
                    }
                }
                $user_list[$user_id]['offline_exam_question_result'] = $offline_exam_question_result;
            }

            // dd($user_list);

            foreach ($user_list as $key => $value) {

                $insertData = [];
                $insertData['exam_id'] = $exam_id;
                $insertData['user_id'] = $key;
                $insertData['total_answer'] = $value['total_answer'];
                $insertData['total_right_answer'] = $value['total_right_answer'];
                if(isset($value['negative_marking_applicable'])){
                    if($value['negative_marking_applicable'] == 1 && $value['negative_marking_per_question']){
                        $total_worng_answer = $value['total_answer'] - $value['total_right_answer'];
    
                        $total_worng_answer = ($total_worng_answer / $value['negative_marking_per_question']);
    
                        $insertData['total_number'] = $value['marks_per_question'] * ($value['total_right_answer'] - $total_worng_answer);
                        $insertData['percentage'] = ($value['total_right_answer'] - $total_worng_answer) * 100 / $value['no_of_question'];
                    }else{
                        $insertData['total_number'] = $value['marks_per_question'] * $value['total_right_answer'];
                        $insertData['percentage'] = $value['total_right_answer'] * 100 / $value['no_of_question'];
                    }
                }else{
                    $insertData['total_number'] = $value['marks_per_question'] * $value['total_right_answer'];
                    $insertData['percentage'] = $value['total_right_answer'] * 100 / $value['no_of_question'];
                }
                    
                    
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

        foreach($data as $value){
            // dd($value);
            $insertData = [];
            $insertData['exam_id'] = $exam_id;
            $insertData['question_text'] = $value['QUESTION'];
            $insertData['answer'] = $value['ANSWER'];
            $insertData['video_link'] = $value['SOL LINK'];
            // dd($insertData);
            Offline_exam_question::create($insertData);
            
        }

        toastr()->success('Question uploaded to exam successfully.');
        return redirect()->route('admin.offline_exam_result.question');
    }

    
    
}
