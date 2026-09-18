<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\Learning;
use App\Models\LearningDescription;
use App\Models\Language;

class LearningController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Learning::with(['descriptions' => function ($query) {
                            $query->select('learning_id', 'language', 'text')
                                ->orderBy('id', 'asc');
                        }])
                        ->get();
        return view('admin.cms.learning.list',$data);
    }

    public function edit(Request $request)
    {
        $learning = Learning::with('descriptions')->findOrFail($request->id);
        $languages = Language::all();

        return view('admin.cms.learning.edit', compact('learning', 'languages'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $learning = Learning::findOrFail($request->id);
        $updateData = [];

        if ($image = $request->file('image')) {
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/learning/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$imageName);

            $destinationPath = public_path('/uploads/learning');
            $image->move($destinationPath, $imageName);
            $updateData['image'] = $imageName;

            if ($learning->image) {
                $thumbnailPath = public_path('uploads/learning/thumbnail/' . $learning->image);
                $imagePath = public_path('uploads/learning/' . $learning->image);

                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }

                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        $updateData['status'] = $request->status;
        $learning->update($updateData);

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'text_') === 0) {
                $languageId = substr($key, 5);

                $language = Language::find($languageId);

                if ($language) {
                    $description = $learning->descriptions->firstWhere('language', $languageId);
                    if ($description) {
                        $description->update([
                            'text' => $value,
                        ]);
                    } else {
                        BannerDescription::create([
                            'learning_id' => $learning->id,
                            'language' => $language->id,
                            'text' => $value,
                        ]);
                    }
                }
            }
        }
        toastr()->success('Learning updated successfully.');
        return redirect()->route('admin.learning');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Learning::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Learning status change successfully.');
        return redirect()->route('admin.learning');
    }
}
