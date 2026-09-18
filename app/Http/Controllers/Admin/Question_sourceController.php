<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Question_source;

class Question_sourceController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Question_source::get();
        return view('admin.question_source.list',$data);
    }


    public function add(){
        $data = [];
        return view('admin.question_source.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Question_source::where('name',$request->name)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['status'] = $request->status;

            Question_source::create($insertData);
        
            toastr()->success('Question source added successfully.');
            return redirect()->route('admin.question_source');
        }else{
            toastr()->warning('Question source already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Question_source::where('id',$request->id)->first();
        return view('admin.question_source.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Question_source::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Question source updated successfully.');
            return redirect()->route('admin.question_source');
        }else{
            toastr()->warning('Question source already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Question_source::where('id',$id)->delete();
        toastr()->success('Question source deleted successfully.');
        return redirect()->route('admin.question_source');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Question_source::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Question source status change successfully.');
        return redirect()->route('admin.question_source');
    }
    
}
