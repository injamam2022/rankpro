<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\RealStory;
use App\Models\Language;

class RealStoryController extends Controller
{
    public function list()
    {
        $data         = [];
        $data['list'] = RealStory::all()->groupBy('uniq_id')->map(function ($group) {
                                        return $group->first();
                                    });
        return view('admin.cms.real_story.list', $data);
    }

    public function edit(Request $request)
    {
        $real_story = RealStory::findOrFail($request->id);
        $asked_questions = RealStory::where('uniq_id', $real_story->uniq_id)->get();

        $questionsByLanguage = $asked_questions->keyBy('language');

        $languages = Language::all();
        return view('admin.cms.real_story.edit', compact('real_story', 'languages', 'questionsByLanguage'));
    }

    public function update(Request $request)
    {
        $real_story = RealStory::findOrFail($request->id);

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'text_') === 0) {
                $languageId = substr($key, 5);

                $language = Language::find($languageId);
                if ($language) {
                    $questionData = RealStory::where('uniq_id', $real_story->uniq_id)
                                                ->where('language', $languageId)
                                                ->first();

                    if ($questionData) {
                        $questionData->status = $request->status;
                        $questionData->text   = $value;
                        $questionData->save();
                    } else {
                        RealStory::create([
                            'uniq_id' => $real_story->uniq_id,
                            'language' => $languageId,
                            'status' => $real_story->status,
                            'text' => $value,
                        ]);
                    }
                }
            }
        }
        $real_story->save();

        toastr()->success('RealStory updated successfully.');
        return redirect()->route('admin.real_story');
    }

    public function change(Request $request)
    {
        $id = $request->id;

        $loginCheck = RealStory::where('id', $request->id)->first();

        if ($loginCheck) {
            if ($loginCheck->status) {
                $loginCheck->update(["status" => 0]);
            } else {
                $loginCheck->update(["status" => 1]);
            }
        }

        toastr()->success('RealStory status change successfully.');
        return redirect()->route('admin.real_story');
    }
}
