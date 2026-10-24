<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Question;
use App\Models\Question_detail;
use App\Models\Question_source;
use App\Support\AdminListQuery;

class Question_sourceController extends Controller
{
    public function list(Request $request){
        $data = [];
        $data['q'] = AdminListQuery::q($request);
        $data['per_page'] = AdminListQuery::perPage($request);
        $query = Question_source::query();
        AdminListQuery::applySearch($query, $data['q'], ['name']);
        $data['list'] = $query->orderByDesc('id')->paginate($data['per_page'])->withQueryString();
        return view('admin.question_source.list',$data);
    }


    public function add(){
        $data = [];
        return view('admin.question_source.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Question_source::where('name',$request->name)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['status'] = $request->status;

            Question_source::create($insertData);
        
            toastr()->success('Question source added successfully.');
            return redirect()->route('admin.question_source');
        }else{
            toastr()->warning('Question source already exist');
            return back()->withInput();
        }
    }

    public function view(Request $request){
        $data = [];
        $data['details'] = Question_source::where('id', $request->id)->first();
        if (!$data['details']) {
            toastr()->error('Question source not found.');
            return redirect()->route('admin.question_source');
        }

        $data['q'] = AdminListQuery::q($request);
        $data['per_page'] = AdminListQuery::perPage($request);
        $languageId = getDefaultLanguage();

        $query = Question_detail::select([
                'questions.id',
                'questions.status',
                'questions.created_at',
                'questions.updated_at',
                'question_details.question_text',
                'question_details.question_image',
                'subjects.name as subject_name',
                'chapters.name as chapter_name',
            ])
            ->join('questions', 'questions.id', '=', 'question_details.question_id')
            ->leftJoin('subjects', 'questions.subject_id', '=', 'subjects.id')
            ->leftJoin('chapters', 'questions.chapter_id', '=', 'chapters.id')
            ->where('questions.is_deleted', 0)
            ->where('question_details.language_id', $languageId);

        Question::constrainBySource($query, (int) $data['details']->id);

        if (session()->get('admmin_is_super') == 'T') {
            $query->where('questions.subject_id', session()->get('admmin_subject_id'));
        }

        if ($data['q'] !== '') {
            $like = AdminListQuery::like($data['q']);
            $term = $data['q'];
            $query->where(function ($w) use ($like, $term) {
                $w->where('question_details.question_text', 'like', $like)
                    ->orWhere('subjects.name', 'like', $like)
                    ->orWhere('chapters.name', 'like', $like);
                if (ctype_digit($term)) {
                    $w->orWhere('questions.id', (int) $term);
                }
            });
        }

        $data['list'] = $query->orderByDesc('questions.id')->paginate($data['per_page'])->withQueryString();

        return view('admin.question_source.view', $data);
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Question_source::where('id',$request->id)->first();
        return view('admin.question_source.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Question_source::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Question source updated successfully.');
            return redirect()->route('admin.question_source');
        }else{
            toastr()->warning('Question source already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Question_source::where('id',$id)->delete();
        toastr()->success('Question source deleted successfully.');
        return redirect()->route('admin.question_source');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Question_source::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Question source status change successfully.');
        return redirect()->route('admin.question_source');
    }
    
}
