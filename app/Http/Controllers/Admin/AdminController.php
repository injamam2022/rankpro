<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Administrator;
use App\Models\Setting;

class AdminController extends Controller
{
    public function index(){
        $data = [];
        $data['details'] = Administrator::where(
            [
                'id'=> session()->get('adminAuth')
            ])->first();
        return view('admin.profile',$data);
    }

    public function updateProfile(Request $request){
        $validatedData = $request->validate([
                            'email' => 'required',
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Administrator::where(
            [
                'id'=> session()->get('adminAuth'),
            ])->first();

        if ($loginCheck){
            $loginCheck->update([
                'user_name' => $input['name']
            ]);
            toastr()->success('Profile successfully changed.');
            return redirect()->route('admin.profile');
        }else{
            toastr()->warning('Current password does not match!');
            return back()->withInput();
        }
    }

    public function changePassword(){
        // dd("ok");
        return view('admin.change_password');
    }

    public function updatePassword(Request $request){
        $validatedData = $request->validate([
                                    'current_password' => 'required',
                                    'password' => 'required',
                                ]);

        $input = $request->all();
        //dd($input);
        $loginCheck = Administrator::where(
            [
                'id'=> session()->get('adminAuth'),
                'password'=> md5($request->current_password)
            ])->first();

        if ($loginCheck){
            $loginCheck->update([
                'password' => md5($request->password)
            ]);
            toastr()->success('Password successfully changed.');
            return redirect()->route('admin.updatePassword');
        }else{
            toastr()->warning('Current password does not match!');
            return back()->withInput();
        }
    }

    public function setting(){
        $data = [];
        $data['list'] = Setting::get();
        return view('admin.admin.setting',$data);
    }

    public function editSetting(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Setting::where(
            [
                'id'=> $input['id']
            ])->first();
        return view('admin.admin.edit_setting',$data);
    }

    public function updateSetting(Request $request){
        $validatedData = $request->validate([
                                    'name' => 'required',
                                    'value' => 'required',
                                ]);

        $input = $request->all();
        //dd($input);
        $loginCheck = Setting::where(
            [
                'id'=> $request->id
            ])->first();

        if ($loginCheck){
            $loginCheck->update([
                'name' => $request->name,
                'value' => $request->value
            ]);
            toastr()->success('Setting successfully changed.');
            return redirect()->route('admin.setting');
        }else{
            toastr()->warning('Setting does not change!');
            return back()->withInput();
        }
    }
    
}
