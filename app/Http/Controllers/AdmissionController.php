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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\User_apply;


class AdmissionController extends Controller
{
    public function signup(Request $request){
        $this->rememberSignupRedirect($request);
        return view('site.registration');
    }

    public function store(Request $request){
        $this->rememberSignupRedirect($request);
        $mobile = preg_replace('/\D+/', '', (string) $request->mobile_number);
        $request->merge(['mobile_number' => $mobile]);

        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'mobile_number'     => 'required|digits:10',
            'email_id'          => 'required|email',
            'address'           => 'nullable|string|max:255',
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
        if (!$existingUser) {
            $existingUser = User_apply::where('email_id', $request->email_id)->first();
        }
        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'This email address is already registered.',
            ], 400);
        }

        if ($this->mobileAlreadyRegistered($mobile)) {
            return response()->json([
                'success' => false,
                'message' => 'This phone number is already registered.',
                'errors' => ['mobile_number' => ['This phone number is already registered.']],
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
        $user->is_whatsapp           = ($request->is_whatsapp)?1:0;
        $user->email_id              = $request->email_id;
        $user->profile_img           = $profileImagePath;
        $user->password              = $hashedPassword;
        $user->otp                   = $otp;
        $user->status                = 0;
        if (Schema::hasColumn($user->getTable(), 'address')) {
            $user->address = $request->address;
        }

        $user->save();
        session(['pending_signup_address_'.$user->id => $request->address]);
        
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
        $insertData['address'] = $user->address ?: session()->pull('pending_signup_address_'.$user->id);
        $insertData['rankpro_id'] = $rankproId;
        $insertData['status'] = 1;

        $createdUser = User::create($insertData);
        Auth::login($createdUser);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully!',
            'redirect' => $this->intendedAfterAuth(),
        ]);
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

    public function showLogin(Request $request){
        $this->rememberSignupRedirect($request);
        return view('site.login');
    }

    public function login(Request $request){
        $this->rememberSignupRedirect($request);
        $request->validate([
            'email_id'    => 'required',
            'password'    => 'required|string',
        ], [
            'email_id.required' => 'Enter your email or phone number.',
            'password.required' => 'Enter your password.',
        ]);

        $login = $request->email_id;
        $user_detail = User::where('status', 1)
            ->where(function ($query) use ($login) {
                $query->where('email_id', $login)
                    ->orWhere('mobile_number', $login)
                    ->orWhere('father_mobile_number', $login);
            })
            ->first();

        if ($user_detail && Hash::check($request->password, $user_detail->password)) {
            $isParent = $user_detail->father_mobile_number && $user_detail->father_mobile_number == $login;
            session()->put('parant_login_type', $isParent ? 'P' : 'S');
            Auth::login($user_detail);
            return redirect($this->intendedAfterAuth())->with('success', 'You are logged in!');
        }
        \Log::info('Redirecting back with error message.');
        return redirect()->back()->with('error', 'Invalid email or password.');
    }

    private function rememberSignupRedirect(Request $request): void
    {
        $next = $request->query('next', $request->input('next'));
        if ($next === 'custom_test') {
            session(['signup_redirect' => 'custom_test']);
        }
    }

    private function intendedAfterAuth(): string
    {
        $intended = session()->pull('signup_redirect');
        if ($intended === 'custom_test') {
            return route('custom_test');
        }
        return route('index');
    }

    private function mobileAlreadyRegistered(string $mobile): bool
    {
        $exists = User::where('mobile_number', $mobile);
        if (Schema::hasColumn((new User)->getTable(), 'is_deleted')) {
            $exists->where(function ($query) {
                $query->where('is_deleted', 0)->orWhereNull('is_deleted');
            });
        }
        if ($exists->exists()) {
            return true;
        }

        return User_apply::where('mobile_number', $mobile)->exists();
    }
}
