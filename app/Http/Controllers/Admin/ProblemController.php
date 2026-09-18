<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Problem;
use App\Models\Problem_type;
use App\Models\User;

class ProblemController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Problem::select(['problems.id','problems.message','problems.status','problem_types.name','users.first_name','users.last_name','users.email_id'])
                        ->leftJoin('problem_types', 'problem_types.id', '=', 'problems.type')
                        ->leftJoin('users', 'users.id', '=', 'problems.user_id')
                        ->get();
        return view('admin.problem.list',$data);
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['problem_type_list'] = Problem_type::where('status',1)->get();
        $data['user_list'] = User::where('status',1)->get();
        $data['details'] = Problem::where('id',$request->id)->first();
        return view('admin.problem.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'type' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Problem::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            
            $insertData['type'] = $request->type;
            $insertData['message'] = $request->message;
            $insertData['user_id'] = $request->user_id;
            
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Problem updated successfully.');
            return redirect()->route('admin.problem');
        }else{
            toastr()->warning('Problem already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Problem::where('id',$id)->delete();
        toastr()->success('Problem deleted successfully.');
        return redirect()->route('admin.problem');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Problem::where('id',$request->id)->first();
        // dd($loginCheck);
        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Problem status change successfully.');
        return redirect()->route('admin.problem');
    }
    
}
