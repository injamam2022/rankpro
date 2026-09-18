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
			
            p {
                margin:0px;
            }
		</style>
	</head>
	<body style="margin: 0px;font-family: "DejaVu Sans", sans-serif;width: 700px; margin: 0px auto;">
		@php $question_count = 1; @endphp
		@php $page_count = 1; @endphp
		@foreach($question_list as $k1=>$v1)

			<header style="height: 30px;">
				<div style="">
					<img src="{{ asset('') }}web/images/logo.png" alt="RankPro - Educational Advisory solutions" width="100px" style="">
				</div>
				<div style="background-color: #fff;">
					
				</div>
			</header>
			<div style="height: 950px;">
			
				<table style="width: 100%;border: 0px solid black; border-collapse: collapse;margin-top: 20px;">
					<tr style="background:#E7E6E6;">
						<td style="width:33%; font-size:14px; font-weight: 600; text-transform: uppercase; padding: 5px 20px;" align="left">{{$details->name}}</td>
						<td style="width:34%; font-size:14px; font-weight: 600; text-transform: uppercase; padding: 5px 20px;" align="center">{{$v1['subject_name']}}</td>
						<td style="width:33%; font-size:14px; font-weight: 600; padding: 5px 20px;" align="right">Time –  {{$details->total_time_for_exam}} Min</td>
					</tr>
				</table>
				<table style="width: 100%;border: 0px solid black; border-collapse: collapse;margin-top: 0px;">
					<tr>
						<td  style="border-right: 1px solid;width: 50%; padding: 0px 15px;">
							@foreach($v1['question_list_left'] as $k2=>$v2)
								<div>
									<div>
									    <table  style="width: 100%;">
									        <tr>
									            <td style="width:10%;">
									                {{$question_count}}.
									            </td>
									            <td style="width:90%;">
									                {!!$v2->question_text!!}
									                
									                @if(!empty($v2->question_image) && $v2->question_image != NULL)
                    									<div>
                    										<img src="{{ asset('') }}uploads/question/{{$v2->question_image}}" style="width:100px; padding: 5px 0px;">
                										</div>
                									@endif
									            </td>
									        </tr>
									    </table>
										 
									</div>
                									
									<div style="padding-left: 40px;">
										<table  style="width: 100%;">
											<tr>
												<td style="width:50%;display: flex;" align="left">
												    <table  style="width: 100%;">
												        <tr>
												            <td style="width:10%;">
												                (1)
												            </td>
												            <td style="width:90%;">
												                @if($v2->is_option1_image)
            														<div style="margin: -15px 0px 10px 25px;"><img src="{{ asset('') }}uploads/question/{{$v2->option1}}" style="width:100px;"></div>
            													@else
            														{!!$v2->option1!!}
            													@endif
												            </td>
												        </tr>
												    </table>
												</td>
											</tr>
											<tr>
												<td style="width:50%;display: flex;" align="left">
												    <table  style="width: 100%;">
												        <tr>
												            <td style="width:10%;">
												                (2)
												            </td>
												            <td style="width:90%;">
												                @if($v2->is_option2_image)
            														<div style="margin: -15px 0px 10px 25px;"><img src="{{ asset('') }}uploads/question/{{$v2->option2}}" style="width:100px;"></div>
            													@else
            														{!!$v2->option2!!}
            													@endif
												            </td>
												        </tr>
												    </table>
												</td>
											</tr>
											<tr>
												<td style="width:50%;display: flex;" align="left">
												    <table  style="width: 100%;">
												        <tr>
												            <td style="width:10%;">
												                (3)
												            </td>
												            <td style="width:90%;">
												                @if($v2->is_option3_image)
            														<div style="margin: -15px 0px 10px 25px;"><img src="{{ asset('') }}uploads/question/{{$v2->option3}}" style="width:100px;"></div>
            													@else
            														{!!$v2->option3!!}
            													@endif
												            </td>
												        </tr>
												    </table>
												</td>
											</tr>
											<tr>
												<td style="width:50%;display: flex;" align="left">
												    <table  style="width: 100%;">
												        <tr>
												            <td style="width:10%;">
												                (4)
												            </td>
												            <td style="width:90%;">
												                @if($v2->is_option4_image)
            														<div style="margin: -15px 0px 10px 25px;"><img src="{{ asset('') }}uploads/question/{{$v2->option1}}" style="width:100px;"></div>
            													@else
            														{!!$v2->option4!!}
            													@endif
												            </td>
												        </tr>
												    </table>
												</td>
											</tr>
										</table>
									</div>
								</div>
								@php $question_count = $question_count + 1; @endphp
							@endforeach
						</td>
						<td  style="border-right: 0px solid;width: 50%; padding: 0px 15px;">
							@foreach($v1['question_list_right'] as $k2=>$v2)
								<div>
									<div>
									    <table  style="width: 100%;">
									        <tr>
									            <td style="width:10%;">
									                {{$question_count}}.
									            </td>
									            <td style="width:90%;">
									                {!!$v2->question_text!!}
									                
									                @if(!empty($v2->question_image) && $v2->question_image != NULL)
                    									<div>
                    										<img src="{{ asset('') }}uploads/question/{{$v2->question_image}}" style="width:100px; padding: 5px 0px;">
                										</div>
                									@endif
									            </td>
									        </tr>
									    </table>
										 
									</div>
                									
									<div style="padding-left: 40px;">
										<table  style="width: 100%;">
											<tr>
												<td style="width:50%;display: flex;" align="left">
												    <table  style="width: 100%;">
												        <tr>
												            <td style="width:10%;">
												                (1)
												            </td>
												            <td style="width:90%;">
												                @if($v2->is_option1_image)
            														<div style="margin: -15px 0px 10px 25px;"><img src="{{ asset('') }}uploads/question/{{$v2->option1}}" style="width:100px;"></div>
            													@else
            														{!!$v2->option1!!}
            													@endif
												            </td>
												        </tr>
												    </table>
												</td>
											</tr>
											<tr>
												<td style="width:50%;display: flex;" align="left">
												    <table  style="width: 100%;">
												        <tr>
												            <td style="width:10%;">
												                (2)
												            </td>
												            <td style="width:90%;">
												                @if($v2->is_option2_image)
            														<div style="margin: -15px 0px 10px 25px;"><img src="{{ asset('') }}uploads/question/{{$v2->option2}}" style="width:100px;"></div>
            													@else
            														{!!$v2->option2!!}
            													@endif
												            </td>
												        </tr>
												    </table>
												</td>
											</tr>
											<tr>
												<td style="width:50%;display: flex;" align="left">
												    <table  style="width: 100%;">
												        <tr>
												            <td style="width:10%;">
												                (3)
												            </td>
												            <td style="width:90%;">
												                @if($v2->is_option3_image)
            														<div style="margin: -15px 0px 10px 25px;"><img src="{{ asset('') }}uploads/question/{{$v2->option3}}" style="width:100px;"></div>
            													@else
            														{!!$v2->option3!!}
            													@endif
												            </td>
												        </tr>
												    </table>
												</td>
											</tr>
											<tr>
												<td style="width:50%;display: flex;" align="left">
												    <table  style="width: 100%;">
												        <tr>
												            <td style="width:10%;">
												                (4)
												            </td>
												            <td style="width:90%;">
												                @if($v2->is_option4_image)
            														<div style="margin: -15px 0px 10px 25px;"><img src="{{ asset('') }}uploads/question/{{$v2->option1}}" style="width:100px;"></div>
            													@else
            														{!!$v2->option4!!}
            													@endif
												            </td>
												        </tr>
												    </table>
												</td>
											</tr>
										</table>
									</div>
								</div>
								@php $question_count = $question_count + 1; @endphp
							@endforeach
						</td>
					</tr>
				</table>
			
			</div>

			<footer style="height: 30px; border-top: 1px solid #ddd;">
    			<table  style="width: 100%;">
    				<tr style="font-size:14px;">
    					<td style="width:20%;" align="left">{{$page_count}} | <span style="color: #ccc;letter-spacing: 2px;">Page</span></td>
    					<td style="width:60%;" align="center">{{url('')}}</td>
    					<td style="width:20%;" align="right"></td>
    				</tr>
    			</table>
    		</footer>

			@if(count($question_list) != $page_count)
				<div class="page-break"></div>
			@endif
			
			@php $page_count = $page_count+1; @endphp
		@endforeach
	</body>
</html>