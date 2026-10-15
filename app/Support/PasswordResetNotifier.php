<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PasswordResetNotifier
{
    public static function send(User $user, string $code): array
    {
        $emailSent = self::sendEmail($user, $code);
        $smsSent = false;

        if (!$emailSent) {
            $smsSent = self::sendSms($user, $code);
        }

        return [
            'ok' => $emailSent || $smsSent,
            'email' => $emailSent,
            'sms' => $smsSent,
        ];
    }

    private static function sendEmail(User $user, string $code): bool
    {
        $to = (string) $user->email_id;
        $from = config('mail.from.address') ?: 'no-reply@rankpro.co.in';
        $fromName = config('mail.from.name') ?: 'RankPro';
        $subject = 'RankPro password reset code';

        $send = function ($mailer) use ($user, $code, $to, $from, $fromName, $subject) {
            $factory = $mailer ? Mail::mailer($mailer) : Mail::mailer();
            $factory->send('emails.verification_code', ['verificationCode' => $code], function ($message) use ($to, $from, $fromName, $subject) {
                $message->from($from, $fromName)
                    ->to($to)
                    ->subject($subject);
            });
        };

        try {
            $send(null);
            return true;
        } catch (Throwable $e) {
            Log::error('Password reset SMTP failed: '.$e->getMessage());
        }

        try {
            $send('sendmail');
            return true;
        } catch (Throwable $e) {
            Log::error('Password reset sendmail failed: '.$e->getMessage());
        }

        try {
            $html = view('emails.verification_code', ['verificationCode' => $code])->render();
            $headers = implode("\r\n", [
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=UTF-8',
                'From: '.$fromName.' <'.$from.'>',
                'Reply-To: '.$from,
            ]);
            if (@mail($to, $subject, $html, $headers)) {
                return true;
            }
            Log::error('Password reset php mail() returned false for '.$to);
        } catch (Throwable $e) {
            Log::error('Password reset php mail() failed: '.$e->getMessage());
        }

        return false;
    }

    private static function sendSms(User $user, string $code): bool
    {
        $digits = preg_replace('/\D+/', '', (string) ($user->mobile_number ?? ''));
        if (strlen($digits) === 10) {
            $digits = '91'.$digits;
        }
        if (strlen($digits) < 12) {
            Log::error('Password reset SMS skipped: no valid mobile on user '.$user->id);
            return false;
        }

        $key = config('services.twofactor.api_key');
        $template = config('services.twofactor.otp_template', 'OTPtemplate');
        if (!$key) {
            return false;
        }

        try {
            $response = Http::timeout(15)->get('https://2factor.in/API/V1/'.$key.'/SMS/+'.$digits.'/'.$code.'/'.$template);
            if ($response->successful()) {
                return true;
            }
            Log::error('Password reset SMS failed: HTTP '.$response->status().' '.$response->body());
        } catch (Throwable $e) {
            Log::error('Password reset SMS failed: '.$e->getMessage());
        }

        return false;
    }
}
