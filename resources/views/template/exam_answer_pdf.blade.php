<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title></title>
	    <style>
			.page-break {
			    page-break-after: always;
			}
		</style>
	</head>
	<body style="margin: 0px;font-family: DejaVu Sans, sans-serif;width: 700px; margin: 0px auto; font-size: 12px;">
		<header style="height: 60px;">
			<div style="">
				@if(!empty($pdf_logo))
					<img src="{{ $pdf_logo }}" alt="RankPro" width="100" style="width:100px;height:auto;">
				@endif
			</div>
		</header>
		@php $page_count = 1; @endphp
		@php $global_q = 1; @endphp
		<div>
			@foreach($subject_list as $k1=>$v1)
				<table style="width: 100%;border: 1px solid black; border-collapse: collapse;margin-top: 20px;">
					<tr>
						<td colspan="10" align="center" style="border: 1px solid black; padding: 6px; font-weight: 600;">{{ $v1->subject_name }} — {{ $details->name }}</td>
					</tr>
					<tr>
						@foreach($v1->question_list as $k2=>$v2)
							@if((($k2) % 10) == 0 && $k2 != 0)
								</tr>
								<tr>
							@endif
							<td style="border: 1px solid black;width: 10%; padding: 6px; text-align: center;">
								{{ $global_q }} - {{ $v2->answer }}
								@php $global_q++; @endphp
							</td>
						@endforeach
					</tr>
				</table>
			@endforeach
		</div>

		<footer style="height: 30px;">
			<table  style="width: 100%;">
				<tr>
					<td style="width:20%;" align="left">{{$page_count}} | Page</td>
					<td style="width:60%;" align="center">RankPro</td>
					<td style="width:20%;" align="right"></td>
				</tr>
			</table>
		</footer>
	</body>
</html>