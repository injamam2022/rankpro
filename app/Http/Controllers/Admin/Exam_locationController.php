<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Exam_location;

class Exam_locationController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Exam_location::get();
        return view('admin.exam_location.list',$data);
    }


    public function add(){
        $data = [];
        return view('admin.exam_location.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Exam_location::where('email',$request->email)->first();

        if (!$loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_icon')){
                $insertData['profile_icon'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/exam_location/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['profile_icon']);


                $destinationPath = public_path('/uploads/exam_location');
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

            Exam_location::create($insertData);
        
            toastr()->success('Exam location added successfully.');
            return redirect()->route('admin.exam_location');
        }else{
            toastr()->warning('Exam location already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Exam_location::where('id',$request->id)->first();
        return view('admin.exam_location.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Exam_location::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_icon')){
                $saveData['profile_icon'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/exam_location/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$saveData['profile_icon']);


                $destinationPath = public_path('/uploads/exam_location');
                $image->move($destinationPath, $saveData['profile_icon']);

                if($loginCheck->profile_icon){
                    if (file_exists(public_path('uploads/exam_location/'.$loginCheck->profile_icon))) {
                        unlink(public_path('uploads/exam_location/'.$loginCheck->profile_icon));
                    }
                    if (file_exists(public_path('uploads/exam_location/thumbnail/'.$loginCheck->profile_icon))) {
                        unlink(public_path('uploads/exam_location/thumbnail/'.$loginCheck->profile_icon));
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
            toastr()->success('Exam location updated successfully.');
            return redirect()->route('admin.exam_location');
        }else{
            toastr()->warning('Exam location already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Exam_location::where('id',$id)->delete();
        toastr()->success('Exam location deleted successfully.');
        return redirect()->route('admin.exam_location');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Exam_location::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Exam location status change successfully.');
        return redirect()->route('admin.exam_location');
    }
    
}
