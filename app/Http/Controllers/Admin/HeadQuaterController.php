<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\HeadQuater;
use App\Models\HeadQuaterDescription;
use App\Models\Language;

class HeadQuaterController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = HeadQuater::with(['descriptions' => function ($query) {
                                        $query->select('head_quater_id', 'language', 'text')
                                            ->orderBy('id', 'asc');
                                    }])
                                    ->get();
        return view('admin.cms.head_quater.list',$data);
    }

    public function edit(Request $request)
    {
        $head_quater = HeadQuater::with('descriptions')->findOrFail($request->id);
        $languages = Language::all();

        return view('admin.cms.head_quater.edit', compact('head_quater', 'languages'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'video' => 'required',
        ]);
        $head_quater = HeadQuater::findOrFail($request->id);
        $updateData = [];
        $updateData['status'] = $request->status;
        $updateData['video'] = $request->video;
        $head_quater->update($updateData);

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'text_') === 0) {
                $languageId = substr($key, 5);

                $language = Language::find($languageId);

                if ($language) {
                    $description = $head_quater->descriptions->firstWhere('language', $languageId);
                    if ($description) {
                        $description->update([
                            'text' => $value,
                        ]);
                    } else {
                        BannerDescription::create([
                            'head_quater_id' => $head_quater->id,
                            'language'       => $language->id,
                            'text'           => $value,
                        ]);
                    }
                }
            }
        }
        toastr()->success('HeadQuater updated successfully.');
        return redirect()->route('admin.head_quater');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = HeadQuater::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('HeadQuater status change successfully.');
        return redirect()->route('admin.head_quater');
    }
}
