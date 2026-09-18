<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Ranker_appointment;

class RankerAppointmentController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Ranker_appointment::get();
        return view('admin.ranker_appointment.list',$data);
    }


    public function add(){
        $data = [];
        return view('admin.ranker_appointment.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Ranker_appointment::where('email',$request->email)->first();

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

            Ranker_appointment::create($insertData);
        
            toastr()->success('Ranker appointment added successfully.');
            return redirect()->route('admin.ranker_appointment.add');
        }else{
            toastr()->warning('Ranker appointment already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Ranker_appointment::where('id',$request->id)->first();
        return view('admin.ranker_appointment.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Ranker_appointment::where('id',$request->id)->first();

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
            toastr()->success('Ranker appointment updated successfully.');
            return redirect()->route('admin.ranker_appointment');
        }else{
            toastr()->warning('Ranker appointment already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Ranker_appointment::where('id',$id)->delete();
        toastr()->success('Ranker appointment deleted successfully.');
        return redirect()->route('admin.ranker_appointment');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Ranker_appointment::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Ranker appointment status change successfully.');
        return redirect()->route('admin.ranker_appointment');
    }
    
}
