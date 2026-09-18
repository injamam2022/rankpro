<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\Gallery;

class GalleryController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Gallery::all();
        return view('admin.cms.gallery.list',$data);
    }

    public function add(){
        return view('admin.cms.gallery.add');
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                        ]);

        $input = $request->all();

        $insertData = [];
        if ($image = $request->file('image')){
            $insertData['image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/gallery/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$insertData['image']);


            $destinationPath = public_path('/uploads/gallery');
            $image->move($destinationPath, $insertData['image']);
        }
        $insertData['status'] = $request->status;

        $gallery = Gallery::create($insertData);

        toastr()->success('Image added successfully.');
        return redirect()->route('admin.gallery');
    }

    public function delete(Request $request){
        $id = $request->id;
        $loginCheck = Gallery::find($id);

        if($loginCheck){
            if ($loginCheck->image && file_exists(public_path('uploads/gallery/' . $loginCheck->image))) {
                unlink(public_path('uploads/gallery/' . $loginCheck->image));
                unlink(public_path('uploads/gallery/thumbnail/' . $loginCheck->image));
            }
            $loginCheck->delete();
            toastr()->success('Image deleted successfully.');
        } else {
            toastr()->error('Image not found.');
        }
        return redirect()->route('admin.gallery');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Gallery::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Image status change successfully.');
        return redirect()->route('admin.gallery');
    }
}
