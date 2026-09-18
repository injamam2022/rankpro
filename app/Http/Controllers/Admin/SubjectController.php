<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Subject;

class SubjectController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Subject::get();
        return view('admin.subject.list',$data);
    }


    public function add(){
        $data = [];
        return view('admin.subject.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Subject::where('name',$request->name)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['per_question_time'] = $request->per_question_time;
            $insertData['per_exam_no_of_question'] = $request->per_exam_no_of_question;
            $insertData['status'] = $request->status;

            Subject::create($insertData);
        
            toastr()->success('Subject added successfully.');
            return redirect()->route('admin.subject');
        }else{
            toastr()->warning('Subject already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Subject::where('id',$request->id)->first();
        return view('admin.subject.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Subject::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;  
            $insertData['per_question_time'] = $request->per_question_time;
            $insertData['per_exam_no_of_question'] = $request->per_exam_no_of_question;          
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Subject updated successfully.');
            return redirect()->route('admin.subject');
        }else{
            toastr()->warning('Subject already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Subject::where('id',$id)->delete();
        toastr()->success('Subject deleted successfully.');
        return redirect()->route('admin.subject');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Subject::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Subject status change successfully.');
        return redirect()->route('admin.subject');
    }
    
}
