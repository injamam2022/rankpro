<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Support\PasswordResetNotifier;

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
        ], [
            'email.required' => 'Enter the email on your account.',
            'email.email' => 'Enter a valid email address.',
        ]);

        $user = User::where('email_id', $request->email)->where('status', 1)->first();

        if (!$user) {
            return back()->withInput()->withErrors(['email' => 'We could not find an account with that email.']);
        }

        $verificationCode = rand(1000, 9999);

        $user->verification_code = $verificationCode;
        $user->verification_code_expiry = now()->addMinutes(10);
        $user->save();

        $sent = PasswordResetNotifier::send($user, (string) $verificationCode);

        if (empty($sent['ok'])) {
            return back()->withInput()->withErrors(['email' => 'We could not send the email right now. Please try again in a moment.']);
        }

        session(['password_reset_email' => $user->email_id]);

        $message = !empty($sent['email'])
            ? 'Verification code sent to your email.'
            : 'We could not email the code, so we sent it to your registered mobile number.';

        return redirect()->route('password.verify')->with('success', $message);
    }

    public function showVerificationForm()
    {
        if (!session('password_reset_email')) {
            return redirect()->route('password.forgot')->withErrors([
                'email' => 'Enter your email first so we can send a verification code.',
            ]);
        }

        return view('site.password.verify-code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|numeric|digits:4',
        ], [
            'verification_code.required' => 'Enter the 4-digit code from your email.',
            'verification_code.digits' => 'The code must be 4 digits.',
        ]);

        $email = session('password_reset_email');
        if (!$email) {
            return redirect()->route('password.forgot')->withErrors([
                'email' => 'Enter your email first so we can send a verification code.',
            ]);
        }

        $user = User::where('email_id', $email)
                    ->where('verification_code', $request->verification_code)
                    ->where('verification_code_expiry', '>', now())
                    ->first();

        if ($user) {
            $hashedUserId = md5($user->id);
            return redirect()->route('password.reset', ['user_id' => $hashedUserId]);
        }

        return back()->withErrors([
            'verification_code' => 'That code is invalid or has expired.',
        ])->withInput();
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
            'password' => 'required|string|min:4|confirmed',
        ], [
            'password.required' => 'Enter a new password.',
            'password.min' => 'Password must be at least 4 characters.',
            'password.confirmed' => 'Passwords do not match.',
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

        session()->forget('password_reset_email');

        return redirect()->route('login')->with('success', 'Password updated. You can log in with your new password.');
    }
}
