<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\Interest;
use App\Models\InterestDescription;
use App\Models\Language;

class InterestController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Interest::with(['descriptions' => function ($query) {
                            $query->select('interest_id', 'language', 'text')
                                ->orderBy('id', 'asc');
                        }])
                        ->get();
        return view('admin.cms.interest.list',$data);
    }

    public function edit(Request $request)
    {
        $interest = Interest::with('descriptions')->findOrFail($request->id);
        $languages = Language::all();

        return view('admin.cms.interest.edit', compact('interest', 'languages'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $interest = Interest::findOrFail($request->id);
        $updateData = [];

        if ($image = $request->file('image')) {
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/interest/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$imageName);

            $destinationPath = public_path('/uploads/interest');
            $image->move($destinationPath, $imageName);
            $updateData['image'] = $imageName;

            if ($interest->image) {
                $thumbnailPath = public_path('uploads/interest/thumbnail/' . $interest->image);
                $imagePath = public_path('uploads/interest/' . $interest->image);

                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }

                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        $updateData['status'] = $request->status;
        $interest->update($updateData);

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'text_') === 0) {
                $languageId = substr($key, 5);

                $language = Language::find($languageId);

                if ($language) {
                    $description = $interest->descriptions->firstWhere('language', $languageId);
                    if ($description) {
                        $description->update([
                            'text' => $value,
                        ]);
                    } else {
                        BannerDescription::create([
                            'interest_id' => $interest->id,
                            'language' => $language->id,
                            'text' => $value,
                        ]);
                    }
                }
            }
        }
        toastr()->success('Interest updated successfully.');
        return redirect()->route('admin.interest');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Interest::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Interest status change successfully.');
        return redirect()->route('admin.interest');
    }
}
