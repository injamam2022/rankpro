<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Models\BannerDescription;
use App\Models\User;
use App\Models\Ranker;
use App\Models\HeadQuater;
use App\Models\RealStory;
use App\Models\SuccessStory;
use App\Models\AskedQuestion;
use App\Models\Gallery;
use App\Models\AsMention;
use App\Models\TestSeries;
use App\Models\RankerRules;
use App\Models\RankerFeedbacks;
use App\Models\Ranker_price;
use App\Models\Coupon;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Location;
use App\Models\Exam;
use App\Models\Language;
use App\Models\Subscription;
use App\Models\ExamLanguage;
use App\Models\ExamLocation;
use App\Models\ExamDate;
use App\Models\Footer;
use App\Models\Contact;
use App\Models\Cms_text;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    private function getLanguageId()
    {
        return 1;
    }
    public function index(){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['banner_list'] = BannerDescription::select(['banner_descriptions.text','banners.id','banners.image','banners.created_at'])
                                ->leftJoin('banners', 'banner_descriptions.banner_id', '=', 'banners.id')
                                ->where('banners.status',1)->where('banner_descriptions.language',$language_id)->get();

        $data['head_quaters'] = HeadQuater::with(['descriptions' => function ($query) use ($language_id) {
                                    $query->where('language', $language_id);
                                }])->where('status', 1)->get();

        $data['real_story'] = RealStory::where('status', 1)
                    ->where('language', $language_id)
                    ->first();

        $data['success_story'] = SuccessStory::with(['descriptions' => function ($query) use ($language_id) {
                                    $query->where('language', $language_id);
                                }])->where('status', 1)->get();

        $data['asked_questien'] = AskedQuestion::where('language', $language_id)
                        ->where('status', 1)
                        ->get();

        $data['upcoming_test'] = Exam::select(["exams.*","locations.location_name"])
                                ->leftJoin('locations', 'locations.id', '=', 'exams.location_id')
                                ->where('exams.status', 1)->where('exams.is_deleted', 0)->where('exams.exam_date','>',date('Y-m-d'))->where('exams.type', 2)->get();
        // dd($data['upcoming_test']);
        $data['gallery'] = Gallery::where('status', 1)->get();

        $data['as_mention'] = AsMention::where('status', 1)->get();
        $data['location'] = Location::select(["locations.*","countries.name as country_name","states.name as state_name","cities.name as city_name"])
                ->leftJoin('states', 'states.id', '=', 'locations.state_id')
                ->leftJoin('countries', 'locations.country_id', '=', 'countries.id')
                ->leftJoin('cities', 'locations.city_id', '=', 'cities.id')
                ->where('locations.status', 1)->get();

        $data['test_series'] = TestSeries::with(['descriptions' => function ($query) use ($language_id) {
                                    $query->where('language', $language_id);
                                }])->where('status', 1)->get();

        $data['ranker_list'] = Ranker::where('status',1)->get();
        // dd($data);
        return view('site.home',$data);
    }

    public function save_subscription(Request $request){

        $subscription = Subscription::where('email',$request->email)->first();
        if(!$subscription){

            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['email'] = $request->email;

            if(Auth::check()){
                $insertData['user_id'] = Auth::user()->id;
            }

            Subscription::create($insertData);
        }

        return redirect()->route('index')->with('success', 'Subscription added successfully!');
    }

    public function testseries(){
        $data = [];
        $data['exam'] = Exam::select(["exams.*","locations.location_name"])
                                ->leftJoin('locations', 'locations.id', '=', 'exams.location_id')
                                ->where('exams.status', 1)->where('exams.is_deleted', 0)->where('exams.type', 2)->get();

        $data['cms_text'] = Cms_text::where('page_key','testseries')->first();
        return view('site.testseries',$data);
    }

    public function testSeriesDetails($encrypted_id)
    {
        try {
            $id = decrypt($encrypted_id);
        } catch (\Exception $e) {
            abort(404, 'Invalid ID');
        }

        $language_id = $this->getLanguageId();

        $test = Exam::select(["exams.*","locations.location_name","question_papers.no_of_question","question_papers.marks_per_question","question_papers.totals_marks_for_exam","question_papers.time_per_question","question_papers.total_time_for_exam"])
                                ->leftJoin('locations', 'locations.id', '=', 'exams.location_id')
                                ->leftJoin('question_papers', 'question_papers.id', '=', 'exams.question_paper_id')
                                ->where('exams.status', 1)->where('exams.type', 2)
                                ->where('exams.id', $id)->first();

        $exam_language_list = ExamLanguage::select(["languages.name as language_name","exam_languages.exam_id","exam_languages.id"])
                                    ->leftJoin('languages', 'languages.id', '=', 'exam_languages.language_id')
                                    ->where('exam_languages.exam_id',$id)
                                    ->get();
        $exam_location_list = ExamLocation::select(["locations.location_name","exam_locations.exam_id","exam_locations.id"])
                                    ->leftJoin('locations', 'locations.id', '=', 'exam_locations.location_id')
                                    ->where('exam_locations.exam_id',$id)
                                    ->get();
        $exam_date_list = ExamDate::where('exam_id',$id)->get();

        return view('site.testseries-details', compact('test','exam_language_list','exam_location_list','exam_date_list'));
    }

    public function testSeriesCheckout(Request $request){
        $test_id = $request->test_id;
        $language = $request->language;
        $location = $request->location;
        $startingDate = $request->startingDate;
        $language_id = $this->getLanguageId();

        $test = Exam::select(["exams.*","locations.location_name"])
                                ->leftJoin('locations', 'locations.id', '=', 'exams.location_id')
                                ->where('exams.status', 1)->where('exams.type', 2)
                                ->where('exams.id', $test_id)->first();

        $data = [];
        $data['test_id'] = $test_id;
        $data['language'] = $language;
        $data['location'] = $location;
        $data['startingDate'] = $startingDate;
        return view('site.testseries_checkout',compact('test','data'));
    }

    public function testSeriesSaveCheckout(Request $request){
        $insertData = [];
        $insertData["user_id"] = Auth::user()->id;
        $insertData["amount"] = $request->final_price;
        $insertData["coupon_discount"] = $request->coupon_discount;
        $insertData["coupon_code"] = $request->coupon_code;
        $insertData["discount"] = $request->total_discount;
        $insertData["language"] = $request->language;
        $insertData["location"] = $request->location;
        $insertData["starting_date"] = $request->startingDate;
        $insertData["test_series_id"] = $request->test_id;
        $insertData["transaction_id"] = $request->razorpay_payment_id;
        $insertData["order_id"] = $request->razorpay_order_id;
        // $insertData["razorpay_signature"] = $request->razorpay_signature;
        $insertData["payment_status"] = "SUCCESS";
        $insertData["type"] = 2;


        Payment::create($insertData);

        return response()->json([
            'success' => 1,
            'message' => 'Coupon applied successfully.'
        ], 200);
    }

    public function new_light(){
        $user = Auth::user();
        $language_id = $this->getLanguageId();
        $testSeries = TestSeries::where('status', 1)
            ->with([
                'descriptions' => fn($q) => $q->where('language', $language_id),
                'headings'     => fn($q) => $q->where('language', $language_id),
                'abouts'       => fn($q) => $q->where('language', $language_id),
                'overviews'    => fn($q) => $q->where('language', $language_id),
                'examdescs'    => fn($q) => $q->where('language', $language_id),
                'markschemes'  => fn($q) => $q->where('language', $language_id),
            ])
            ->get();
        $cms_text = Cms_text::where('page_key','new-light')->first();
        return view('site.new_light', ['test_series' => $testSeries,'cms_text' => $cms_text]);
    }

    public function new_light_detail($encrypted_id)
    {
        try {
            $id = decrypt($encrypted_id);
        } catch (\Exception $e) {
            abort(404, 'Invalid ID');
        }

        $language_id = $this->getLanguageId();

        $test = TestSeries::with([
            'descriptions' => fn($q) => $q->where('language', $language_id),
            'headings'     => fn($q) => $q->where('language', $language_id),
            'abouts'       => fn($q) => $q->where('language', $language_id),
            'overviews'    => fn($q) => $q->where('language', $language_id),
            'examdescs'    => fn($q) => $q->where('language', $language_id),
            'markschemes'  => fn($q) => $q->where('language', $language_id),
            'language',
            'location',
            'date'
        ])->findOrFail($id);

        $subjectIds = array_filter(explode(',', $test->subjects));
        $subjects = Subject::whereIn('id', $subjectIds)->get();

        return view('site.new_light_detail', compact('test', 'subjects'));
    }

    public function new_lightCheckout(Request $request){
        

        $insertData = [];
        
        if (Auth::check()){
            $insertData["user_id"] = Auth::user()->id;
        }
        
        $insertData['type'] = 3;
        $insertData['test_series_id'] = $request->test_id;
        $insertData['language'] = $request->language;
        $insertData['location'] = $request->location;
        $insertData['starting_date'] = $request->startingDate;
        $insertData['name'] = $request->name;
        $insertData['email'] = $request->email;
        $insertData['mobile_number'] = $request->mobile_number;
        // dd($insertData);
        Payment::create($insertData);
        return redirect()->route('new_light')->with('success', 'Request has been submitted successfully.');
    }


    public function checkCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        $code = $request->code;
        $today = now();

        $coupon = Coupon::where('code', $code)
                        ->where('status', 1)
                        ->first();

        if ($coupon) {
            if ($coupon->start_date && $today->lt($coupon->start_date)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Coupon is not active yet.'
                ], 201);
            }

            if ($coupon->end_date && $today->gt($coupon->end_date)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Coupon has expired.'
                ], 201);
            }
            return response()->json([
                'valid' => true,
                'discount' => $coupon->value ?? 0,
                'type' => $coupon->type ?? 0,
                'message' => 'Coupon applied successfully.'
            ], 200);
        } else {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or inactive coupon code.'
            ], 202);
        }
    }

    public function ranker(){
        $data = [];
        $data['ranker_list'] = Ranker::where('status',1)->get();
        $data['language_list'] = Language::where('status',1)->get();
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['course_list'] = Course::where('status',1)->get();
        $data['ranker_banner_list'] = Ranker::where('status',1)->where('is_in_listing',1)->get();
        return view('site.ranker',$data);
    }

    public function ranker_detail($encrypted_id){
        try {
            $id = decrypt($encrypted_id);
        } catch (\Exception $e) {
            abort(404, 'Invalid ID');
        }

        $language_id = $this->getLanguageId();


        $data = [];
        $rules = RankerRules::where('status', 1)
                            ->where('language', $language_id)
                            ->get();
        $ranker = Ranker::with([
                                'meetings'
                            ])->findOrFail($id);

        $feedbacks = RankerFeedbacks::select(['ranker_feedbacks.*','users.first_name','users.last_name','rankers.name as ranker_name'])
                ->leftJoin('rankers', 'ranker_feedbacks.ranker_id', '=', 'rankers.id')
                ->leftJoin('users', 'ranker_feedbacks.user_id', '=', 'users.id')
                ->where('ranker_feedbacks.status',1)->where('ranker_feedbacks.ranker_id',$id)->get();
                
        // dd($feedbacks);
        return view('site.ranker_detail', [
                        'ranker' => $ranker,
                        'rules'  => $rules,
                        'feedbacks' => $feedbacks,
                        'meetings' => $ranker->meetings
                    ]);
    }

    public function rankerCheckout($encrypted_id){
        try {
            $id = decrypt($encrypted_id);
        } catch (\Exception $e) {
            abort(404, 'Invalid ID');
        }

        $language_id = $this->getLanguageId();


        $ranker_meeting = Ranker_price::where('id',$id)->first();
        $ranker = Ranker::where('id',$ranker_meeting->ranker_id)->first();
        // dd($ranker);
        return view('site.ranker_checkout', [
                        'ranker' => $ranker,
                        'ranker_meeting'  => $ranker_meeting
                    ]);
    }

    public function rankerSaveCheckout(Request $request){
        $insertData = [];
        $insertData["user_id"] = Auth::user()->id;
        $insertData["amount"] = $request->final_price;
        $insertData["coupon_discount"] = $request->coupon_discount;
        $insertData["coupon_code"] = $request->coupon_code;
        $insertData["discount"] = $request->total_discount;
        $insertData["language"] = $request->language;
        $insertData["location"] = $request->location;
        $insertData["starting_date"] = $request->startingDate;
        $insertData["ranker_id"] = $request->test_id;
        $insertData["transaction_id"] = $request->razorpay_payment_id;
        $insertData["order_id"] = $request->razorpay_order_id;
        // $insertData["razorpay_signature"] = $request->razorpay_signature;
        $insertData["payment_status"] = "SUCCESS";
        $insertData["type"] = 1;


        Payment::create($insertData);

        return response()->json([
            'success' => 1,
            'message' => 'Coupon applied successfully.'
        ], 200);
    }

    public function contact(Request $request){
        $footerSlides = Footer::where('status',1)->get();
        $data = [];
        $data['id'] = $request->id;
        $data['type'] = $request->type;

        $data['footer_detail'] = [];

        foreach($footerSlides as $value){
            $data['footer_detail'][$value->key_name] = $value->value;
        }
        return view('site.contact', $data);
    }

    public function save_contact(Request $request){
        $insertData = [];
        // $insertData["user_id"] = Auth::user()->id;
        $insertData['type'] = 3;
        $insertData['location_id'] = $request->id;
        $insertData['page_type'] = $request->type;
        $insertData['message'] = $request->message;
        $insertData['name'] = $request->name;
        $insertData['email'] = $request->email;
        $insertData['mobile_number'] = $request->mobile_number;
        // dd($insertData);
        Contact::create($insertData);
        return redirect()->route('contact')->with('success', 'Thanks for contact us');
    }

}
