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

class ChapterController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Chapter::select(['chapters.*','subjects.name as subject_name'])
                            ->leftJoin('subjects', 'chapters.subject_id', '=', 'subjects.id')
                            ->get();
        return view('admin.chapter.list',$data);
    }


    public function add(){
        $data = [];
        $data['subject_list'] = Subject::where('status','1')->where('is_deleted','0')->get();
        return view('admin.chapter.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required',
                            'subject_id' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Chapter::where('name',$request->name)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['subject_id'] = $request->subject_id;
            $insertData['status'] = $request->status;

            Chapter::create($insertData);
        
            toastr()->success('Chapter added successfully.');
            return redirect()->route('admin.chapter');
        }else{
            toastr()->warning('Chapter already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Chapter::where('id',$request->id)->first();
        $data['subject_list'] = Subject::where('status','1')->where('is_deleted','0')->get();
        return view('admin.chapter.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required',
                            'subject_id' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Chapter::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['subject_id'] = $request->subject_id;
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Chapter updated successfully.');
            return redirect()->route('admin.chapter');
        }else{
            toastr()->warning('Chapter already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Chapter::where('id',$id)->delete();
        toastr()->success('Chapter deleted successfully.');
        return redirect()->route('admin.chapter');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Chapter::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Chapter status change successfully.');
        return redirect()->route('admin.chapter');
    }
    
}
