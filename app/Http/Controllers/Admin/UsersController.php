<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Admin;
use App\Models\Hotel;

class UsersController extends Controller
{
    public function index(){
        $data = [];
        $data['list'] = Admin::where('type','A')->get();
        return view('admin.users.list',$data);
    }


    public function add(){
        $data = [];
        $data['hotel_list'] = Hotel::where('status',1)->get();
        return view('admin.users.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Admin::where(
            [
                'email'=> $input['email']
            ])->first();

        if (!$loginCheck){
            $saveData = [
                "name"=>$input['name'],
                "email"=>$input['email'],
                "password"=>md5($input['password']),
                "status"=>$input['status']
            ];

            Admin::create($saveData);
        
            toastr()->success('User added successfully.');
            return redirect()->route('admin.users.add');
        }else{
            toastr()->warning('User already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Admin::where(
            [
                'id'=> $input['id']
            ])->first();
        $data['hotel_list'] = Hotel::where('status',1)->get();
        return view('admin.users.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Admin::where(
            [
                'id'=> $request->id
            ])->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['email'] = $request->email;
            if($request->password){
                $insertData['password'] = $request->password;
            }
            
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('User updated successfully.');
            return redirect()->route('admin.users');
        }else{
            toastr()->warning('User already exist');
            return back()->withInput();
        }
    }
    
}
