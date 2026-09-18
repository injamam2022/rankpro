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

class TopicController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Topic::select(['topics.*','chapters.name as chapter_name','subjects.name as subject_name'])
                            ->leftJoin('chapters', 'topics.chapter_id', '=', 'chapters.id')
                            ->leftJoin('subjects', 'topics.subject_id', '=', 'subjects.id')
                            ->get();
        return view('admin.topic.list',$data);
    }


    public function add(){
        $data = [];
        $data['subject_list'] = Subject::where('status','1')->where('is_deleted','0')->get();
        return view('admin.topic.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required',
                            'subject_id' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Topic::where('name',$request->name)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['subject_id'] = $request->subject_id;
            $insertData['chapter_id'] = $request->chapter_id;
            $insertData['status'] = $request->status;

            Topic::create($insertData);
        
            toastr()->success('Topic added successfully.');
            return redirect()->route('admin.topic');
        }else{
            toastr()->warning('Topic already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Topic::where('id',$request->id)->first();
        $data['subject_list'] = Subject::where('status','1')->where('is_deleted','0')->get();
        $data['chapter_list'] = Chapter::where('status','1')->where('subject_id',$data['details']->subject_id)->get();
        return view('admin.topic.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required',
                            'subject_id' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Topic::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['subject_id'] = $request->subject_id;
            $insertData['chapter_id'] = $request->chapter_id;
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Topic updated successfully.');
            return redirect()->route('admin.topic');
        }else{
            toastr()->warning('Topic already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Topic::where('id',$id)->delete();
        toastr()->success('Topic deleted successfully.');
        return redirect()->route('admin.topic');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Topic::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Topic status change successfully.');
        return redirect()->route('admin.topic');
    }
    
}
