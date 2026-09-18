<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\HurryNow;
use App\Models\Language;

class HurryNowController extends Controller
{
    public function list()
    {
        $data         = [];
        $data['list'] = HurryNow::all()->groupBy('uniq_id')->map(function ($group) {
                                        return $group->first();
                                    });
        return view('admin.cms.hurry_now.list', $data);
    }

    public function edit(Request $request)
    {
        $hurry_now = HurryNow::findOrFail($request->id);
        $asked_questions = HurryNow::where('uniq_id', $hurry_now->uniq_id)->get();

        $questionsByLanguage = $asked_questions->keyBy('language');

        $languages = Language::all();
        return view('admin.cms.hurry_now.edit', compact('hurry_now', 'languages', 'questionsByLanguage'));
    }

    public function update(Request $request)
    {
        $hurry_now = HurryNow::findOrFail($request->id);

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'text_') === 0) {
                $languageId = substr($key, 5);

                $language = Language::find($languageId);
                if ($language) {
                    $questionData = HurryNow::where('uniq_id', $hurry_now->uniq_id)
                                                ->where('language', $languageId)
                                                ->first();

                    if ($questionData) {
                        $questionData->status = $request->status;
                        $questionData->text   = $value;
                        $questionData->save();
                    } else {
                        HurryNow::create([
                            'uniq_id' => $hurry_now->uniq_id,
                            'language' => $languageId,
                            'status' => $hurry_now->status,
                            'text' => $value,
                        ]);
                    }
                }
            }
        }
        $hurry_now->save();

        toastr()->success('HurryNow updated successfully.');
        return redirect()->route('admin.hurry_now');
    }

    public function change(Request $request)
    {
        $id = $request->id;

        $loginCheck = HurryNow::where('id', $request->id)->first();

        if ($loginCheck) {
            if ($loginCheck->status) {
                $loginCheck->update(["status" => 0]);
            } else {
                $loginCheck->update(["status" => 1]);
            }
        }

        toastr()->success('HurryNow status change successfully.');
        return redirect()->route('admin.hurry_now');
    }
}
