<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Ranker_assign;
use App\Models\User;
use App\Models\Ranker;

class RankerAssignController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Ranker_assign::select(['ranker_assigns.*','rankers.name as ranker_name','users.first_name','users.last_name'])
                        ->leftJoin('users', 'ranker_assigns.student_id', '=', 'users.id')
                        ->leftJoin('rankers', 'ranker_assigns.ranker_id', '=', 'rankers.id')
                        ->get();
        return view('admin.ranker_assign.list',$data);
    }


    public function add(){
        $data = [];
        $data['student_list'] = User::where('status',1)->get();
        $data['ranker_list'] = Ranker::where('status',1)->get();
        return view('admin.ranker_assign.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'student_id' => 'required'
                        ]);

        $input = $request->all();

        //$loginCheck = Ranker_assign::where('email',$request->email)->first();

        if (1){
            $insertData = [];
            $insertData['student_id'] = $request->student_id;
            $insertData['ranker_id'] = $request->ranker_id;
            $insertData['date'] = $request->date;
            $insertData['payment_amount'] = $request->payment_amount;
            $insertData['status'] = $request->status;

            Ranker_assign::create($insertData);
        
            toastr()->success('Ranker assign added successfully.');
            return redirect()->route('admin.ranker_assign.add');
        }else{
            toastr()->warning('Ranker assign already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Ranker_assign::where('id',$request->id)->first();
        $data['student_list'] = User::where('status',1)->get();
        $data['ranker_list'] = Ranker::where('status',1)->get();
        return view('admin.ranker_assign.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'student_id' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Ranker_assign::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['student_id'] = $request->student_id;
            $insertData['ranker_id'] = $request->ranker_id;
            $insertData['date'] = $request->date;
            $insertData['payment_amount'] = $request->payment_amount;
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Ranker assign updated successfully.');
            return redirect()->route('admin.ranker_assign');
        }else{
            toastr()->warning('Ranker assign already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Ranker_assign::where('id',$id)->delete();
        toastr()->success('Ranker assign deleted successfully.');
        return redirect()->route('admin.ranker_assign');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Ranker_assign::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Ranker assign status change successfully.');
        return redirect()->route('admin.ranker_assign');
    }
    
}
