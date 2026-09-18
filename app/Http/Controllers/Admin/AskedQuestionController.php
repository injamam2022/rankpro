<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\AskedQuestion;
use App\Models\Language;

class AskedQuestionController extends Controller
{
    public function list()
    {
        $data         = [];
        $data['list'] = AskedQuestion::all()->groupBy('uniq_id')->map(function ($group) {
                                        return $group->first();
                                    });
        return view('admin.cms.asked_question.list', $data);
    }

    public function add()
    {
        $data              = [];
        $data['languages'] = Language::where('status', 1)->get();
        return view('admin.cms.asked_question.add', $data);
    }

    public function save(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required',
        ]);

        $input = $request->all();
        // dd($input);
        $uniq_id = rand(10000, 99999);

        foreach ($request->text as $key => $value) {
            AskedQuestion::create([
                    'type'        => $request->type,
                    'status'      => $request->status,
                    'uniq_id'     => $uniq_id,
                    'text'        => $value,
                    'text2'       => isset($request->text2[$key])?$request->text2[$key]:'',
                    'language'    => $key,
                ]);
        }

        toastr()->success('AskedQuestion added successfully.');
        return redirect()->route('admin.asked_question');
    }

    public function edit(Request $request)
    {
        $asked_question = AskedQuestion::findOrFail($request->id);
        $asked_questions = AskedQuestion::select(['asked_questions.*','languages.name as language_name'])
                                    ->leftJoin('languages', 'asked_questions.language', '=', 'languages.id')
                                    ->where('uniq_id', $asked_question->uniq_id)->get();

        $questionsByLanguage = $asked_questions->keyBy('language');

        return view('admin.cms.asked_question.edit', compact('asked_question', 'asked_questions', 'questionsByLanguage'));
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $asked_question = AskedQuestion::findOrFail($request->id);

        $asked_questions = AskedQuestion::where('uniq_id',$asked_question->uniq_id)->get();
        

        foreach($asked_questions as $value){
            // dd($value);
            $insertData = [
                'type'        => $request->type,
                'status'      => $request->status,
                'text'        => isset($request->text[$value->language])?$request->text[$value->language]:'',
                'text2'       => isset($request->text2[$value->language])?$request->text2[$value->language]:''
            ];
            $value->update($insertData);
        }

        toastr()->success('AskedQuestion updated successfully.');
        return redirect()->route('admin.asked_question');
    }

    public function delete(Request $request)
    {
        $id         = $request->id;
        $loginChecks = AskedQuestion::where('uniq_id', $id)->get();

        if ($loginChecks->isEmpty()) {
            toastr()->error('AskedQuestion not found.');
        } else {
            foreach ($loginChecks as $loginCheck) {
                $loginCheck->delete();
            }
            toastr()->success('AskedQuestions deleted successfully.');
        }
        return redirect()->route('admin.asked_question');
    }

    public function change(Request $request)
    {
        $id = $request->id;

        $loginCheck = AskedQuestion::where('id', $request->id)->first();

        if ($loginCheck) {
            if ($loginCheck->status) {
                $loginCheck->update(["status" => 0]);
            } else {
                $loginCheck->update(["status" => 1]);
            }
        }

        toastr()->success('AskedQuestion status change successfully.');
        return redirect()->route('admin.asked_question');
    }
}
