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
use App\Support\TaxonomyGuard;
use App\Support\AdminListQuery;

class ChapterController extends Controller
{
    public function list(Request $request){
        $data = [];
        $data['q'] = AdminListQuery::q($request);
        $data['per_page'] = AdminListQuery::perPage($request);
        $query = Chapter::select(['chapters.*','subjects.name as subject_name'])
                            ->leftJoin('subjects', 'chapters.subject_id', '=', 'subjects.id');
        $classFilter = $request->input('class_level');
        $hasClass = \Illuminate\Support\Facades\Schema::hasColumn('chapters', 'class_level');
        if ($hasClass && ($classFilter === '11' || $classFilter === '12' || $classFilter === '0')) {
            $query->where('chapters.class_level', (int) $classFilter);
        }
        AdminListQuery::applySearch($query, $data['q'], ['chapters.name', 'subjects.name']);
        $data['class_filter'] = $classFilter;
        if ($hasClass) {
            $query->orderBy('chapters.class_level');
        }
        $data['list'] = $query->orderBy('chapters.subject_id')->orderBy('chapters.name')->paginate($data['per_page'])->withQueryString();
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
                            'subject_id' => 'required',
                            'class_level' => 'required|in:0,11,12',
                        ]);

        $input = $request->all();

        $loginCheck = Chapter::where('name',$request->name)
            ->where('subject_id', $request->subject_id)
            ->where('class_level', $request->class_level)
            ->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['subject_id'] = $request->subject_id;
            $insertData['class_level'] = (int) $request->class_level;
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
                            'subject_id' => 'required',
                            'class_level' => 'required|in:0,11,12',
                        ]);

        $input = $request->all();
        $loginCheck = Chapter::where('id',$request->id)->first();

        if ($loginCheck){
            $dup = Chapter::where('name', $request->name)
                ->where('subject_id', $request->subject_id)
                ->where('class_level', $request->class_level)
                ->where('id', '!=', $loginCheck->id)
                ->first();
            if ($dup) {
                toastr()->warning('Chapter already exist for this subject and class.');
                return back()->withInput();
            }
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['subject_id'] = $request->subject_id;
            $insertData['class_level'] = (int) $request->class_level;
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Chapter updated successfully.');
            return redirect()->route('admin.chapter');
        }else{
            toastr()->error('Record not found');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = (int) $request->id;
        $used = TaxonomyGuard::chapterInUse($id);
        if ($used > 0) {
            toastr()->error('This chapter is still used by '.$used.' question(s) or topic(s). It cannot be deleted.');
            return redirect()->route('admin.chapter');
        }
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
