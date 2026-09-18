<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\Video_tutorial;
use App\Models\Page;
use App\Models\Language;

class Video_tutorialController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Video_tutorial::get();
        return view('admin.cms.video_tutorial.list',$data);
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
        $banner = Video_tutorial::findOrFail($request->id);
        $languages = Language::all();
        $page = Page::where('status',1)->get();

        return view('admin.cms.video_tutorial.edit', compact('banner', 'languages','page'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required'
        ]);
        $banner = Video_tutorial::findOrFail($request->id);
        $updateData = [];
        $updateData['name'] = $request->name;
        $updateData['video_link'] = $request->video_link;
        $updateData['status'] = $request->status;
        $banner->update($updateData);

        toastr()->success('Video Tutorial updated successfully.');
        return redirect()->route('admin.video_tutorial');
    }

    public function delete(Request $request){
        $id = $request->id;
        $loginCheck = Video_tutorial::find($id);

        if($loginCheck){
            if ($loginCheck->icon && file_exists(public_path('uploads/video_tutorial/' . $loginCheck->icon))) {
                unlink(public_path('uploads/video_tutorial/' . $loginCheck->icon));
                unlink(public_path('uploads/video_tutorial/thumbnail/' . $loginCheck->icon));
            }
            $loginCheck->delete();
            toastr()->success('Video Tutorial deleted successfully.');
        } else {
            toastr()->error('Video Tutorial not found.');
        }
        return redirect()->route('admin.video_tutorial');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Video_tutorial::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Video Tutorial status change successfully.');
        return redirect()->route('admin.video_tutorial');
    }

}
