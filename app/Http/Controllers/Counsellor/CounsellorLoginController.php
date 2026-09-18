<?php

namespace App\Http\Controllers\Counsellor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Counsellor;
use App\Models\Setting;

class CounsellorLoginController extends Controller
{
    public function index(){
        // dd(session()->get('counsellorAuth'));
        return view('counsellor.login');
    }

    public function dologin(Request $request){
        $validatedData =  Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validatedData->fails())
        {
            return redirect()->back()->withInput();
        }

        $input = $request->all();
        $loginCheck = Counsellor::where(
            [
                'email'=> $input['email'],
                'password'=> md5($input['password'])
            ])->first();
        // dd($loginCheck);
        if ($loginCheck){
            session()->put('counsellorAuth',$loginCheck->id);
            session()->put('counsellorName',$loginCheck->name);
            session()->put('counsellorEmail',$loginCheck->email);
            session()->put('counsellorCode',$loginCheck->code);
            session()->put('counsellorProfileIcon',$loginCheck->profile_icon);
            toastr()->success('You have successfully logged in.');
            return redirect()->route('counsellor.dashboard');
        }else{
            toastr()->success('Incorrect login credentials!');
            return back()->withInput()->with('error-message','Incorrect login credentials!');
        }

    }
    

    public function set_password($id){
        // dd("ok");
        $data = [];
        $data['id'] = $id;
        return view('counsellor.set_password',$data);
    }

    public function update_set_password(Request $request){
        $validatedData = $request->validate([
                            'confirm_password' => 'required',
                            'password' => 'required',
                        ]);

        $input = $request->all();
        $loginCheck = Admin::where('remember_token',$input['id'])->first();

        if ($loginCheck){
            $loginCheck->update([
                'password' => md5($input['password']),
                'remember_token' => ''
            ]);
            toastr()->success('Password successfully set.');
            return redirect()->route('webadmin.index');
        }else{
            toastr()->warning('Token expired');
            return back()->withInput();
        }
    }
    
    public function forgot_password(){
        return view('counsellor.forgot_password');
    }
    
    public function updateforgotpassword(Request $request){

        $validatedData =  Validator::make($request->all(),[
            'email' => 'required|email',
        ]);

        if ($validatedData->fails())
        {
            return redirect()->back()->withInput();
        }

        $input = $request->all();
        // echo "<pre>"; print_r($input['email']); exit;
        $loginCheck = Admin::where('email',$input['email'])->first();
        
        $pass = rand(111111,1111111111);
        //dd($pass);
        if ($loginCheck){

            toastr()->success('Please check your email');
            return redirect()->route('counsellor.index');
        }else{
            return back()->withInput()->with('error-message','Invalid email id.');
        }
        //dd($pass);
    }
    
}
