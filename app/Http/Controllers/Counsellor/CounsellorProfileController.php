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

class CounsellorProfileController extends Controller
{

    public function logout(){
        session()->put('rankersAuth',null);
        return redirect()->route('counsellor.index')->with('success-message','You have successfully logged out.');
    }

    public function index(){
        $data = [];
        $data['details'] = Counsellor::where(
            [
                'id'=> session()->get('counsellorAuth')
            ])->first();
        return view('counsellor.profile',$data);
    }

    public function updateProfile(Request $request){
        $validatedData = $request->validate([
                            'phone_number' => 'required',
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Counsellor::where(
            [
                'id'=> session()->get('counsellorAuth'),
            ])->first();

        if ($loginCheck){

            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['phone_number'] = $request->phone_number;
            $insertData['address'] = $request->address;

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
                session()->put('counsellorProfileIcon',$insertData['profile_icon']);
            }
            // dd($insertData);
            $loginCheck->update($insertData);

            toastr()->success('Profile successfully changed.');
            return redirect()->route('counsellor.profile');
        }else{
            toastr()->warning('Current password does not match!');
            return back()->withInput();
        }
    }

    public function changePassword(){
        // dd("ok");
        return view('counsellor.change_password');
    }

    public function updatePassword(Request $request){
        $validatedData = $request->validate([
                                    'current_password' => 'required',
                                    'password' => 'required',
                                ]);

        $input = $request->all();
        // dd($input);
        $loginCheck = Counsellor::where(
            [
                'id'=> session()->get('counsellorAuth'),
                'password'=> md5($request->current_password)
            ])->first();

        if ($loginCheck){
            $loginCheck->update([
                'password' => md5($request->password)
            ]);
            toastr()->success('Password successfully changed.');
            return redirect()->route('counsellor.change_password');
        }else{
            toastr()->warning('Current password does not match!');
            return back()->withInput();
        }
    }

    public function setting(){
        $data = [];
        $data['list'] = Setting::get();
        return view('counsellor.setting.list',$data);
    }

    public function editSetting(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Setting::where(
            [
                'id'=> $input['id']
            ])->first();
        return view('counsellor.setting.edit',$data);
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
            return redirect()->route('counsellor.setting');
        }else{
            toastr()->warning('Setting does not change!');
            return back()->withInput();
        }
    }
    
}
