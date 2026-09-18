<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Administrator;
use App\Models\Subject;

class TeacherController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Administrator::select(['administrators.*','subjects.name as user_role_name'])
                            ->leftJoin('subjects', 'administrators.subject_id', '=', 'subjects.id')
                            ->where('administrators.type','T')->get();
        return view('admin.teacher.list',$data);
    }


    public function add(){
        $data = [];
        $data['subject_list'] = Subject::where('status','1')->get();
        return view('admin.teacher.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'user_name' => 'required',
                            'login_email' => 'required'
                        ]);

        $input = $request->all();
        //dd($input);
        $loginCheck = Administrator::where('login_email',$request->login_email)->first();

        if (!$loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_icon')){
                $insertData['profile_icon'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/admin_user/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['profile_icon']);


                $destinationPath = public_path('/uploads/admin_user');
                $image->move($destinationPath, $insertData['profile_icon']);
            }
            $insertData['user_name'] = $request->user_name ?? '';
            $insertData['login_email'] = $request->login_email ?? '';
            $insertData['phone_number'] = $request->phone_number ?? '';
            $insertData['type'] = 'T';
            $insertData['subject_id'] = $request->subject_id ?? '';
            $insertData['password'] = md5($request->password) ?? '';
            $insertData['status'] = $request->status;

            Administrator::create($insertData);

            toastr()->success('Teacher added successfully.');
            return redirect()->route('admin.teacher');
        }else{
            toastr()->warning('Teacher already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['subject_list'] = Subject::where('status','1')->get();
        $data['details'] = Administrator::where('id',$request->id)->first();
        return view('admin.teacher.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'user_name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Administrator::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_icon')){
                $insertData['profile_icon'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/admin_user/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['profile_icon']);


                $destinationPath = public_path('/uploads/admin_user');
                $image->move($destinationPath, $insertData['profile_icon']);

                if($loginCheck->profile_icon){
                    if (file_exists(public_path('uploads/admin_user/'.$loginCheck->profile_icon))) {
                        unlink(public_path('uploads/admin_user/'.$loginCheck->profile_icon));
                    }
                    if (file_exists(public_path('uploads/admin_user/thumbnail/'.$loginCheck->profile_icon))) {
                        unlink(public_path('uploads/admin_user/thumbnail/'.$loginCheck->profile_icon));
                    }
                }
            }
            $insertData['user_name'] = $request->user_name ?? '';
            $insertData['login_email'] = $request->login_email ?? '';
            $insertData['phone_number'] = $request->phone_number ?? '';
            $insertData['type'] = 'T';
            $insertData['subject_id'] = $request->subject_id ?? '';
            if($request->password){
                $insertData['password'] = md5($request->password);
            }

            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Teacher updated successfully.');
            return redirect()->route('admin.teacher');
        }else{
            toastr()->warning('Teacher already exist');
            return back()->withInput();
        }
    }

    public function delete(Request $request){
        $id = $request->id;
        Administrator::where('id',$id)->delete();
        toastr()->success('Teacher deleted successfully.');
        return redirect()->route('admin.teacher');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Administrator::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Teacher status change successfully.');
        return redirect()->route('admin.teacher');
    }

}
