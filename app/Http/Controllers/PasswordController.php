<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\PasswordResetNotifier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    private const LINK_MINUTES = 60;
    private const OTP_MINUTES = 10;

    public function showForgotPasswordForm()
    {
        return view('site.password.forgot-password');
    }

    public function sendVerificationCode(Request $request)
    {
        $email = strtolower(trim((string) $request->input('email')));
        $mobile = preg_replace('/\D+/', '', (string) $request->input('mobile')) ?: '';
        $request->merge([
            'email' => $email,
            'mobile' => $mobile,
        ]);

        if ($email === '' && $mobile === '') {
            return back()->withInput()->withErrors([
                'email' => 'Enter the email or mobile number on your account.',
            ]);
        }

        if ($email !== '') {
            $request->validate([
                'email' => 'email',
            ], [
                'email.email' => 'Enter a valid email address.',
            ]);
        }

        if ($mobile !== '' && strlen($mobile) !== 10) {
            return back()->withInput()->withErrors([
                'mobile' => 'Enter a valid 10-digit mobile number.',
            ]);
        }

        $user = $this->findActiveUser($email, $mobile);
        if (!$user) {
            return back()->withInput()->withErrors([
                'email' => 'We could not find an account with those details.',
            ]);
        }

        $otp = (string) random_int(1000, 9999);
        $user->verification_code = $otp;
        $user->verification_code_expiry = now()->addMinutes(self::OTP_MINUTES);
        $user->save();

        $token = $this->issueResetToken($user);
        $resetUrl = url()->route('password.reset', ['token' => $token], true);

        $sent = PasswordResetNotifier::send($user, $resetUrl, $otp);

        if (empty($sent['ok'])) {
            return back()->withInput()->withErrors([
                'email' => 'We could not send the reset details right now. Please try again in a moment.',
            ]);
        }

        $this->storeResetSession($user, $sent);

        $message = $this->sentMessage($user, $sent);

        return redirect()->route('password.verify')->with('success', $message);
    }

    public function showVerificationForm()
    {
        if (!session('password_reset_user_id')) {
            return redirect()->route('password.forgot')->withErrors([
                'email' => 'Enter your email or mobile number first so we can send a reset link.',
            ]);
        }

        return view('site.password.verify-code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|numeric|digits:4',
        ], [
            'verification_code.required' => 'Enter the 4-digit OTP sent to your mobile.',
            'verification_code.digits' => 'The OTP must be 4 digits.',
        ]);

        $userId = session('password_reset_user_id');
        if (!$userId) {
            return redirect()->route('password.forgot')->withErrors([
                'email' => 'Enter your email or mobile number first so we can send a reset link.',
            ]);
        }

        $user = User::where('id', $userId)
            ->where('status', 1)
            ->where('verification_code', $request->verification_code)
            ->where('verification_code_expiry', '>', now())
            ->first();

        if (!$user) {
            return back()->withErrors([
                'verification_code' => 'That OTP is invalid or has expired.',
            ])->withInput();
        }

        $token = $this->issueResetToken($user);

        return redirect()->route('password.reset', ['token' => $token]);
    }

    public function resendOtp(Request $request)
    {
        $userId = session('password_reset_user_id');
        $user = $userId ? User::where('id', $userId)->where('status', 1)->first() : null;

        if (!$user) {
            return redirect()->route('password.forgot')->withErrors([
                'email' => 'Enter your email or mobile number first so we can send a reset link.',
            ]);
        }

        $otp = (string) random_int(1000, 9999);
        $user->verification_code = $otp;
        $user->verification_code_expiry = now()->addMinutes(self::OTP_MINUTES);
        $user->save();

        if (!PasswordResetNotifier::sendSms($user, $otp)) {
            return back()->with('error', 'We could not send the OTP right now. Please try again in a moment.');
        }

        session(['password_reset_sms_sent' => true]);

        return back()->with('success', 'OTP sent to '.PasswordResetNotifier::maskMobile($user->mobile_number).'.');
    }

    public function showResetPasswordForm(Request $request, $token)
    {
        $user = $this->userFromResetToken($token);
        if (!$user) {
            return redirect()->route('password.forgot')->with('error', 'That reset link is invalid or has expired. Request a new one.');
        }

        return view('site.password.reset-password', compact('user', 'token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:4|confirmed',
        ], [
            'password.required' => 'Enter a new password.',
            'password.min' => 'Password must be at least 4 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $user = $this->userFromResetToken($request->token);
        if (!$user) {
            return redirect()->route('password.forgot')->with('error', 'That reset link is invalid or has expired. Request a new one.');
        }

        $user->password = Hash::make($request->password);
        $user->verification_code = null;
        $user->verification_code_expiry = null;
        $user->save();

        DB::table('password_resets')->where('email', $user->email_id)->delete();
        $this->forgetResetSession();

        return redirect()->route('login')->with('success', 'Password updated. You can log in with your new password.');
    }

    private function findActiveUser(string $email, string $mobile): ?User
    {
        $query = User::where('status', 1);

        if ($email !== '') {
            $query->where('email_id', $email);
        }

        if ($mobile !== '') {
            $query->where(function ($mobileQuery) use ($mobile) {
                $mobileQuery->where('mobile_number', $mobile)
                    ->orWhere('mobile_number', '91'.$mobile)
                    ->orWhere('mobile_number', '+91'.$mobile);
            });
        }

        return $query->first();
    }

    private function issueResetToken(User $user): string
    {
        $token = Str::random(64);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email_id],
            [
                'token' => hash('sha256', $token),
                'created_at' => now(),
            ]
        );

        return $token;
    }

    private function userFromResetToken(string $token): ?User
    {
        if ($token === '') {
            return null;
        }

        $record = DB::table('password_resets')
            ->where('token', hash('sha256', $token))
            ->first();

        if (!$record || empty($record->created_at)) {
            return null;
        }

        if (Carbon::parse($record->created_at)->addMinutes(self::LINK_MINUTES)->isPast()) {
            return null;
        }

        return User::where('email_id', $record->email)->where('status', 1)->first();
    }

    private function storeResetSession(User $user, array $sent): void
    {
        session([
            'password_reset_user_id' => $user->id,
            'password_reset_email' => $user->email_id,
            'password_reset_email_masked' => PasswordResetNotifier::maskEmail($user->email_id),
            'password_reset_mobile_masked' => PasswordResetNotifier::maskMobile($user->mobile_number),
            'password_reset_email_sent' => !empty($sent['email']),
            'password_reset_sms_sent' => !empty($sent['sms']),
        ]);
    }

    private function forgetResetSession(): void
    {
        session()->forget([
            'password_reset_user_id',
            'password_reset_email',
            'password_reset_email_masked',
            'password_reset_mobile_masked',
            'password_reset_email_sent',
            'password_reset_sms_sent',
        ]);
    }

    private function sentMessage(User $user, array $sent): string
    {
        $email = PasswordResetNotifier::maskEmail($user->email_id);
        $mobile = PasswordResetNotifier::maskMobile($user->mobile_number);

        if (!empty($sent['email']) && !empty($sent['sms'])) {
            return 'We emailed a reset link to '.$email.' and sent an OTP to '.$mobile.'.';
        }
        if (!empty($sent['email'])) {
            return 'We emailed a reset link to '.$email.'. Open that email to choose a new password.';
        }

        return 'We sent an OTP to '.$mobile.'. Enter it below to choose a new password.';
    }
}
