<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Question_type;

class Question_typeController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Question_type::get();
        return view('admin.question_type.list',$data);
    }


    public function add(){
        $data = [];
        return view('admin.question_type.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Question_type::where('name',$request->name)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['description'] = $request->description;
            $insertData['status'] = $request->status;

            Question_type::create($insertData);
        
            toastr()->success('Question type added successfully.');
            return redirect()->route('admin.question_type');
        }else{
            toastr()->warning('Question type already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Question_type::where('id',$request->id)->first();
        return view('admin.question_type.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Question_type::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;  
            $insertData['description'] = $request->description;         
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Question type updated successfully.');
            return redirect()->route('admin.question_type');
        }else{
            toastr()->warning('Question type already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Question_type::where('id',$id)->delete();
        toastr()->success('Question type deleted successfully.');
        return redirect()->route('admin.question_type');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Question_type::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Question type status change successfully.');
        return redirect()->route('admin.question_type');
    }
    
}
