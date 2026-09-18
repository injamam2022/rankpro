<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\Dashboard_banner;
use App\Models\Language;

class Dashboard_bannerController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Dashboard_banner::get();
        return view('admin.cms.dashboard_banner.list',$data);
    }

    public function add(){
        $data = [];
        $data['languages'] = Language::where('status',1)->get();
        return view('admin.cms.dashboard_banner.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                        ]);

        $input = $request->all();

        $insertData = [];
        if ($image = $request->file('icon')){
            $insertData['icon'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/dashboard_banner/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$insertData['icon']);


            $destinationPath = public_path('/uploads/dashboard_banner');
            $image->move($destinationPath, $insertData['icon']);
        }
        $insertData['status'] = $request->status;
        $insertData['video_link'] = $request->video_link;

        Dashboard_banner::create($insertData);
        

        toastr()->success('Dashboard banner added successfully.');
        return redirect()->route('admin.dashboard_banner');
    }

    public function edit(Request $request)
    {
        $banner = Dashboard_banner::findOrFail($request->id);
        $languages = Language::all();

        return view('admin.cms.dashboard_banner.edit', compact('banner', 'languages'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $banner = Dashboard_banner::findOrFail($request->id);
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
        $updateData['video_link'] = $request->video_link;
        $updateData['status'] = $request->status;
        $banner->update($updateData);

        toastr()->success('Dashboard Banner updated successfully.');
        return redirect()->route('admin.dashboard_banner');
    }

    public function delete(Request $request){
        $id = $request->id;
        $loginCheck = Dashboard_banner::find($id);

        if($loginCheck){
            if ($loginCheck->icon && file_exists(public_path('uploads/dashboard_banner/' . $loginCheck->icon))) {
                unlink(public_path('uploads/dashboard_banner/' . $loginCheck->icon));
                unlink(public_path('uploads/dashboard_banner/thumbnail/' . $loginCheck->icon));
            }
            $loginCheck->delete();
            toastr()->success('Dashboard Banner deleted successfully.');
        } else {
            toastr()->error('Dashboard Banner not found.');
        }
        return redirect()->route('admin.dashboard_banner');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Dashboard_banner::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Dashboard Banner status change successfully.');
        return redirect()->route('admin.dashboard_banner');
    }

}
