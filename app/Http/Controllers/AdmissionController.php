<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\User_apply;


class AdmissionController extends Controller
{
    public function signup(){
        return view('site.registration');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'mobile_number'     => 'required|digits:10',
            'email_id'          => 'required|email',
            'profileImage'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password'          => 'required|string|min:4|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 400);
        }

        $existingUser = User::where('email_id', $request->email_id)->first();
        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'This email address is already registered.',
            ], 400);
        }

        $existingUser = User::where('mobile_number', $request->mobile_number)->first();
        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'This phone number is already registered.',
            ], 400);
        }

        $profileImagePath = null;
        if ($request->hasFile('profileImage')) {
            $profileImagePath = $this->uploadImage($request->file('profileImage'), 'profileImage');
        }

        $otp = rand(1000, 9999);
        $hashedPassword = Hash::make($request->password);

        $user = new User_apply();
        $user->first_name            = $request->first_name;
        $user->last_name             = $request->last_name;
        $user->mobile_number         = $request->mobile_number;
        $user->father_full_name      = $request->father_full_name;
        $user->father_mobile_number  = $request->father_mobile_number;
        $user->is_whatsapp           = ($request->is_whatsapp)?1:0;
        $user->email_id              = $request->email_id;
        $user->profile_img           = $profileImagePath;
        $user->password              = $hashedPassword;
        $user->otp                   = $otp;
        $user->status                = 0;

        $user->save();
        
        $emailData = [
            'subject' => 'Verify Your Account - OTP Code',
            'email'   => $user->email_id,
            'otp'     => $otp,
        ];

        try {
            $url_link = "https://2factor.in/API/V1/604aef81-03bd-11f0-8b17-0200cd936042/SMS/+91".$request->mobile_number."/".$otp."/OTPtemplate";

            $response = Http::get($url_link);

            if ($response->successful()) {
                $json = $response->json();

            }else{

            }

        } catch (\Exception $e) {
            Log::error('SMS failed to send: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Please check your email for OTP verification.',
            'user_id' => $user->id
        ], 200);
    }

    public function verifyOtp(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'otp'      => 'required|digits:4'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors()
            ], 400);
        }

        $user = User_apply::where('id', $request->user_id)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }
        if ($user->otp != $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP. Please try again.']);
        }

        $user->otp    = null;
        $user->status = 1;
        $user->save();


        do {
            $rankproId = random_int(10000000, 99999999);
        } while (User::where('rankpro_id', $rankproId)->exists());

        $insertData = [];
        $insertData['first_name'] = $user->first_name;
        $insertData['last_name'] = $user->last_name;
        $insertData['mobile_number'] = $user->mobile_number;
        $insertData['is_whatsapp'] = ($user->is_whatsapp)?1:0;
        $insertData['email_id'] = $user->email_id;
        $insertData['password'] = $user->password;
        $insertData['profile_img'] = $user->profile_img;
        $insertData['father_full_name'] = $user->father_full_name;
        $insertData['father_mobile_number'] = $user->father_mobile_number;
        $insertData['rankpro_id'] = $rankproId;

        User::create($insertData);

        return response()->json(['success' => true, 'message' => 'OTP verified successfully!']);
    }

    public function resendOtp(Request $request){
        $email = $request->input('email_id');
        $otp = rand(1000, 9999);

        $user = User::where('email_id', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        $user->otp = $otp;
        $user->save();

        try {
            $url_link = "https://2factor.in/API/V1/604aef81-03bd-11f0-8b17-0200cd936042/SMS/+91".$request->mobile_number."/".$otp."/OTPtemplate";

            $response = Http::get($url_link);

            if ($response->successful()) {
                $json = $response->json();

            }else{

            }

        } catch (\Exception $e) {
            Log::error('SMS failed to send: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully! Please check your email for OTP verification.',
            'email'   => $email,
            'user_id'   => $user->id,
        ], 200);
    }

    private function uploadImage($file, $folder){
        $filename = uniqid().'_'.time() . '_' . $file->getClientOriginalName();
        $path     = public_path("uploads/$folder");
        $file->move($path, $filename);
        return $filename;
    }

    public function showLogin(){
        return view('site.login');
    }

    public function login(Request $request){
        $request->validate([
            'email_id'    => 'required',
            'password'    => 'required|string',
        ]);

        $user_detail = User::where('status',1)->where('email_id', $request->email_id)
                    ->orWhere('father_mobile_number', $request->email_id)
                    ->first();
        if($user_detail){
            if($user_detail->email_id == $request->email_id){
                session()->put('parant_login_type','S');
                if (Hash::check($request->password, $user_detail->password)) {
                    Auth::login($user_detail);
                    return redirect()->route('index')->with('success', 'You are logged in!');
                }
            }else if($user_detail->father_mobile_number == $request->email_id){
                session()->put('parant_login_type','P');
                if (Hash::check($request->password, $user_detail->password)) {
                    Auth::login($user_detail);
                    return redirect()->route('index')->with('success', 'You are logged in!');;
                }
            }
        }
        \Log::info('Redirecting back with error message.');
        return redirect()->back()->with('error', 'Invalid email or password.');
    }
}
