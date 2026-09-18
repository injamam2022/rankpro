<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Course;

class CourseController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Course::get();
        return view('admin.course.list',$data);
    }


    public function add(){
        $data = [];
        return view('admin.course.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Course::where('name',$request->name)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['description'] = $request->description;
            $insertData['status'] = $request->status;

            Course::create($insertData);
        
            toastr()->success('Course added successfully.');
            return redirect()->route('admin.course');
        }else{
            toastr()->warning('Course already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Course::where('id',$request->id)->first();
        return view('admin.course.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Course::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;  
            $insertData['description'] = $request->description;       
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Course updated successfully.');
            return redirect()->route('admin.course');
        }else{
            toastr()->warning('Course already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Course::where('id',$id)->delete();
        toastr()->success('Course deleted successfully.');
        return redirect()->route('admin.course');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Course::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Course status change successfully.');
        return redirect()->route('admin.course');
    }
    
}