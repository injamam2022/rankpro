<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Notice;
use App\Models\User;
use App\Models\Notice_user;
use App\Models\Subject;
use App\Models\Exam;

class NoticeController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Notice::get();
        return view('admin.notice.list',$data);
    }


    public function add(){
        $data = [];
        $data['student_list'] = User::where('status',1)->get();
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['exam_list'] = Exam::where('status',1)->get();
        return view('admin.notice.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Notice::where('name',$request->name)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['description'] = $request->description;
            $insertData['is_urgent'] = $request->is_urgent;
            $insertData['status'] = $request->status;

            $notice = Notice::create($insertData);

            $class_id = $request->class_id;
            $exam_type = $request->exam_type;
            $exam_id = $request->exam_id;
            $test_series = $request->test_series;
            $user_id = $request->user_id;

            if(!empty($user_id)){
                $insertData = [];
                $insertData['notice_id'] = $notice->id;
                $insertData['user_id'] = $request->user_id;

                Notice_user::create($insertData);
            }else if(!empty($test_series)){

            }else if(!empty($exam_id)){

            }else if(!empty($exam_type)){

            }else if(!empty($class_id)){

            }

            toastr()->success('Notice added successfully.');
            return redirect()->route('admin.notice');
        }else{
            toastr()->warning('Notice already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Notice::where('id',$request->id)->first();
        return view('admin.notice.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Notice::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;  
            $insertData['description'] = $request->description;        
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Notice updated successfully.');
            return redirect()->route('admin.notice');
        }else{
            toastr()->warning('Notice already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Notice::where('id',$id)->delete();
        toastr()->success('Notice deleted successfully.');
        return redirect()->route('admin.notice');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Notice::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Notice status change successfully.');
        return redirect()->route('admin.notice');
    }
    
}
