<html>
	<head>
		<title>Password Reset</title>
	</head>
	<body>
	    <p>Dear {{$name}},</p>
	    <table>
			<tr><td>Reset Password Link : {{route('frontend.reset_password')}}?remember_token={{ $remember_token }}</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>Thanks & Regards,</td></tr>
			<tr><td>Team</td></tr>
	</body>
</html>