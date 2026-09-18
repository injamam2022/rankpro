<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\Cms_text;
use App\Models\Page;
use App\Models\Language;

class CmsController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Cms_text::get();
        return view('admin.cms.cms.list',$data);
    }

    public function add(){
        $data = [];
        $data['languages'] = Language::where('status',1)->get();
        $data['page'] = Page::where('status',1)->get();
        return view('admin.cms.video_tutorial.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                        ]);

        $input = $request->all();

        $insertData = [];
        $insertData['status'] = $request->status;

        $insertData['video_link'] = $request->video_link;

        Video_tutorial::create($insertData);
        

        toastr()->success('Video Tutorial added successfully.');
        return redirect()->route('admin.video_tutorial');
    }

    public function edit(Request $request)
    {
        $details = Cms_text::findOrFail($request->id);
        $languages = Language::all();
        $page = Page::where('status',1)->get();

        return view('admin.cms.cms.edit', compact('details', 'languages','page'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'banner_header' => 'required'
        ]);
        $banner = Cms_text::findOrFail($request->id);

        $updateData = [];
        $updateData['banner_header'] = $request->banner_header;
        $updateData['banner_description'] = $request->banner_description;

        if ($image = $request->file('banner_logo')){
            $updateData['banner_logo'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/banner/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$updateData['banner_logo']);


            $destinationPath = public_path('/uploads/banner');
            $image->move($destinationPath, $updateData['banner_logo']);

            if($banner->banner_logo){
                if (file_exists(public_path('uploads/banner/'.$banner->banner_logo))) {
                    unlink(public_path('uploads/banner/'.$banner->banner_logo));
                }
                if (file_exists(public_path('uploads/banner/thumbnail/'.$banner->banner_logo))) {
                    unlink(public_path('uploads/banner/thumbnail/'.$banner->banner_logo));
                }
            }
        }

        $updateData['header'] = $request->header;
        $updateData['description'] = $request->description;
        $updateData['status'] = $request->status;
        $banner->update($updateData);

        toastr()->success('CMS updated successfully.');
        return redirect()->route('admin.cms');
    }

    public function delete(Request $request){
        $id = $request->id;
        $loginCheck = Cms_text::find($id);

        if($loginCheck){
            if ($loginCheck->icon && file_exists(public_path('uploads/cms/' . $loginCheck->icon))) {
                unlink(public_path('uploads/cms/' . $loginCheck->icon));
                unlink(public_path('uploads/cms/thumbnail/' . $loginCheck->icon));
            }
            $loginCheck->delete();
            toastr()->success('CMS deleted successfully.');
        } else {
            toastr()->error('CMS not found.');
        }
        return redirect()->route('admin.cms');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Cms_text::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('CMS status change successfully.');
        return redirect()->route('admin.cms');
    }

}
