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
        $data['list'] = Notice::withCount('noticeUsers')->orderByDesc('id')->get();
        return view('admin.notice.list',$data);
    }


    public function add(){
        $data = [];
        $data['student_list'] = User::where('status',1)->orderBy('first_name')->orderBy('last_name')->get();
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
            $audience = $this->resolveRecipients($request);
            $recipientIds = $audience['ids'];

            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['description'] = $request->description;
            $insertData['is_urgent'] = $request->is_urgent;
            $insertData['status'] = $request->status;
            if (Schema::hasColumn('notices', 'is_for_all')) {
                $insertData['is_for_all'] = $audience['is_for_all'] ? 1 : 0;
            }

            $notice = Notice::create($insertData);

            $this->storeNoticeUsers($notice->id, $recipientIds);

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
        Notice_user::where('notice_id', $id)->delete();
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

    /**
     * @return array{ids:\Illuminate\Support\Collection,is_for_all:bool}
     */
    private function resolveRecipients(Request $request): array
    {
        $userId = $request->input('user_id');
        $examId = $request->input('exam_id');
        $classId = $request->input('class_id');
        $specificUser = $userId !== null && $userId !== '' && $userId !== 'all' && $userId !== '0';

        if ($specificUser) {
            return [
                'ids' => collect([(int) $userId])->filter()->unique()->values(),
                'is_for_all' => false,
            ];
        }

        $query = User::query()->where('status', 1);

        if (!empty($classId) && (string) $classId !== '0') {
            $classId = (string) $classId;
            $query->where(function ($q) use ($classId) {
                $q->where('class_name', $classId)
                    ->orWhere('class_name', $classId.'th')
                    ->orWhere('class_name', 'like', $classId.' %');
            });
        }

        if (!empty($examId)) {
            $examUserIds = collect();
            if (Schema::hasTable('exam_assignments')) {
                $examUserIds = $examUserIds->merge(
                    DB::table('exam_assignments')->where('exam_id', $examId)->pluck('user_id')
                );
            }
            $examUserIds = $examUserIds
                ->merge(Exam_user::where('exam_id', $examId)->pluck('user_id'))
                ->unique()
                ->filter()
                ->values();

            $query->whereIn('id', $examUserIds);

            return [
                'ids' => $query->pluck('id'),
                'is_for_all' => false,
            ];
        }

        return [
            'ids' => $query->pluck('id'),
            'is_for_all' => empty($classId) || (string) $classId === '0',
        ];
    }

    private function storeNoticeUsers(int $noticeId, $recipientIds): void
    {
        $ids = collect($recipientIds)->filter()->unique()->values();
        if ($ids->isEmpty()) {
            return;
        }

        $now = now();
        $rows = [];
        foreach ($ids as $rid) {
            $rows[] = [
                'notice_id' => $noticeId,
                'user_id' => (int) $rid,
                'is_favorite' => 0,
                'is_archive' => 0,
                'is_urgent' => 0,
                'is_deleted' => 0,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            if (count($rows) >= 500) {
                Notice_user::insert($rows);
                $rows = [];
            }
        }
        if ($rows) {
            Notice_user::insert($rows);
        }
    }
}
