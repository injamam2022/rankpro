<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Source;
use App\Models\Subject;

class SourceController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Source::select(['sources.*','subjects.name as subject_name'])
                            ->leftJoin('subjects', 'sources.subject_id', '=', 'subjects.id')
                            ->get();
        return view('admin.source.list',$data);
    }


    public function add(){
        $data = [];
        $data['subject_list'] = Subject::where('status','1')->where('is_deleted','0')->get();
        return view('admin.source.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required',
                            'subject_id' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Source::where('name',$request->name)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['subject_id'] = $request->subject_id;
            $insertData['status'] = $request->status;

            Source::create($insertData);
        
            toastr()->success('Source added successfully.');
            return redirect()->route('admin.source');
        }else{
            toastr()->warning('Source already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Source::where('id',$request->id)->first();
        $data['subject_list'] = Subject::where('status','1')->where('is_deleted','0')->get();
        return view('admin.source.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required',
                            'subject_id' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Source::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['subject_id'] = $request->subject_id;
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Source updated successfully.');
            return redirect()->route('admin.source');
        }else{
            toastr()->warning('Source already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Source::where('id',$id)->delete();
        toastr()->success('Source deleted successfully.');
        return redirect()->route('admin.source');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Source::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Source status change successfully.');
        return redirect()->route('admin.source');
    }
    
}
