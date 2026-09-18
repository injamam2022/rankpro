<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\RankerFeedbacks;
use App\Models\Language;
use App\Models\Ranker;
use App\Models\User;

class RankerFeedbackController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = RankerFeedbacks::select(['ranker_feedbacks.*','users.first_name','users.last_name','rankers.name as ranker_name'])
                ->leftJoin('rankers', 'ranker_feedbacks.ranker_id', '=', 'rankers.id')
                ->leftJoin('users', 'ranker_feedbacks.user_id', '=', 'users.id')
                ->get();

        return view('admin.ranker_feedback.list',$data);
    }


    public function add(){
        $data = [];
        $data['user_list'] = User::where('status',1)->get();
        $data['ranker_list'] = Ranker::where('status',1)->get();
        return view('admin.ranker_feedback.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'status' => 'required'
                        ]);

        if (1){
            $insertData = [];
            $insertData['ranker_id'] = $request->ranker_id;
            $insertData['user_id'] = $request->student_id;
            $insertData['rating'] = $request->rating;
            $insertData['text'] = $request->text;
            $insertData['status'] = $request->status;

            RankerFeedbacks::create($insertData);
        
            toastr()->success('Ranker Feedback added successfully.');
            return redirect()->route('admin.ranker_feedback');
        }else{
            toastr()->warning('Ranker assign already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['user_list'] = User::where('status',1)->get();
        $data['ranker_list'] = Ranker::where('status',1)->get();
        $data['details'] = RankerFeedbacks::where('id',$request->id)->first();
        return view('admin.ranker_feedback.edit',$data);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'ranker_id' => 'required'
        ]);

        $loginCheck = RankerFeedbacks::where('id', $request->id)->firstOrFail();

        if ($loginCheck) {
            $insertData = [];
            $insertData['ranker_id'] = $request->ranker_id;
            $insertData['user_id'] = $request->student_id;
            $insertData['rating'] = $request->rating;
            $insertData['text'] = $request->text;
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);

            toastr()->success('Ranker Feedback updated successfully.');
            return redirect()->route('admin.ranker_feedback');
        } else {
            return back()->withInput();
        }
    }


    public function delete(Request $request){
        $id = $request->id;
        RankerFeedbacks::where('id',$id)->delete();
        toastr()->success('Ranker Feedback deleted successfully.');
        return redirect()->route('admin.ranker_feedback');
    }

    public function change(Request $request)
    {
        $id = $request->id;

        $loginCheck = RankerFeedbacks::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }
        toastr()->success('Ranker Feedback status change successfully.');

        return redirect()->route('admin.ranker_feedback');
    }
}
