<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('site.password.forgot-password');
    }

    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email_id', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'The email address does not exist.']);
        }

        $verificationCode = rand(1000, 9999);

        $user->verification_code = $verificationCode;
        $user->verification_code_expiry = now()->addMinutes(10);
        $user->save();

        Mail::send('emails.verification_code', ['verificationCode' => $verificationCode], function ($message) use ($user) {
            $message->to($user->email_id)
                    ->subject('Password Reset Verification Code');
        });
        return redirect()->route('password.verify')->with('success', 'Verification code sent to your email!');
    }

    public function showVerificationForm()
    {
        return view('site.password.verify-code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|numeric|digits:4',
        ]);

        $user = User::where('verification_code', $request->verification_code)
                    ->where('verification_code_expiry', '>', now())
                    ->first();

        if ($user) {
            $hashedUserId = md5($user->id);
            return redirect()->route('password.reset', ['user_id' => $hashedUserId]);
        }

        return back()->with('error', 'Invalid or expired verification code.');
    }

    public function showResetPasswordForm(Request $request)
    {
        $hashedUserId = $request->user_id;
        $user = User::whereRaw('MD5(id) = ?', [$hashedUserId])->first();

        if (!$user) {
            return redirect()->route('password.forgot')->with('error', 'User not found.');
        }

        return view('site.password.reset-password', compact('user'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ]);
        $userId = $request->user_id;
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('password.forgot')->with('error', 'User not found.');
        }

        if ($user->verification_code_expiry && now()->gt($user->verification_code_expiry)) {
            return redirect()->route('password.forgot')->with('error', 'Verification code has expired.');
        }

        $user->password = Hash::make($request->password);
        $user->verification_code = null;
        $user->verification_code_expiry = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Password successfully reset!');
    }
}
