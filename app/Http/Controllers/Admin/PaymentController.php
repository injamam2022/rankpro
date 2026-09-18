<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Payment;

class PaymentController extends Controller
{
    private function getLanguageId(){
        return 1;
    }
    public function test_series(){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['list'] = Payment::select(['payments.*','users.first_name','users.last_name','users.email_id','exams.name as exam_name'])
                        ->leftJoin('users', 'users.id', '=', 'payments.user_id')
                        ->leftJoin('exams', 'exams.id', '=', 'payments.test_series_id')
                        ->where('payments.type',2)
                        ->get();
        return view('admin.payment.test_series',$data);
    }

    public function test_series_detail(Request $request){
        $id = $request->id;
        $language_id = $this->getLanguageId();
        $data = [];
        $data['details'] = Payment::select(['payments.*','users.first_name','users.last_name','users.email_id','exams.name as exam_name','languages.name as language_name','locations.location_name','exam_dates.date_name'])
                        ->leftJoin('users', 'users.id', '=', 'payments.user_id')
                        ->leftJoin('exam_languages', 'exam_languages.id', '=', 'payments.language')
                        ->leftJoin('languages', 'exam_languages.id', '=', 'languages.id')
                        ->leftJoin('exam_locations', 'exam_locations.id', '=', 'payments.location')
                        ->leftJoin('locations', 'exam_locations.location_id', '=', 'locations.id')
                        ->leftJoin('exam_dates', 'exam_dates.id', '=', 'payments.starting_date')
                        ->leftJoin('exams', 'exams.id', '=', 'payments.test_series_id')
                        ->where('payments.type',2)
                        ->where('payments.id',$id)
                        ->first();
            // dd($data['details']);
        return view('admin.payment.test_series_detail',$data);
    }

    public function ranker(){
        $data = [];
        $data['list'] = Payment::select(['payments.*','users.first_name','users.last_name','users.email_id','rankers.name as ranker_name','ranker_prices.title'])
                        ->leftJoin('users', 'users.id', '=', 'payments.user_id')
                        ->leftJoin('ranker_prices', 'ranker_prices.id', '=', 'payments.ranker_id')
                        ->leftJoin('rankers', 'rankers.id', '=', 'ranker_prices.ranker_id')
                        ->where('payments.type',1)
                        ->get();
        return view('admin.payment.ranker',$data);
    }

    public function ranker_detail(Request $request){
        $id = $request->id;
        $language_id = $this->getLanguageId();
        $data = [];
        $data['details'] = Payment::select(['payments.*','users.first_name','users.last_name','users.email_id','rankers.name as ranker_name','ranker_prices.title'])
                        ->leftJoin('users', 'users.id', '=', 'payments.user_id')
                        ->leftJoin('ranker_prices', 'ranker_prices.id', '=', 'payments.ranker_id')
                        ->leftJoin('rankers', 'rankers.id', '=', 'ranker_prices.ranker_id')
                        ->where('payments.type',1)
                        ->first();
            // dd($data['details']);
        return view('admin.payment.ranker_detail',$data);
    }

    public function new_light(){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['list'] = Payment::select(['payments.*','users.first_name','users.last_name','users.email_id','test_series_headings.heading'])
                        ->leftJoin('users', 'users.id', '=', 'payments.user_id')
                        ->leftJoin('test_series_headings', 'test_series_headings.test_series_id', '=', 'payments.test_series_id')
                        ->where('payments.type',3)
                        ->where('test_series_headings.language',$language_id)
                        ->get();
        return view('admin.payment.new_light',$data);
    }

    public function new_light_detail(Request $request){

        $id = $request->id;
        $language_id = $this->getLanguageId();
        $data = [];
        $data['details'] = Payment::select(['payments.*','users.first_name','users.last_name','users.email_id','test_series_headings.heading','test_series_languages.language_name','test_series_locations.location_name','test_series_dates.date_name'])
                        ->leftJoin('users', 'users.id', '=', 'payments.user_id')
                        ->leftJoin('test_series_languages', 'test_series_languages.id', '=', 'payments.language')
                        ->leftJoin('test_series_locations', 'test_series_locations.id', '=', 'payments.location')
                        ->leftJoin('test_series_dates', 'test_series_dates.id', '=', 'payments.starting_date')
                        ->leftJoin('test_series_headings', 'test_series_headings.test_series_id', '=', 'payments.test_series_id')
                        ->where('payments.type',3)
                        ->where('payments.id',$id)
                        ->first();
            // dd($data['details']);
        return view('admin.payment.new_light_detail',$data);
    }
    
}
