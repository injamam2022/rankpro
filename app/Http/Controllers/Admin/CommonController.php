<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Chapter;
use App\Models\Source;
use App\Models\Exam;
use App\Models\Topic;
use App\Models\Sub_topic;

class CommonController extends Controller
{

    public function state(Request $request){
        $country_id = $request->country_id;
        $data = [];
        $data['result'] = State::where('country_id',$country_id)->where('status',1)->get();
        echo json_encode($data);
    }
    
    public function city(Request $request){
        $state_id = $request->state_id;
        $data = [];
        $data['result'] = City::where('state_id',$state_id)->where('status',1)->get();
        echo json_encode($data);
    }
    
    public function source(Request $request){
        $subject_id = $request->subject_id;
        $data = [];
        $data['result'] = Source::where('subject_id',$subject_id)->where('status',1)->get();
        echo json_encode($data);
    }
    
    public function chapter(Request $request){
        $subject_id = $request->subject_id;
        $data = [];
        $data['result'] = Chapter::where('subject_id',$subject_id)->where('status',1)->get();
        echo json_encode($data);
    }
    
    public function topic(Request $request){
        $subject_id = $request->subject_id;
        $chapter_id = $request->chapter_id;
        $data = [];
        $data['result'] = Topic::where('subject_id',$subject_id)->where('chapter_id',$chapter_id)->where('status',1)->get();
        echo json_encode($data);
    }
    
    public function sub_topic(Request $request){
        $subject_id = $request->subject_id;
        $chapter_id = $request->chapter_id;
        $topic_id = $request->topic_id;
        $data = [];
        $data['result'] = Sub_topic::where('subject_id',$subject_id)->where('chapter_id',$chapter_id)->where('topic_id',$topic_id)->where('status',1)->get();
        echo json_encode($data);
    }
    
    public function offline_exam(Request $request){
        $location_id = $request->location_id;
        $data = [];
        $data['result'] = Exam::where('location_id',$location_id)->where('type',2)->where('status',1)->where('is_deleted',0)->get();
        echo json_encode($data);
    }
    
    public function offline_exam_without_qus_paper(Request $request){
        $location_id = $request->location_id;
        $data = [];
        $data['result'] = Exam::where('location_id',$location_id)->where('type',2)->where('question_paper_id',NULL)->where('status',1)->where('is_deleted',0)->get();
        echo json_encode($data);
    }

    private function getLanguageId(){
        return 1;
    }
    
    
}
