<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class ExcelToDbImportStudent implements ToCollection, WithHeadingRow
{
    protected $counsellor_id;
    public $message = "";

    public function __construct($counsellor_id)
    {
        $this->counsellor_id = $counsellor_id;
    }
    public function collection(Collection $rows)
    {
        foreach ($rows as $val) {
            // dd($val);
            $question_detail = User::where('email_id',$val['email'])->first();
                
            if(!$question_detail){
                if(!empty($val['first_name']) && !empty($val['email']) && !empty($val['phone'])){
                    $insertData = [];
                    $insertData['counsellor_id'] = $this->counsellor_id;
                    $insertData['user_tag_id'] = $val['tag'];
                    $insertData['mobile_number'] = $val['phone'];
                    $insertData['profile_img'] = $val['profile_icon'];
                    $insertData['first_name'] = $val['first_name'];
                    $insertData['last_name'] = $val['last_name'];
                    $insertData['email_id'] = $val['email'];
                    $insertData['is_whatsapp'] = $val['is_whatsapp'];
                    $insertData['address'] = $val['address_as_per_adhaar'];
                    $insertData['father_full_name'] = $val['fathers_name'];
                    $insertData['father_mobile_number'] = $val['fathers_contact_no'];
                    $insertData['father_qualification'] = $val['fathers_qualification'];
                    $insertData['mother_full_name'] = $val['mothers_name'];
                    $insertData['mother_mobile_number'] = $val['mothers_contact_no'];
                    $insertData['mother_qualification'] = $val['mothers_qualification'];
                    $insertData['qualification_details'] = $val['qualification_details'];
                    $insertData['certificate'] = $val['certificate'];
                    $insertData['password'] = Hash::make($val['password']);
                    $insertData['status'] = 1;
                    // dd($insertData);
                    $question = User::create($insertData);
                }
                
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
