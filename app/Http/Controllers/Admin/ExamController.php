<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Exam;

class ExamController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Exam::get();
        return view('admin.exam.list',$data);
    }


    public function add(){
        $data = [];
        return view('admin.exam.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Exam::where('email',$request->email)->first();

        if (!$loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_icon')){
                $insertData['profile_icon'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/exam/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['profile_icon']);


                $destinationPath = public_path('/uploads/exam');
                $image->move($destinationPath, $insertData['profile_icon']);
            }
            $insertData['name'] = $request->name;
            $insertData['email'] = $request->email;
            $insertData['phone_number'] = $request->phone_number;
            $insertData['college_name'] = $request->college_name;
            $insertData['location'] = $request->location;
            $insertData['access_token'] = md5(time().$request->email);
            $insertData['hash_code'] = md5(time().$request->email);
            $insertData['password'] = md5($request->password);
            $insertData['status'] = $request->status;

            Exam::create($insertData);
        
            toastr()->success('Exam added successfully.');
            return redirect()->route('admin.exam.add');
        }else{
            toastr()->warning('Exam already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Exam::where('id',$request->id)->first();
        return view('admin.exam.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Exam::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_icon')){
                $saveData['profile_icon'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/exam/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$saveData['profile_icon']);


                $destinationPath = public_path('/uploads/exam');
                $image->move($destinationPath, $saveData['profile_icon']);

                if($loginCheck->profile_icon){
                    if (file_exists(public_path('uploads/exam/'.$loginCheck->profile_icon))) {
                        unlink(public_path('uploads/exam/'.$loginCheck->profile_icon));
                    }
                    if (file_exists(public_path('uploads/exam/thumbnail/'.$loginCheck->profile_icon))) {
                        unlink(public_path('uploads/exam/thumbnail/'.$loginCheck->profile_icon));
                    }
                }
            }
            $insertData['name'] = $request->name;
            $insertData['email'] = $request->email;
            $insertData['phone_number'] = $request->phone_number;
            $insertData['college_name'] = $request->college_name;
            $insertData['location'] = $request->location;
            if($request->password){
                $insertData['password'] = md5($request->password);
            }
            
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Exam updated successfully.');
            return redirect()->route('admin.exam');
        }else{
            toastr()->warning('Exam already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Exam::where('id',$id)->delete();
        toastr()->success('Exam deleted successfully.');
        return redirect()->route('exam');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Exam::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Exam status change successfully.');
        return redirect()->route('exam');
    }
    
}
