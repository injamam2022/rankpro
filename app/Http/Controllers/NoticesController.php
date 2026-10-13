<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Exam_user;
use App\Models\Notice_user;
use App\Models\StudentNotification;
use App\Services\StudentNotificationService;
use Illuminate\Support\Facades\Schema;

use DB;

class NoticesController extends Controller
{
    public const PER_PAGE = 10;

    private function getLanguageId(){
        return 1;
    }

    

    public function notice(Request $request){
        $language_id = $this->getLanguageId();

        $data = [];
        $data['user'] = Auth::user();
        $data['subject_list'] = Subject::where('status',1)->get();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['type'] = $request->type;
        $data['notice_count'] = Notice_user::select(['notice_users.*','notices.name','notices.description'])
                                ->leftJoin('notices', 'notices.id', '=', 'notice_users.notice_id')
                                ->where('notice_users.user_id',Auth::user()->id)->where('notice_users.is_deleted',0)->where('notice_users.is_archive',0)->where('notices.status',1)->count();
                                
        $data['archive_count'] = Notice_user::select(['notice_users.*','notices.name','notices.description'])
                                ->leftJoin('notices', 'notices.id', '=', 'notice_users.notice_id')
                                ->where('notice_users.user_id',Auth::user()->id)->where('notice_users.is_archive',1)->where('notice_users.is_deleted',0)->where('notices.status',1)->count();

        $data['favorite_count'] = Notice_user::select(['notice_users.*','notices.name','notices.description'])
                                ->leftJoin('notices', 'notices.id', '=', 'notice_users.notice_id')
                                ->where('notice_users.user_id',Auth::user()->id)->where('notice_users.is_favorite',1)->where('notice_users.is_archive',0)->where('notices.status',1)->count();

        $data['urgent_count'] = Notice_user::select(['notice_users.*','notices.name','notices.description'])
                                ->leftJoin('notices', 'notices.id', '=', 'notice_users.notice_id')
                                ->where('notice_users.user_id',Auth::user()->id)->where('notices.is_urgent',1)->where('notice_users.is_archive',0)->where('notices.status',1)->count();

        $data['notice_list'] = Notice_user::select(['notice_users.*','notices.name','notices.description','notices.is_urgent'])
                                ->leftJoin('notices', 'notices.id', '=', 'notice_users.notice_id')
                                ->where('notice_users.user_id',Auth::user()->id)->where('notice_users.is_deleted',0);
        if($data['type'] == 2){
            $data['notice_list'] = $data['notice_list']->where('notice_users.is_archive',1);
        }else if($data['type'] == 3){
            $data['notice_list'] = $data['notice_list']->where('notice_users.is_favorite',1)->where('notice_users.is_archive',0);
        }else if($data['type'] == 4){
            $data['notice_list'] = $data['notice_list']->where('notices.is_urgent',1)->where('notice_users.is_archive',0);
        }else{
            $data['notice_list'] = $data['notice_list']->where('notice_users.is_archive',0);
        }

        $data['notice_list'] = $data['notice_list']
            ->where('notices.status',1)
            ->orderByDesc('notices.is_urgent')
            ->orderByDesc('notice_users.created_at')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $data['notifications'] = collect();
        $data['notif_tab'] = $request->get('tab', 'all');
        if (Schema::hasTable('student_notifications')) {
            $tab = $data['notif_tab'];
            $q = StudentNotification::where('user_id', Auth::id())->orderByDesc('id');
            if ($request->get('type') == 5 || $tab === 'upcoming') {
                $q->whereIn('type', [
                    StudentNotificationService::TYPE_EXAM_ASSIGNED,
                    StudentNotificationService::TYPE_EXAM_UPCOMING,
                    StudentNotificationService::TYPE_EXAM_LIVE,
                ]);
            } elseif ($request->get('type') == 6 || $tab === 'important') {
                $q->where('is_important', 1);
            }
            $data['notifications'] = $q->paginate(self::PER_PAGE)->withQueryString();
        }

        $isNotifTab = in_array((string) $request->get('type'), ['5', '6'], true)
            || in_array($data['notif_tab'], ['upcoming', 'important'], true);
        $activePager = $isNotifTab ? $data['notifications'] : $data['notice_list'];
        $clamped = $this->redirectIfPageOutOfRange($activePager);
        if ($clamped) {
            return $clamped;
        }

        return view('site.notice',$data);
    }

    protected function redirectIfPageOutOfRange($paginator)
    {
        if (!$paginator || !method_exists($paginator, 'lastPage')) {
            return null;
        }
        $last = max(1, (int) $paginator->lastPage());
        if ((int) $paginator->currentPage() > $last) {
            return redirect()->to($paginator->url($last));
        }

        return null;
    }

    public function notification_read(Request $request, StudentNotificationService $svc)
    {
        $svc->markRead(Auth::id(), (int) $request->id);
        $go = $request->input('to');
        if ($go) {
            return redirect()->to($go);
        }
        return back();
    }

    public function notification_read_all(Request $request, StudentNotificationService $svc)
    {
        $svc->markRead(Auth::id());
        return back();
    }

    public function notification_unread_count(StudentNotificationService $svc)
    {
        if (!Schema::hasTable('student_notifications')) {
            return response()->json(['unread' => 0]);
        }

        return response()->json([
            'unread' => (int) $svc->unreadCount(Auth::id()),
        ]);
    }

    public function notice_favorite(Request $request){

        $id = $request->id;

        $notice_user = Notice_user::where('id',$id)->first();

        if($notice_user){
            if($notice_user->is_favorite == 1){
                $notice_user->update(['is_favorite'=>0]);
                return back()->with('success', 'Notice removed from your favorites.');
            }else{
                $notice_user->update(['is_favorite'=>1]);
                return back()->with('success', 'Notice added to your favorites.');
            }
            
        }else{
            return back()->with('success', 'Notice added to your favorites.');
        }
        
    }

    public function notice_archive(Request $request){

        $id = $request->id;

        $notice_user = Notice_user::where('id',$id)->first();

        if($notice_user){
            $is_archive = ($notice_user->is_archive==1)?0:1;
            $notice_user->update(['is_archive'=>$is_archive]);
        }
        return back()->with('success', 'Notice added to your archive.');
    }

    public function notice_delete(Request $request){

        $id = $request->id;

        $notice_user = Notice_user::where('id',$id)->first();

        if($notice_user){
            $notice_user->update(['is_deleted'=>1]);
        }
        
        return back()->with('success', 'Notice deleted successfully.');
        
    }


    
}
