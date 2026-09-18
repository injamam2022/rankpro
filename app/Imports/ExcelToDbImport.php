<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Question;
use App\Models\Question_detail;

class ExcelToDbImport implements ToCollection, WithHeadingRow
{
    protected $subject_id;
    public $message = "";

    public function __construct($subject_id)
    {
        $this->subject_id = $subject_id;
    }
    public function collection(Collection $rows)
    {
        foreach ($rows as $val) {
            // dd($val);
            $question_detail = Question_detail::where('question_text',$val['question_text'])->first();
                
            if(!$question_detail){
                $insertData = [];
                $insertData['subject_id'] = $this->subject_id;
                $insertData['chapter_id'] = $val['chapter'];
                $insertData['source_id'] = $val['source'];
                $insertData['topic_id'] = $val['topic'];
                $insertData['sub_topic_id'] = $val['sub_topic'];
                $insertData['difficulty_level'] = $val['difficulty_level'];
                $insertData['question_type_id'] = $val['question_type'];
                $insertData['question_source_id'] = $val['question_source'];
                $insertData['solution_video_link'] = $val['solution_video_link'];
                $insertData['answer'] = $val['answer'];
                $insertData['administrator_id'] = 0;
                $insertData['status'] = $val['status'];

                $question = Question::create($insertData);

                $insertData = [];
                $insertData['question_id'] = $question->id;
                $insertData['language_id'] = 1;
                $insertData['solution'] = $val['solution'];
                $insertData['question_text'] = $val['question_text'];
                $insertData['question_image'] = $val['question_image'];
                $insertData['option1'] = $val['option1'];
                $insertData['is_option1_image'] = $val['is_option1_image'];
                $insertData['option2'] = $val['option2'];
                $insertData['is_option2_image'] = $val['is_option2_image'];
                $insertData['option3'] = $val['option3'];
                $insertData['is_option3_image'] = $val['is_option3_image'];
                $insertData['option4'] = $val['option4'];
                $insertData['is_option4_image'] = $val['is_option4_image'];
                $insertData['answer_behavior_tag1'] = $val['ans_behavior_tag_1'];
                $insertData['answer_behavior_tag2'] = $val['ans_behavior_tag_2'];
                $insertData['answer_behavior_tag3'] = $val['ans_behavior_tag_3'];
                $insertData['answer_behavior_tag4'] = $val['ans_behavior_tag_4'];
                // dd($insertData);
                Question_detail::create($insertData);
                
            }else{
                if($this->message){
                    $this->message = $this->message.", ".$val['sl_no'];
                }else{
                    $this->message = $val['sl_no'];
                }
                
            }
        }
    }


    public function getMessage()
    {
        return $this->message;
    }
}
