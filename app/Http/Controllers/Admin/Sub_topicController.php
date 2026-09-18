<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Chapter;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Sub_topic;

class Sub_topicController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Sub_topic::select(['sub_topics.*','chapters.name as chapter_name','subjects.name as subject_name','topics.name as topic_name'])
                            ->leftJoin('topics', 'sub_topics.topic_id', '=', 'topics.id')
                            ->leftJoin('chapters', 'sub_topics.chapter_id', '=', 'chapters.id')
                            ->leftJoin('subjects', 'sub_topics.subject_id', '=', 'subjects.id')
                            ->get();
        return view('admin.sub_topic.list',$data);
    }


    public function add(){
        $data = [];
        $data['subject_list'] = Subject::where('status','1')->where('is_deleted','0')->get();
        return view('admin.sub_topic.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required',
                            'subject_id' => 'required'
                        ]);

        $input = $request->all();

        // $loginCheck = Sub_topic::where('name',$request->name)->first();

        if (1){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['subject_id'] = $request->subject_id;
            $insertData['chapter_id'] = $request->chapter_id;
            $insertData['topic_id'] = $request->topic_id;
            $insertData['status'] = $request->status;

            Sub_topic::create($insertData);
        
            toastr()->success('Sub Topic added successfully.');
            return redirect()->route('admin.sub_topic');
        }else{
            toastr()->warning('Sub Topic already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Sub_topic::where('id',$request->id)->first();
        $data['subject_list'] = Subject::where('status','1')->where('is_deleted','0')->get();
        $data['chapter_list'] = Chapter::where('status','1')->where('subject_id',$data['details']->subject_id)->get();
        $data['topic_list'] = Topic::where('status','1')->where('chapter_id',$data['details']->chapter_id)->get();
        return view('admin.sub_topic.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required',
                            'subject_id' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Sub_topic::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['subject_id'] = $request->subject_id;
            $insertData['chapter_id'] = $request->chapter_id;
            $insertData['topic_id'] = $request->topic_id;
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Sub Topic updated successfully.');
            return redirect()->route('admin.sub_topic');
        }else{
            toastr()->warning('Sub Topic already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Sub_topic::where('id',$id)->delete();
        toastr()->success('Sub Topic deleted successfully.');
        return redirect()->route('admin.sub_topic');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Sub_topic::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Sub Topic status change successfully.');
        return redirect()->route('admin.sub_topic');
    }
    
}
