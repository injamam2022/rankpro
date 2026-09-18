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
	<body style="margin: 0px;font-family: 'Calibri';width: 700px; margin: 0px auto;">
		<header style="height: 60px;">
			<div style="">
				<img src="{{ asset('') }}web/images/logo.png" alt="RankPro - Educational Advisory solutions" width="100px" style="">
			</div>
		</header>
		@php $page_count = 1; @endphp
		<div style="height: 920px;">
			@foreach($subject_list as $k1=>$v1)
				
				<table style="width: 100%;border: 1px solid black; border-collapse: collapse;margin-top: 20px;">
					<tr>
						<td colspan="10" align="center" style="border: 1px solid black;">{{$v1->subject_name}}</td>
					</tr>
					<tr>

						@foreach($v1->question_list as $k2=>$v2)
							@if((($k2) % 10) == 0 && $k2 != 0)
								</tr>

								<tr>

							@endif
							<td  style="border: 1px solid black;width: 20%;">
								<div>
									{{$k2+1}} - {{$v2->answer}}
								</div>
							</td>
						@endforeach
					</tr>
				</table>

				@if(count($subject_list) > $page_count)
					</div>

					<footer style="height: 30px;">
						<table  style="width: 100%;">
							<tr>
								<td style="width:20%;" align="left">{{$page_count}} | Page</td>
								<td style="width:60%;" align="center">{{url('')}}</td>
								<td style="width:20%;" align="right"></td>
							</tr>
						</table>
					</footer>

					<div class="page-break"></div>


					<header style="height: 60px;">
						<div style="">
							<img src="{{ asset('') }}web/images/logo.png" alt="RankPro - Educational Advisory solutions" width="100px" style="">
						</div>
					</header>
					<div style="height: 920px;">
					@php $page_count = $page_count+1; @endphp
				@endif
			@endforeach
		</div>

		<footer style="height: 30px;">
			<table  style="width: 100%;">
				<tr>
					<td style="width:20%;" align="left">{{$page_count}} | Page</td>
					<td style="width:60%;" align="center">{{url('')}}</td>
					<td style="width:20%;" align="right"></td>
				</tr>
			</table>
		</footer>
	</body>
</html>