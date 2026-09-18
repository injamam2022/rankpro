<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
</head>
		<style type="text/css" media="screen">
			@font-face {
			  font-family: "Calibri";
			  src: url("{{ storage_path('fonts/CalibriRegular.eot') }}"); /* IE9 Compat Modes */
			  src: url("{{ storage_path('fonts/CalibriRegular.eot?#iefix') }}") format("embedded-opentype"), /* IE6-IE8 */
			    url("{{ storage_path('fonts/CalibriRegular.otf') }}") format("opentype"), /* Open Type Font */
			    url("{{ storage_path('fonts/CalibriRegular.svg') }}") format("svg"), /* Legacy iOS */
			    url("{{ storage_path('fonts/CalibriRegular.ttf') }}") format("truetype"), /* Safari, Android, iOS */
			    url("{{ storage_path('fonts/CalibriRegular.woff') }}") format("woff"), /* Modern Browsers */
			    url("{{ storage_path('fonts/CalibriRegular.woff2') }}") format("woff2"); /* Modern Browsers */
			  font-weight: normal;
			  font-style: normal;
			}
			@font-face {
			  font-family: "Calibri-Bold";
			  src: url("{{ storage_path('fonts/CalibriBold.eot') }}"); /* IE9 Compat Modes */
			  src: url("{{ storage_path('fonts/CalibriBold.eot?#iefix') }}") format("embedded-opentype"), /* IE6-IE8 */
			    url("{{ storage_path('fonts/CalibriBold.otf') }}") format("opentype"), /* Open Type Font */
			    url("{{ storage_path('fonts/CalibriBold.svg') }}") format("svg"), /* Legacy iOS */
			    url("{{ storage_path('fonts/CalibriBold.ttf') }}") format("truetype"), /* Safari, Android, iOS */
			    url("{{ storage_path('fonts/CalibriBold.woff') }}") format("woff"), /* Modern Browsers */
			    url("{{ storage_path('fonts/CalibriBold.woff2') }}") format("woff2"); /* Modern Browsers */
			  font-weight: normal;
			  font-style: normal;
			}
			@font-face {
			  font-family: "Calibri-Light";
			  src: url("{{ storage_path('fonts/CalibriLight.eot') }}"); /* IE9 Compat Modes */
			  src: url("{{ storage_path('fonts/CalibriLight.eot?#iefix') }}") format("embedded-opentype"), /* IE6-IE8 */
			    url("{{ storage_path('fonts/CalibriLight.otf') }}") format("opentype"), /* Open Type Font */
			    url("{{ storage_path('fonts/CalibriLight.svg') }}") format("svg"), /* Legacy iOS */
			    url("{{ storage_path('fonts/CalibriLight.ttf') }}") format("truetype"), /* Safari, Android, iOS */
			    url("{{ storage_path('fonts/CalibriLight.woff') }}") format("woff"), /* Modern Browsers */
			    url("{{ storage_path('fonts/CalibriLight.woff2') }}") format("woff2"); /* Modern Browsers */
			  font-weight: normal;
			  font-style: normal;
			}
		</style>
<body style="margin: 0px;font-family: 'Calibri';width: 700px; margin: 0px auto;">
	<div>
		<img src="{{asset('')}}booking_pdf_img/headerImg2.jpg" alt="">
	</div>
	<div style="background-color: #fff;padding: 30px 30px 0px;">
		<div style="font-family: 'Calibri-Light';font-size: 18px;color: #000;">
			Dear {{$details->title}} {{$details->first_name}} {{$details->last_name}},
			<br><br>
			This is with reference to the <span style="color: #ff0000;">cancellation request</span> for your <br>
			<span style="font-family: 'Calibri-Bold';">Booking ID - FOURSQUARE{{$details->id}}</span>
			<br><br>
			As per your request for refund, we raised request with hotel it will take some 
			time to get confirmation from hotel side. We request you to kindly provide us 
			some time we are working on your request on priority bases. 
			<br><br>
			We appreciate your understanding and look forward to assist you in future.
			Please feel free to contact us at our 
		</div>
	</div>
	<div style="background-color: #fff;padding: 20px 50px 26px;">
		<div style="padding: 0px 0px;border: 1px solid #b2b2b2;border-radius: 12px;margin-top: 15px;background-color: #ffdddd;">
			<div style="padding: 10px 60px;">
				<div style="text-align: center;width: 40%;float: left;">
					<div style="font-size: 14px;color: #000;">CHECK IN</div>
					<div style="font-size: 20px;color: #edac0f;margin-top: -5px;">{{date('D d M Y', strtotime($details->check_in_date))}}</div>
				</div>
				<div style="text-align: center;width: 20%;float: left;margin-top: 16px;">
					<img src="{{asset('')}}booking_pdf_img/stars.png" alt="">
				</div>
				<div style="text-align: center;width: 40%;float: left;">
					<div style="font-size: 14px;color: #000;">CHECK OUT</div>
					<div style="font-size: 20px;color: #edac0f;margin-top: -5px;">{{date('D d M Y', strtotime($details->check_out_date))}}</div>
				</div>
				<div style="clear: both;"></div>
			</div>
			<div style="font-size: 16px;color: #000;text-align: center;background: #f2f2f2;border-radius: 12px;padding: 4px 5px 10px;width: 340px;margin: 0px auto 14px;"><span style="font-size: 20px;">{{$details->days}} Nights  |</span>  {{$details->adults_count}} Adults, {{$details->children_count}} Child ({{$child_age}} Year)  |  {{$details->rooms_count}} Room</div>
		</div>

		<div style="font-size: 18px;color: #312a60;text-align: center;margin-top: 26px;">We hope to see you again soon!</div>
		</div>
		<div style="font-size: 29px;text-align: center;color: #312a60;background-color: #edac0f;padding: 8px 15px 15px;">Thank you, From Four Square Hotels</div>

		<div style="font-family: 'Calibri';padding: 20px 0px; font-size: 13px;color: #231f20; text-align: center;">
			This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message.
		</div>
</body>
</html>