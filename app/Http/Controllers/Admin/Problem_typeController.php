<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Problem_type;

class Problem_typeController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Problem_type::get();
        return view('admin.problem_type.list',$data);
    }

    public function add(){
        $data = [];
        return view('admin.problem_type.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Problem_type::where('name',$request->name)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['description'] = $request->description;
            $insertData['status'] = $request->status;

            Problem_type::create($insertData);
        
            toastr()->success('Problem type added successfully.');
            return redirect()->route('admin.problem_type');
        }else{
            toastr()->warning('Problem type already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Problem_type::where('id',$request->id)->first();
        return view('admin.problem_type.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Problem_type::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['description'] = $request->description;            
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Problem type updated successfully.');
            return redirect()->route('admin.problem_type');
        }else{
            toastr()->warning('Problem type already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Problem_type::where('id',$id)->delete();
        toastr()->success('Problem type deleted successfully.');
        return redirect()->route('admin.problem_type');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Problem_type::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Problem type status change successfully.');
        return redirect()->route('admin.problem_type');
    }
    
}
