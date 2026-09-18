<?php

namespace App\Http\Controllers\Rankers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Ranker;
use App\Models\Setting;

class RankerAdminController extends Controller
{
    public function index(){
        $data = [];
        $data['details'] = Ranker::where(
            [
                'id'=> session()->get('rankersAuth')
            ])->first();
        // dd($data);
        return view('rankers.profile',$data);
    }

    public function updateProfile(Request $request){
        $validatedData = $request->validate([
                            'email' => 'required',
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Ranker::where(
            [
                'id'=> session()->get('rankersAuth'),
            ])->first();

        if ($loginCheck){
            $loginCheck->update([
                'name' => $input['name']
            ]);
            toastr()->success('Profile successfully changed.');
            return redirect()->route('rankers.profile');
        }else{
            toastr()->warning('Current password does not match!');
            return back()->withInput();
        }
    }

    public function changePassword(){
        // dd("ok");
        return view('rankers.change_password');
    }

    public function updatePassword(Request $request){
        $validatedData = $request->validate([
                                    'current_password' => 'required',
                                    'password' => 'required',
                                ]);

        $input = $request->all();
        $loginCheck = Ranker::where(
            [
                'id'=> session()->get('rankersAuth'),
                'password'=> md5($request->current_password)
            ])->first();

        // dd($loginCheck);

        if ($loginCheck){
            $loginCheck->update([
                'password' => md5($request->password)
            ]);
            toastr()->success('Password successfully changed.');
            return redirect()->route('rankers.change_password');
        }else{
            toastr()->warning('Current password does not match!');
            return back()->withInput();
        }
    }

    
}
