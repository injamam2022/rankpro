<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset your RankPro password</title>
</head>
<body style="margin:0; padding:0; background:#f4f6fb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f6fb; padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px; background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:#3561ff; padding:20px 28px; color:#ffffff; font-size:20px; font-weight:700;">
                            RankPro
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 12px; font-size:16px;">Hello {{ $name }},</p>
                            <p style="margin:0 0 20px; font-size:15px; line-height:1.6; color:#3f3f46;">
                                We received a request to reset the password for your RankPro account. Click the button below to choose a new password. This link expires in {{ $expiresMinutes }} minutes.
                            </p>
                            <p style="margin:0 0 24px; text-align:center;">
                                <a href="{{ $resetUrl }}" style="display:inline-block; background:#3561ff; color:#ffffff; text-decoration:none; font-weight:700; font-size:15px; padding:12px 28px; border-radius:10px;">
                                    Reset password
                                </a>
                            </p>
                            <p style="margin:0 0 8px; font-size:13px; color:#71717a;">If the button does not work, copy and paste this link into your browser:</p>
                            <p style="margin:0 0 20px; font-size:13px; line-height:1.5; word-break:break-all; color:#3561ff;">{{ $resetUrl }}</p>
                            <p style="margin:0; font-size:13px; color:#71717a;">If you did not ask to reset your password, you can ignore this email.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
