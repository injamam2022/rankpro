<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Counsellor;

class CounsellorController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Counsellor::get();
        return view('admin.counsellor.list',$data);
    }


    public function add(){
        $data = [];
        return view('admin.counsellor.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Counsellor::where('email',$request->email)->first();

        if (!$loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_icon')){
                $insertData['profile_icon'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/counsellor/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['profile_icon']);


                $destinationPath = public_path('/uploads/counsellor');
                $image->move($destinationPath, $insertData['profile_icon']);
            }
            $insertData['name'] = $request->name;
            $insertData['email'] = $request->email;
            $insertData['phone_number'] = $request->phone_number;
            $insertData['address'] = $request->address;
            $insertData['code'] = $request->code;
            $insertData['access_token'] = md5(time().$request->email);
            $insertData['hash_code'] = md5(time().$request->email);
            $insertData['password'] = md5($request->password);
            $insertData['status'] = $request->status;

            Counsellor::create($insertData);
        
            toastr()->success('Counsellor added successfully.');
            return redirect()->route('admin.counsellor');
        }else{
            toastr()->warning('Counsellor already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Counsellor::where('id',$request->id)->first();
        return view('admin.counsellor.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Counsellor::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_icon')){
                $insertData['profile_icon'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/counsellor/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['profile_icon']);


                $destinationPath = public_path('/uploads/counsellor');
                $image->move($destinationPath, $insertData['profile_icon']);

                if($loginCheck->profile_icon){
                    if (file_exists(public_path('uploads/counsellor/'.$loginCheck->profile_icon))) {
                        unlink(public_path('uploads/counsellor/'.$loginCheck->profile_icon));
                    }
                    if (file_exists(public_path('uploads/counsellor/thumbnail/'.$loginCheck->profile_icon))) {
                        unlink(public_path('uploads/counsellor/thumbnail/'.$loginCheck->profile_icon));
                    }
                }
            }
            $insertData['name'] = $request->name;
            $insertData['email'] = $request->email;
            $insertData['phone_number'] = $request->phone_number;
            $insertData['address'] = $request->address;
            $insertData['code'] = $request->code;
            if($request->password){
                $insertData['password'] = md5($request->password);
            }
            
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Counsellor updated successfully.');
            return redirect()->route('admin.counsellor');
        }else{
            toastr()->warning('Counsellor already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Counsellor::where('id',$id)->delete();
        toastr()->success('Counsellor deleted successfully.');
        return redirect()->route('admin.counsellor');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Counsellor::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Counsellor status change successfully.');
        return redirect()->route('admin.counsellor');
    }
    
}
