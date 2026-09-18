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
	<!-- Header -->
	<div style="padding: 30px 0px;">
		<div style='width: 34%;float: left;text-transform: uppercase;font-size: 30px;font-family: "Calibri-Bold";margin-top: 30px;'>Tax Invoice</div>
		<div style="width: 34%;float: left;">
			<img src="{{ asset('') }}img/logo.png" alt="">
		</div>
		<div style="width: 32%;float: left;margin-top: 4px;">
			<span style='text-transform: uppercase;font-family: "Calibri-Light";color: #626262;'>{{$details->hotel_name}}</span> <br>
			{{$details->address}}
		</div>
		<div style="clear: both;"></div>
	</div>

	<div style="border-bottom: 1px dotted #000;padding-bottom: 12px;">
		<div style='width: 31%;float: left;'>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Booking ID</div>
				<div>FOURSQUARE{{$details->id}}</div>
			</div>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Invoice No.</div>
				<div>FOURSQUARE{{$details->id}}</div>
			</div>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Date</div>
				<div>{{date('D d M Y', strtotime($details->created_at))}}</div>
			</div>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Place of Supply</div>
				<div>WEST BENGAL</div>
			</div>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Transactional Type/Category</div>
				<div>REG/B2C</div>
			</div>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Transactional Details</div>
				<div>RG</div>
			</div>
		</div>
		<div style='width: 38%;float: left;'>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>PAN</div>
				<div>{{$details->pan_number}}</div>
			</div>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>HSN/SAC</div>
				<div>{{$details->hsn_number}}</div>
			</div>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>GSTIN</div>
				<div>{{$details->gst_number}}</div>
			</div>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>CIN</div>
				<div>{{$details->cin_number}}</div>
			</div>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Service Description</div>
				<div>{{$details->service_description}}</div>
			</div>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Tax Payable under RCM</div>
				<div>{{$details->tax_payale_rcm}}</div>
			</div>
		</div>
		<div style="width: 31%;float: left;text-align: right;margin-top: 30px;">
			<img src="{{asset('')}}img/qr.jpg" alt="" style="width: 180px;">
		</div>
		<div style="clear: both;"></div>
	</div>

	<div style="border-bottom: 1px dotted #000;padding: 12px 0px;">
		<div style='width: 31%;float: left;'>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Customer Name</div>
				<div>{{$details->title}} {{$details->first_name}} {{$details->last_name}}</div>
			</div>
		</div>
		<div style='width: 38%;float: left;'>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Customer Email</div>
				<div>{{$details->email_id}}</div>
			</div>
		</div>
		<div style="width: 31%;float: left;">
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Customer Phone Number</div>
				<div>{{$details->mobile_number}}</div>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>

	<div style="padding: 12px 0px;">
		<div style='width: 24%;float: left;'>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Hotel Name</div>
				<div>{{$details->hotel_name}}</div>
			</div>
		</div>
		<div style='width: 16%;float: left;'>
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Hotel City</div>
				<div>{{$details->place}}</div>
			</div>
		</div>
		<div style="width: 30%;float: left;">
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Check-in</div>
				<div>{{date('D d M Y', strtotime($details->check_in_date))}} {{$details->check_in_time}}</div>
			</div>
		</div>
		<div style="width: 30%;float: left;">
			<div style="margin-bottom: 10px;">
				<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Check-Out</div>
				<div>{{date('D d M Y', strtotime($details->check_out_date))}} {{$details->check_out_time}}</div>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>

	<div style="border-top: 2px dotted #000;margin: 20px 0px 10px;">
		<div style="text-align: center;margin: -12px auto 0px;background: #fff;width: 150px;">PAYMENT BREAKUP</div>
	</div>

	<div style="border: 1px solid #000;border-radius: 10px;">
		<div style="border-bottom: 1px solid #000;padding: 12px 14px;">
			<div style="margin-bottom: 10px;">
				<div style="width: 80%;float: left;">
					<div>*Accomodation Charges</div>
					<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>(Inclusive of applicable taxes collected on behalf of hotel)</div>
				</div>
				<div style="width: 20%;float: left;text-align: right;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$details->total_price}}</div>
				<div style="clear: both;"></div>
			</div>
			<div style="margin-bottom: 10px;">
				<div style="width: 80%;float: left;">
					<div>Total Discount</div>
				</div>
				<div style="width: 20%;float: left;text-align: right;color: #ff0000;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$details->total_discount}}</div>
				<div style="clear: both;"></div>
			</div>
			<div style="margin-bottom: 10px;">
				<div style="width: 80%;float: left;">
					<div>Total Tax</div>
				</div>
				<div style="width: 20%;float: left;text-align: right;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$details->total_taxes}}</div>
				<div style="clear: both;"></div>
			</div>
			<!-- <div>
				<div style="width: 80%;float: left;">
					<div>Effective discount</div>
				</div>
				<div style="width: 20%;float: left;text-align: right;color: #ff0000;">₹-149.0</div>
				<div style="clear: both;"></div>
			</div> -->
		</div>
		<div style="padding: 12px 14px;">
			<div style="width: 80%;float: left;">
				<div>Grand Total</div>
			</div>
			<div style="width: 20%;float: left;text-align: right;color: #ff0000;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$details->total_amount}}</div>
			<div style="clear: both;"></div>
		</div>
	</div>

	<div style="margin-top:10px;">Input tax credit of GST charged by the original service provider is available only against the invoice issued by the respective service provider. Four Square acts only as a facilitator for these services.</div>
	<div>This is not a valid travel document</div>

	<div style="border-top: 2px dotted #000;margin: 20px 0px 10px;">
		<div style="text-align: center;margin: -12px auto 0px;background: #fff;width: 166px;">TERMS & CONDITIONS</div>
	</div>
	<div>
		<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>1. Any dispute with respect to the invoice is to be reported back to MMT/GOIBIBO within 48 hours of receipt of invoice.</div>
		<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>2. QR code for B2B and SEZ category invoices can only be scanned using app downloaded from the link <br> <a href="https://einvoice1.gst.gov.in/Others/QRCodeVerifyApp" style="color: #626262;">https://einvoice1.gst.gov.in/Others/QRCodeVerifyApp</a></div>
	</div>


	<br><br><br><br><br><br><br><br><br><br><br>


	<!-- footer -->
	<div>
		<div style="width: 80%;float: left;padding: 30px 0px;">
			<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>Registered Office</div>
			<div>P-144, CIT Road, Scheme –VI M, Kankurgachi, Near APC Park, Kolkata-700054 </div>
		</div>
		<div style="width: 20%;float: left;padding: 30px 0px;text-align:right;">
			<div style='font-family: "Calibri-Light";color: #626262;font-size:14px;'>M06HL25I01725017</div>
			<div>Page 1 of 1</div>
		</div>
		<div style="clear: both;"></div>
	</div>
</body>
</html>