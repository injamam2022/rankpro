<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\User;

class StudentController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = User::get();
        // dd($data['list']);
        return view('admin.student.list',$data);
    }


    public function add(){
        $data = [];
        return view('admin.student.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = User::where('email',$request->email)->first();

        if (!$loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_img')){
                $insertData['profile_img'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/profileImage/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['profile_img']);


                $destinationPath = public_path('/uploads/profileImage');
                $image->move($destinationPath, $insertData['profile_img']);
            }

            do {
                $rankproId = random_int(100000, 999999);
            } while (User::where('rankpro_id', $rankproId)->exists());

            $insertData['first_name'] = $request->first_name;
            $insertData['last_name'] = $request->last_name;
            $insertData['address'] = $request->address;
            $insertData['mobile_number'] = $request->mobile_number;
            $insertData['is_whatsapp'] = $request->is_whatsapp;
            $insertData['email_id'] = $request->email_id;
            $insertData['father_full_name'] = $request->father_full_name;
            $insertData['father_occupation'] = $request->father_occupation;
            $insertData['father_mobile_number'] = $request->father_mobile_number;
            $insertData['father_qualification'] = $request->father_qualification;
            $insertData['mother_full_name'] = $request->mother_full_name;
            $insertData['mother_occupation'] = $request->mother_occupation;
            $insertData['mother_mobile_number'] = $request->mother_mobile_number;
            $insertData['mother_qualification'] = $request->mother_qualification;
            $insertData['qualification_details'] = $request->qualification_details;
            $insertData['access_token'] = md5(time().$request->email);
            $insertData['hash_code'] = md5(time().$request->email);
            $insertData['password'] = md5($request->password);
            $insertData['status'] = $request->status;
            $insertData['rankpro_id'] = $rankproId;

            $user = User::create($insertData);


            toastr()->success('Student added successfully.');
            return redirect()->route('admin.student.add');
        }else{
            toastr()->warning('Student already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = User::where('id',$request->id)->first();
        return view('admin.student.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'first_name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = User::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_img')){
                $insertData['profile_img'] = time().'.'.$image->getClientOriginalExtension();


                $destinationPath = public_path('/uploads/profileImage');
                $image->move($destinationPath, $insertData['profile_img']);

                if($loginCheck->profile_img){
                    if (file_exists(public_path($loginCheck->profile_img))) {
                        unlink(public_path($loginCheck->profile_img));
                    }
                }
                $insertData['profile_img'] = $insertData['profile_img'];
            }
            if ($image = $request->file('certificate')){
                $insertData['certificate'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/certificate');
                $image->move($destinationPath, $insertData['certificate']);

                if($loginCheck->certificate){
                    if (file_exists(public_path($loginCheck->certificate))) {
                        unlink(public_path($loginCheck->certificate));
                    }
                }
                $insertData['certificate'] = 'uploads/certificate/'.$insertData['certificate'];
            }
            if ($image = $request->file('guardian_signature')){
                $insertData['guardian_signature'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/guardian_signature');
                $image->move($destinationPath, $insertData['guardian_signature']);

                if($loginCheck->guardian_signature){
                    if (file_exists(public_path($loginCheck->guardian_signature))) {
                        unlink(public_path($loginCheck->guardian_signature));
                    }
                }
                $insertData['guardian_signature'] = 'uploads/guardian_signature/'.$insertData['guardian_signature'];
            }
            if ($image = $request->file('student_signature')){
                $insertData['student_signature'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/student_signature');
                $image->move($destinationPath, $insertData['student_signature']);

                if($loginCheck->student_signature){
                    if (file_exists(public_path($loginCheck->student_signature))) {
                        unlink(public_path($loginCheck->student_signature));
                    }
                }
                $insertData['student_signature'] = 'uploads/student_signature/'.$insertData['student_signature'];
            }

            $insertData['first_name'] = $request->first_name;
            $insertData['last_name'] = $request->last_name;
            $insertData['address'] = $request->address;
            $insertData['mobile_number'] = $request->mobile_number;
            $insertData['is_whatsapp'] = ($request->is_whatsapp)?1:0;
            $insertData['email_id'] = $request->email_id;
            $insertData['father_full_name'] = $request->father_full_name;
            $insertData['father_occupation'] = $request->father_occupation;
            $insertData['father_mobile_number'] = $request->father_mobile_number;
            $insertData['father_qualification'] = $request->father_qualification;
            $insertData['mother_full_name'] = $request->mother_full_name;
            $insertData['mother_occupation'] = $request->mother_occupation;
            $insertData['mother_mobile_number'] = $request->mother_mobile_number;
            $insertData['mother_qualification'] = $request->mother_qualification;
            $insertData['qualification_details'] = $request->qualification_details;

            $insertData['address'] = $request->address;
            if($request->password){
                $insertData['password'] = md5($request->password);
            }

            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Student updated successfully.');
            return redirect()->route('admin.student');
        }else{
            toastr()->warning('Student already exist');
            return back()->withInput();
        }
    }

    public function delete(Request $request){
        $id = $request->id;
        User::where('id',$id)->delete();
        toastr()->success('Student deleted successfully.');
        return redirect()->route('admin.student');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = User::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Student status change successfully.');
        return redirect()->route('admin.student');
    }

}
