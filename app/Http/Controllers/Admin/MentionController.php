<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\AsMention;

class MentionController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = AsMention::all();
        return view('admin.cms.mention.list',$data);
    }

    public function add(){
        return view('admin.cms.mention.add');
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                        ]);

        $input = $request->all();

        $insertData = [];
        if ($image = $request->file('image')){
            $insertData['image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/mention/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$insertData['image']);


            $destinationPath = public_path('/uploads/mention');
            $image->move($destinationPath, $insertData['image']);
        }
        $insertData['status'] = $request->status;

        $mention = AsMention::create($insertData);

        toastr()->success('Image added successfully.');
        return redirect()->route('admin.mention');
    }

    public function delete(Request $request){
        $id = $request->id;
        $loginCheck = AsMention::find($id);

        if($loginCheck){
            if ($loginCheck->image && file_exists(public_path('uploads/mention/' . $loginCheck->image))) {
                unlink(public_path('uploads/mention/' . $loginCheck->image));
                unlink(public_path('uploads/mention/thumbnail/' . $loginCheck->image));
            }
            $loginCheck->delete();
            toastr()->success('Image deleted successfully.');
        } else {
            toastr()->error('Image not found.');
        }
        return redirect()->route('admin.mention');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = AsMention::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Image status change successfully.');
        return redirect()->route('admin.mention');
    }
}
