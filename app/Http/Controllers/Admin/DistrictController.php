<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\District;

class DistrictController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = District::get();
        return view('admin.district.list',$data);
    }


    public function add(){
        $data = [];
        return view('admin.district.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = District::where('email',$request->email)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['email'] = $request->email;
            $insertData['phone_number'] = $request->phone_number;
            $insertData['college_name'] = $request->college_name;
            $insertData['location'] = $request->location;
            $insertData['access_token'] = md5(time().$request->email);
            $insertData['hash_code'] = md5(time().$request->email);
            $insertData['password'] = md5($request->password);
            $insertData['status'] = $request->status;

            District::create($insertData);
        
            toastr()->success('District added successfully.');
            return redirect()->route('admin.district');
        }else{
            toastr()->warning('District already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = District::where('id',$request->id)->first();
        return view('admin.district.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = District::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
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
            toastr()->success('District updated successfully.');
            return redirect()->route('admin.district');
        }else{
            toastr()->warning('District already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        District::where('id',$id)->delete();
        toastr()->success('District deleted successfully.');
        return redirect()->route('admin.district');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = District::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('District status change successfully.');
        return redirect()->route('admin.district');
    }
    
}
