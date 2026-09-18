<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\Scholarship;
use App\Models\ScholarshipDescription;
use App\Models\Language;

class ScholarshipController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Scholarship::with(['descriptions' => function ($query) {
                                        $query->select('scholarship_id', 'language', 'text')
                                            ->orderBy('id', 'asc');
                                    }])
                                    ->get();
        return view('admin.cms.scholarship.list',$data);
    }

    public function edit(Request $request)
    {
        $scholarship = Scholarship::with('descriptions')->findOrFail($request->id);
        $languages = Language::all();

        return view('admin.cms.scholarship.edit', compact('scholarship', 'languages'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'video' => 'required',
        ]);
        $scholarship = Scholarship::findOrFail($request->id);
        $updateData = [];
        $updateData['status'] = $request->status;
        $updateData['video'] = $request->video;
        $scholarship->update($updateData);

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'text_') === 0) {
                $languageId = substr($key, 5);

                $language = Language::find($languageId);

                if ($language) {
                    $description = $scholarship->descriptions->firstWhere('language', $languageId);
                    if ($description) {
                        $description->update([
                            'text' => $value,
                        ]);
                    } else {
                        BannerDescription::create([
                            'scholarship_id' => $scholarship->id,
                            'language'       => $language->id,
                            'text'           => $value,
                        ]);
                    }
                }
            }
        }
        toastr()->success('Scholarship updated successfully.');
        return redirect()->route('admin.scholarship');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Scholarship::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Scholarship status change successfully.');
        return redirect()->route('admin.scholarship');
    }
}
