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
use App\Models\Exam_question;
use App\Models\Location;

class Online_exam_resultController extends Controller
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
        
        foreach($data as $value){
            $user_id = $value['CAND_ID'];
            // dd($value['Q001']);
            foreach ($value as $key => $val) {
                if($key == 'CAND_ID' || $key == "SCANNO"){
                    
                }else{
                    $question_id = str_replace('Q', '', $key);
                    dd((int) $question_id);
                }
            }
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
                $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        $exam_question = Exam_question::select([
                            'exam_questions.id as exam_question_id','exam_questions.question_id',
                            'questions.answer'
                        ])
                        ->leftJoin('questions', 'questions.id', '=', 'exam_questions.question_id')
                        ->where('exam_questions.exam_id',$exam_id)->orderBy('exam_questions.id','ASC')->get();

        $exam_question_list = [];
        foreach ($exam_question as $key => $value) {
            // code...
        }
        
        foreach($data as $value){
            $user_id = $value['CAND_ID'];
            // dd($value['Q001']);
            foreach ($value as $key => $val) {
                if($key == 'CAND_ID' || $key == "SCANNO"){
                    
                }else{
                    $question_id = str_replace('Q', '', $key);
                    dd((int) $question_id);
                }
            }
            
        }
        dd($data);
    }

    
    
}
