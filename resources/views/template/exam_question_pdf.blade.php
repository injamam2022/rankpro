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
            body {
                font-family: DejaVu Sans;
                font-size: 12px;
                color: #111;
                line-height: 1.35;
            }
            p {
                margin: 0;
                padding: 0;
            }
            table {
                border-collapse: collapse;
            }
            td {
                vertical-align: top;
            }
            .q-body img {
                max-width: 250px;
                height: auto;
                vertical-align: middle;
            }
		</style>
	</head>
	<body style="margin: 0px;width: 700px; margin: 0px auto;">
		@php
			if (!function_exists('exam_pdf_image_src')) {
				function exam_pdf_image_src($filename) {
					$filename = basename((string) $filename);
					$path = public_path('uploads/question/'.$filename);
					if ($filename !== '' && is_file($path)) {
						return str_replace('\\', '/', $path);
					}
					return asset('uploads/question/'.$filename);
				}
				function exam_pdf_image_style($filename, $maxW = 250, $maxH = 150) {
					$filename = basename((string) $filename);
					$fallback = 'max-width:'.$maxW.'px;height:auto;';
					$path = public_path('uploads/question/'.$filename);
					if ($filename === '' || !is_file($path)) {
						return $fallback;
					}
					$size = @getimagesize($path);
					if (!$size || empty($size[0]) || empty($size[1])) {
						return $fallback;
					}
					$scale = min($maxW / $size[0], $maxH / $size[1], 1);
					$w = max(1, (int) round($size[0] * $scale));
					$h = max(1, (int) round($size[1] * $scale));
					return 'width:'.$w.'px;height:'.$h.'px;';
				}
			}
		@endphp
		@php $question_count = 1; @endphp
		@php $page_count = 1; @endphp
		@php $question_char_count = 3; @endphp
		@foreach($subject_list as $k1=>$v1)
            @php $height = $k1; @endphp
            @php $page_side = 1; @endphp
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
						<td valign="top" style="border-right: 1px solid #000;width: 50%; padding: 8px 12px 0 12px; vertical-align: top;">
						@foreach($v1['question_list'] as $k2=>$v2)

							@php
							    $current_height = 30;
								if($v2->question_text){
									$current_height = $current_height + (strlen(strip_tags($v2->question_text))/$question_char_count);
								}

								if(!empty($v2->question_image) && $v2->question_image != NULL){
									$current_height = $current_height+120;
								}

								if($v2->is_option1_image){
									$current_height = $current_height+120;
								}else{
									$current_height = $current_height + 30 + (strlen(strip_tags($v2->option1))/$question_char_count);
								}

								if($v2->is_option2_image){
									$current_height = $current_height+120;
								}else{
									$current_height = $current_height + 30 + (strlen(strip_tags($v2->option2))/$question_char_count);
								}

								if($v2->is_option3_image){
									$current_height = $current_height+120;
								}else{
									$current_height = $current_height + 30 + (strlen(strip_tags($v2->option3))/$question_char_count);
								}

								if($v2->is_option4_image){
									$current_height = $current_height+120;
								}else{
									$current_height = $current_height + 30 + (strlen(strip_tags($v2->option4))/$question_char_count);
								}
								
								$height = $height + $current_height;

							@endphp

							@if($height >= 750)

								@if($page_side == 1)
									@php 
										$page_side = 2; 
										$height = $current_height; 
									@endphp
									</td>
									<td valign="top" style="width: 50%; padding: 8px 12px 0 12px; vertical-align: top;">
								@else
									@php 
										$page_side = 1; 
										$height = $current_height; 
									@endphp
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

						    		<div class="page-break"></div>

						    		@php $page_count = $page_count+1; @endphp

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
												<td valign="top" style="border-right: 1px solid #000;width: 50%; padding: 8px 12px 0 12px; vertical-align: top;">

								@endif

							@endif
							
								<table cellpadding="0" cellspacing="0" style="width:100%; margin:0 0 14px 0; border-collapse:collapse;">
									<tr>
										<td valign="top" style="width:24px; vertical-align:top; white-space:nowrap; padding:0 6px 0 0; font-size:12px; line-height:1.35;">{{$question_count}}.</td>
										<td valign="top" class="q-body" style="vertical-align:top; padding:0; font-size:12px; line-height:1.35;">
											<div>{!!$v2->question_text!!}</div>
											@if(!empty($v2->question_image) && $v2->question_image != NULL)
												<div style="text-align:center; margin:6px 0 2px 0;">
													<img src="{{ exam_pdf_image_src($v2->question_image) }}" style="{{ exam_pdf_image_style($v2->question_image, 250, 150) }}" alt="">
												</div>
											@endif
											<table cellpadding="0" cellspacing="0" style="width:100%; margin-top:4px; border-collapse:collapse;">
												<tr>
													<td valign="top" style="width:26px; vertical-align:top; white-space:nowrap; padding:3px 6px 3px 0; line-height:1.35;">(1)</td>
													<td valign="top" class="q-body" style="vertical-align:top; padding:3px 0; line-height:1.35;">
														@if($v2->is_option1_image)
															<img src="{{ exam_pdf_image_src($v2->option1) }}" style="{{ exam_pdf_image_style($v2->option1, 200, 80) }}" alt="">
														@else
															{!!$v2->option1!!}
														@endif
													</td>
												</tr>
												<tr>
													<td valign="top" style="width:26px; vertical-align:top; white-space:nowrap; padding:3px 6px 3px 0; line-height:1.35;">(2)</td>
													<td valign="top" class="q-body" style="vertical-align:top; padding:3px 0; line-height:1.35;">
														@if($v2->is_option2_image)
															<img src="{{ exam_pdf_image_src($v2->option2) }}" style="{{ exam_pdf_image_style($v2->option2, 200, 80) }}" alt="">
														@else
															{!!$v2->option2!!}
														@endif
													</td>
												</tr>
												<tr>
													<td valign="top" style="width:26px; vertical-align:top; white-space:nowrap; padding:3px 6px 3px 0; line-height:1.35;">(3)</td>
													<td valign="top" class="q-body" style="vertical-align:top; padding:3px 0; line-height:1.35;">
														@if($v2->is_option3_image)
															<img src="{{ exam_pdf_image_src($v2->option3) }}" style="{{ exam_pdf_image_style($v2->option3, 200, 80) }}" alt="">
														@else
															{!!$v2->option3!!}
														@endif
													</td>
												</tr>
												<tr>
													<td valign="top" style="width:26px; vertical-align:top; white-space:nowrap; padding:3px 6px 3px 0; line-height:1.35;">(4)</td>
													<td valign="top" class="q-body" style="vertical-align:top; padding:3px 0; line-height:1.35;">
														@if($v2->is_option4_image)
															<img src="{{ exam_pdf_image_src($v2->option4) }}" style="{{ exam_pdf_image_style($v2->option4, 200, 80) }}" alt="">
														@else
															{!!$v2->option4!!}
														@endif
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								@php $question_count = $question_count + 1; @endphp

								
						@endforeach
						
						@if($page_side == 1)
						    </td>
							<td valign="top" style="width: 50%; padding: 8px 12px 0 12px; vertical-align: top;">
							    &nbsp;
							    </td>
						@else
						    </td>
						@endif
						
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
    		
    		
	    		<!--<div class="page-break"></div>-->

	    		@php $page_count = $page_count+1; @endphp
			
			
		@endforeach
	</body>
</html>