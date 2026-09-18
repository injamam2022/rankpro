<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\Banner;
use App\Models\BannerDescription;
use App\Models\Language;

class BannerController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Banner::with(['descriptions' => function ($query) {
                            $query->select('banner_id', 'language', 'text')
                                ->orderBy('id', 'asc');
                        }])
                        ->get();
        return view('admin.cms.banner.list',$data);
    }

    public function add(){
        $data = [];
        $data['languages'] = Language::where('status',1)->get();
        return view('admin.cms.banner.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                        ]);

        $input = $request->all();

        $insertData = [];
        if ($image = $request->file('image')){
            $insertData['image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/banner/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$insertData['image']);


            $destinationPath = public_path('/uploads/banner');
            $image->move($destinationPath, $insertData['image']);
        }
        $insertData['status'] = $request->status;

        $banner = Banner::create($insertData);
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'text_') === 0) {
                $languageId = substr($key, 5);

                $language = Language::find($languageId);

                if ($language) {
                    BannerDescription::create([
                        'banner_id' => $banner->id,
                        'language' => $language->id,
                        'text' => $value,
                    ]);
                }
            }
        }

        toastr()->success('Banner added successfully.');
        return redirect()->route('admin.banner');
    }

    public function edit(Request $request)
    {
        $banner = Banner::with('descriptions')->findOrFail($request->id);
        $languages = Language::all();

        return view('admin.cms.banner.edit', compact('banner', 'languages'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $banner = Banner::findOrFail($request->id);
        $updateData = [];

        if ($image = $request->file('image')) {
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/banner/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$imageName);

            $destinationPath = public_path('/uploads/banner');
            $image->move($destinationPath, $imageName);
            $updateData['image'] = $imageName;

            if ($banner->image) {
                $thumbnailPath = public_path('uploads/banner/thumbnail/' . $banner->image);
                $imagePath = public_path('uploads/banner/' . $banner->image);

                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }

                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        $updateData['status'] = $request->status;
        $banner->update($updateData);

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'text_') === 0) {
                $languageId = substr($key, 5);

                $language = Language::find($languageId);

                if ($language) {
                    $description = $banner->descriptions->firstWhere('language', $languageId);
                    if ($description) {
                        $description->update([
                            'text' => $value,
                        ]);
                    } else {
                        BannerDescription::create([
                            'banner_id' => $banner->id,
                            'language' => $language->id,
                            'text' => $value,
                        ]);
                    }
                }
            }
        }
        toastr()->success('Banner updated successfully.');
        return redirect()->route('admin.banner');
    }

    public function delete(Request $request){
        $id = $request->id;
        $loginCheck = Banner::find($id);

        if($loginCheck){
            if ($loginCheck->image && file_exists(public_path('uploads/banner/' . $loginCheck->image))) {
                unlink(public_path('uploads/banner/' . $loginCheck->image));
                unlink(public_path('uploads/banner/thumbnail/' . $loginCheck->image));
            }
            $loginCheck->delete();
            toastr()->success('Banner deleted successfully.');
        } else {
            toastr()->error('Banner not found.');
        }
        return redirect()->route('admin.banner');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Banner::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Banner status change successfully.');
        return redirect()->route('admin.banner');
    }

}
