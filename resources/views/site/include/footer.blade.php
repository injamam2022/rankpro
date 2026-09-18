@php
    $footerSlides = \App\Models\Footer::where('status',1)->get();
    $exam = \App\Models\Exam::where('status',1)->where('type',2)->where('is_in_footer',1)->get();
    $testSeries = \App\Models\TestSeriesHeading::select(["test_series_headings.heading","test_series.id"])
                        ->leftJoin('test_series', 'test_series.id', '=', 'test_series_headings.test_series_id')
                        ->where('test_series.status', 1)->where('test_series_headings.language', 1)->get();

    $footer_detail = [];

    foreach($footerSlides as $value){
        $footer_detail[$value->key_name] = $value->value;
    }
@endphp

<footer>
    <div class="cusContainer" style="max-width:1420px;">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 ffirstblk">
                <dl>
                    <dt>Menu</dt>
                    <dd><a href="{{ route('ranker') }}">Ranker</a></dd>
                    <!-- <dd><a href="#">Free Customise Test</a></dd>
                    <dd><a href="#">Advisory committee</a></dd>
                    <dd><a href="#">Blogs</a></dd>
                    <dd><a href="#"></a>New light</a></dd> -->
                </dl>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 fsecblk">
                <dl>
                    <dt>RankPro Test Series</dt>
                    @foreach($exam as $value)
                        <dd><a href="{{ route('testseries.details', encrypt($value->id)) }}">{{$value->name}}</a></dd>
                    @endforeach
                </dl>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 fthrdblk">
                <dl>
                    <dt>New Light Test Series</dt>
                    @foreach($testSeries as $value)
                        <dd><a href="{{ route('new_light.details', encrypt($value->id)) }}">{!!$value->heading!!}</a></dd>
                    @endforeach
                </dl>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 ffourblk">
                <div class="footercont">
                    @if(isset($footer_detail['contact_us']))
                        <h6>Contact Us</h6>
                        <span>{{$footer_detail['contact_us']}}</span>
                    @endif
                    @if(isset($footer_detail['toll_free']))
                        <h6>Toll Free</h6>
                        <span>{{$footer_detail['toll_free']}}</span>
                        <img src="{{ asset('') }}web/images/icons/footer_contact.png" alt="">
                    @endif
                </div>
                
                @if(isset($footer_detail['email']))
                    <div class="footeremail">
                        <h6>Email</h6>
                        <a href="mailto:{{$footer_detail['email']}}">{{$footer_detail['email']}}</a>
                        <img src="{{ asset('') }}web/images/icons/footer_email.png" alt="">
                    </div>
                @endif
                @if(isset($footer_detail['address']))
                    <div class="footeradd">
                        <h6>Address</h6>
                        <span>{{$footer_detail['address']}}</span>
                        <img src="{{ asset('') }}web/images/icons/footer_address.png" alt="">
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="footerlinediv">
        <div class="cusContainer" style="max-width:1420px;">
            <div class="copyfooter">
                @if(isset($footer_detail['copyright']))
                    <p>&copy;Copyright {{date('Y')}} {{$footer_detail['copyright']}}</p>
                @endif
                <div class="footersocial">
                    @if(isset($footer_detail['facebook']))
                        <a href="{{$footer_detail['facebook']}}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if(isset($footer_detail['youtube']))
                        <a href="{{$footer_detail['youtube']}}" target="_blank"><i class="fab fa-youtube"></i></a>
                    @endif
                    @if(isset($footer_detail['instagram']))
                        <a href="{{$footer_detail['instagram']}}" target="_blank"><i class="fab fa-instagram"></i></a>
                    @endif
                </div>
                <p>Developed By <a href="https://zabingo.com/">zabingo.com</a></p>
            </div>
        </div>
    </div>
</footer>
