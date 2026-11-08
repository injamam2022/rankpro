<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PasswordResetNotifier
{
    public static function send(User $user, string $resetUrl, string $otp): array
    {
        $emailSent = self::sendResetEmail($user, $resetUrl);
        $smsSent = self::sendSms($user, $otp);

        return [
            'ok' => $emailSent || $smsSent,
            'email' => $emailSent,
            'sms' => $smsSent,
        ];
    }

    public static function sendSms(User $user, string $otp): bool
    {
        $digits = self::internationalMobile($user->mobile_number ?? '');
        if (!$digits) {
            Log::warning('Password reset SMS skipped: no valid mobile on user '.$user->id);
            return false;
        }

        $key = config('services.twofactor.api_key');
        $template = config('services.twofactor.otp_template', 'OTPtemplate');
        if (!$key) {
            Log::error('Password reset SMS skipped: missing TWOFACTOR_API_KEY');
            return false;
        }

        try {
            $response = Http::timeout(15)->get('https://2factor.in/API/V1/'.$key.'/SMS/+'.$digits.'/'.$otp.'/'.$template);
            if ($response->successful() && ($response->json('Status') === 'Success')) {
                return true;
            }
            Log::error('Password reset SMS failed: HTTP '.$response->status().' '.$response->body());
        } catch (Throwable $e) {
            Log::error('Password reset SMS failed: '.$e->getMessage());
        }

        return false;
    }

    public static function maskEmail(?string $email): string
    {
        $email = trim((string) $email);
        if (!str_contains($email, '@')) {
            return '';
        }
        [$name, $domain] = explode('@', $email, 2);
        $keep = min(2, max(1, strlen($name)));
        return substr($name, 0, $keep).str_repeat('*', max(strlen($name) - $keep, 3)).'@'.$domain;
    }

    public static function maskMobile(?string $mobile): string
    {
        $digits = preg_replace('/\D+/', '', (string) $mobile);
        if (strlen($digits) < 4) {
            return '';
        }
        return str_repeat('*', max(strlen($digits) - 4, 4)).substr($digits, -4);
    }

    private static function sendResetEmail(User $user, string $resetUrl): bool
    {
        $to = trim((string) $user->email_id);
        if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            Log::error('Password reset email skipped: invalid address for user '.$user->id);
            return false;
        }

        $from = config('mail.from.address') ?: 'no-reply@rankpro.co.in';
        $fromName = config('mail.from.name') ?: 'RankPro';
        $name = trim((string) ($user->first_name ?? 'there'));
        if ($name === '') {
            $name = 'there';
        }

        try {
            Mail::send('emails.password_reset_link', [
                'name' => $name,
                'resetUrl' => $resetUrl,
                'expiresMinutes' => 60,
            ], function ($message) use ($to, $from, $fromName) {
                $message->from($from, $fromName)
                    ->to($to)
                    ->subject('Reset your RankPro password');
            });

            return true;
        } catch (Throwable $e) {
            Log::error('Password reset SMTP failed: '.$e->getMessage());
            return false;
        }
    }

    private static function internationalMobile(string $mobile): ?string
    {
        $digits = preg_replace('/\D+/', '', $mobile) ?: '';
        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }
        if (strlen($digits) === 10) {
            $digits = '91'.$digits;
        }
        if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
            return $digits;
        }

        return null;
    }
}
