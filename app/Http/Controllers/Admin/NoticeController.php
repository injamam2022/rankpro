<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Notice;
use App\Models\User;
use App\Models\Notice_user;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Exam_user;
use App\Services\StudentNotificationService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Notice::get();
        return view('admin.notice.list',$data);
    }


    public function add(){
        $data = [];
        $data['student_list'] = User::where('status',1)->get();
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['exam_list'] = Exam::where('status',1)->get();
        return view('admin.notice.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Notice::where('name',$request->name)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['description'] = $request->description;
            $insertData['is_urgent'] = $request->is_urgent;
            $insertData['status'] = $request->status;

            $notice = Notice::create($insertData);

            $class_id = $request->class_id;
            $exam_type = $request->exam_type;
            $exam_id = $request->exam_id;
            $test_series = $request->test_series;
            $user_id = $request->user_id;

            $recipientIds = collect();
            if(!empty($user_id)){
                $recipientIds = collect([(int) $user_id]);
            }else if(!empty($exam_id)){
                $recipientIds = DB::table('exam_assignments')->where('exam_id', $exam_id)->pluck('user_id')
                    ->merge(Exam_user::where('exam_id', $exam_id)->pluck('user_id'))
                    ->unique()->values();
            }

            foreach ($recipientIds as $rid) {
                Notice_user::create([
                    'notice_id' => $notice->id,
                    'user_id' => $rid,
                ]);
            }

            if (Schema::hasTable('student_notifications') && $recipientIds->isNotEmpty()) {
                app(StudentNotificationService::class)->notify(
                    $recipientIds,
                    StudentNotificationService::TYPE_ADMIN_NOTICE,
                    $notice->name,
                    [
                        'body' => \Illuminate\Support\Str::limit(strip_tags($notice->description ?? ''), 180),
                        'is_important' => (int) $notice->is_urgent === 1,
                        'notifiable_type' => Notice::class,
                        'notifiable_id' => $notice->id,
                        'action_url' => route('notice'),
                    ]
                );
            }

            toastr()->success('Notice added successfully.');
            return redirect()->route('admin.notice');
        }else{
            toastr()->warning('Notice already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Notice::where('id',$request->id)->first();
        return view('admin.notice.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Notice::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;  
            $insertData['description'] = $request->description;        
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Notice updated successfully.');
            return redirect()->route('admin.notice');
        }else{
            toastr()->warning('Notice already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Notice::where('id',$id)->delete();
        toastr()->success('Notice deleted successfully.');
        return redirect()->route('admin.notice');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Notice::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Notice status change successfully.');
        return redirect()->route('admin.notice');
    }
    
}
