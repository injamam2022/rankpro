<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Ranker;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Language;

class RankerController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Ranker::get();
        return view('admin.ranker.list',$data);
    }


    public function add(){
        $data = [];
        $data['language_list'] = Language::where('status',1)->get();
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['course_list'] = Course::where('status',1)->get();
        return view('admin.ranker.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Ranker::where('email',$request->email)->first();

        if (!$loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_icon')){
                $insertData['profile_icon'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/ranker/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['profile_icon']);


                $destinationPath = public_path('/uploads/ranker');
                $image->move($destinationPath, $insertData['profile_icon']);
            }
            if ($image = $request->file('icon')){
                $insertData['icon'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/ranker/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['icon']);


                $destinationPath = public_path('/uploads/ranker');
                $image->move($destinationPath, $insertData['icon']);
            }
            if ($image = $request->file('landing_icon')){
                $insertData['landing_icon'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/ranker/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['landing_icon']);


                $destinationPath = public_path('/uploads/ranker');
                $image->move($destinationPath, $insertData['landing_icon']);
            }
            $insertData['name'] = $request->name ?? '';
            $insertData['email'] = $request->email ?? '';
            $insertData['phone_number'] = $request->phone_number ?? '';
            $insertData['college_name'] = $request->college_name ?? '';
            $insertData['about'] = $request->about ?? '';
            $insertData['description'] = $request->description ?? '';
            $insertData['mentorship'] = $request->mentorship ?? '';
            $insertData['test_series'] = $request->test_series ?? '';
            $insertData['location'] = $request->location ?? '';
            $insertData['score'] = $request->score ?? '';
            $insertData['college'] = $request->college ?? '';
            $insertData['year'] = $request->year ?? '';
            $insertData['air'] = $request->air ?? '';
            $insertData['video_link'] = $request->video_link ?? '';
            $insertData['subject_id'] = $request->subject_id ?? '';
            $insertData['language_id'] = $request->language_id ?? '';
            $insertData['course_id'] = $request->course_id ?? '';
            $insertData['is_in_listing'] = $request->is_in_listing ?? '0';
            $insertData['access_token'] = md5(time().$request->email) ?? '';
            $insertData['hash_code'] = md5(time().$request->email) ?? '';
            $insertData['password'] = md5($request->password) ?? '';
            $insertData['status'] = $request->status;

            Ranker::create($insertData);

            toastr()->success('Ranker added successfully.');
            return redirect()->route('admin.ranker.add');
        }else{
            toastr()->warning('Ranker already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['language_list'] = Language::where('status',1)->get();
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['course_list'] = Course::where('status',1)->get();
        $data['details'] = Ranker::where('id',$request->id)->first();
        return view('admin.ranker.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Ranker::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_icon')){
                $insertData['profile_icon'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/ranker/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['profile_icon']);


                $destinationPath = public_path('/uploads/ranker');
                $image->move($destinationPath, $insertData['profile_icon']);

                if($loginCheck->profile_icon){
                    if (file_exists(public_path('uploads/ranker/'.$loginCheck->profile_icon))) {
                        unlink(public_path('uploads/ranker/'.$loginCheck->profile_icon));
                    }
                    if (file_exists(public_path('uploads/ranker/thumbnail/'.$loginCheck->profile_icon))) {
                        unlink(public_path('uploads/ranker/thumbnail/'.$loginCheck->profile_icon));
                    }
                }
            }
            if ($image = $request->file('icon')){
                $insertData['icon'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/ranker/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['icon']);


                $destinationPath = public_path('/uploads/ranker');
                $image->move($destinationPath, $insertData['icon']);

                if($loginCheck->icon){
                    if (file_exists(public_path('uploads/ranker/'.$loginCheck->icon))) {
                        unlink(public_path('uploads/ranker/'.$loginCheck->icon));
                    }
                    if (file_exists(public_path('uploads/ranker/thumbnail/'.$loginCheck->icon))) {
                        unlink(public_path('uploads/ranker/thumbnail/'.$loginCheck->icon));
                    }
                }
            }
            if ($image = $request->file('landing_icon')){
                $insertData['landing_icon'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/ranker/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['landing_icon']);


                $destinationPath = public_path('/uploads/ranker');
                $image->move($destinationPath, $insertData['landing_icon']);

                if($loginCheck->landing_icon){
                    if (file_exists(public_path('uploads/ranker/'.$loginCheck->landing_icon))) {
                        unlink(public_path('uploads/ranker/'.$loginCheck->landing_icon));
                    }
                    if (file_exists(public_path('uploads/ranker/thumbnail/'.$loginCheck->landing_icon))) {
                        unlink(public_path('uploads/ranker/thumbnail/'.$loginCheck->landing_icon));
                    }
                }
            }
            $insertData['name'] = $request->name ?? '';
            $insertData['email'] = $request->email ?? '';
            $insertData['phone_number'] = $request->phone_number ?? '';
            $insertData['college_name'] = $request->college_name ?? '';
            $insertData['about'] = $request->about ?? '';
            $insertData['description'] = $request->description ?? '';
            $insertData['mentorship'] = $request->mentorship ?? '';
            $insertData['test_series'] = $request->test_series ?? '';
            $insertData['location'] = $request->location ?? '';
            $insertData['score'] = $request->score ?? '';
            $insertData['college'] = $request->college ?? '';
            $insertData['year'] = $request->year ?? '';
            $insertData['air'] = $request->air ?? '';
            $insertData['video_link'] = $request->video_link ?? '';
            $insertData['subject_id'] = $request->subject_id ?? '';
            $insertData['language_id'] = $request->language_id ?? '';
            $insertData['course_id'] = $request->course_id ?? '';
            $insertData['is_in_listing'] = $request->is_in_listing ?? '0';
            if($request->password){
                $insertData['password'] = md5($request->password);
            }

            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Ranker updated successfully.');
            return redirect()->route('admin.ranker');
        }else{
            toastr()->warning('Ranker already exist');
            return back()->withInput();
        }
    }

    public function delete(Request $request){
        $id = $request->id;
        Ranker::where('id',$id)->delete();
        toastr()->success('Ranker deleted successfully.');
        return redirect()->route('admin.ranker');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Ranker::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Ranker status change successfully.');
        return redirect()->route('admin.ranker');
    }

}
