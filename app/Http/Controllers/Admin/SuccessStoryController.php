<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\SuccessStory;
use App\Models\SuccessStoryDescription;
use App\Models\Language;

class SuccessStoryController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = SuccessStory::with(['descriptions' => function ($query) {
                            $query->select('success_story_id', 'language', 'text')
                                ->orderBy('id', 'asc');
                        }])
                        ->get();
        return view('admin.cms.success_story.list',$data);
    }

    public function add(){
        $data = [];
        $data['languages'] = Language::where('status',1)->get();
        return view('admin.cms.success_story.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name'    => 'required',
                            'address' => 'required',
                        ]);

        $input = $request->all();

        $insertData = [];
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $insertData['image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/success_story/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$insertData['image']);

            $destinationPath = public_path('/uploads/success_story');
            $image->move($destinationPath, $insertData['image']);
        } elseif ($request->has('image')) {
            $insertData['image'] = $request->image;
        }
        $insertData['name']    = $request->name;
        $insertData['address'] = $request->address;
        $insertData['status']  = $request->status;
        $success_story = SuccessStory::create($insertData);

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'text_') === 0) {
                $languageId = substr($key, 5);

                $language = Language::find($languageId);

                if ($language) {
                    SuccessStoryDescription::create([
                        'success_story_id' => $banner->id,
                        'language' => $language->id,
                        'text' => $value,
                    ]);
                }
            }
        }

        toastr()->success('SuccessStory added successfully.');
        return redirect()->route('admin.success_story');
    }

    public function edit(Request $request)
    {
        $success_story = SuccessStory::with('descriptions')->findOrFail($request->id);
        $languages = Language::all();

        return view('admin.cms.success_story.edit', compact('success_story', 'languages'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name'    => 'required',
            'address' => 'required',
        ]);

        $success_story = SuccessStory::findOrFail($request->id);
        $updateData = [];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/success_story/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$imageName);

            $destinationPath = public_path('/uploads/success_story');
            $image->move($destinationPath, $imageName);

            if (!empty($success_story->image) && !filter_var($success_story->image, FILTER_VALIDATE_URL)) {
                $thumbnailPath = public_path('uploads/success_story/thumbnail/' . $success_story->image);
                $imagePath = public_path('uploads/success_story/' . $success_story->image);

                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $updateData['image'] = $imageName;
        } elseif ($request->has('image')) {
            $updateData['image'] = $request->image;
        }

        $updateData['name']    = $request->name;
        $updateData['address'] = $request->address;
        $updateData['status']  = $request->status;

        $success_story->update($updateData);

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'text_') === 0) {
                $languageId = substr($key, 5);

                $language = Language::find($languageId);

                if ($language) {
                    $description = $success_story->descriptions->firstWhere('language', $languageId);
                    if ($description) {
                        $description->update([
                            'text' => $value,
                        ]);
                    } else {
                        SuccessStoryDescription::create([
                            'success_story_id' => $success_story->id,
                            'language' => $language->id,
                            'text' => $value,
                        ]);
                    }
                }
            }
        }

        toastr()->success('SuccessStory updated successfully.');
        return redirect()->route('admin.success_story');
    }

    public function delete(Request $request){
        $id = $request->id;
        $loginCheck = SuccessStory::find($id);

        if($loginCheck){
            if ($loginCheck->image && file_exists(public_path('uploads/success_story/' . $loginCheck->image))) {
                unlink(public_path('uploads/success_story/' . $loginCheck->image));
                unlink(public_path('uploads/success_story/thumbnail/' . $loginCheck->image));
            }
            $loginCheck->delete();
            toastr()->success('SuccessStory deleted successfully.');
        } else {
            toastr()->error('SuccessStory not found.');
        }
        return redirect()->route('admin.success_story');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = SuccessStory::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('SuccessStory status change successfully.');
        return redirect()->route('admin.success_story');
    }
}
