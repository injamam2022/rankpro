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

use DB;

class NoticesController extends Controller
{
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
        $data['type'] = $request->type;;
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

        $data['notice_list'] = Notice_user::select(['notice_users.*','notices.name','notices.description'])
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

        $data['notice_list'] = $data['notice_list']->where('notices.status',1)->get();
        return view('site.notice',$data);
    }

    public function notice_favorite(Request $request){

        $id = $request->id;

        $notice_user = Notice_user::where('id',$id)->first();

        if($notice_user){
            if($notice_user->is_favorite == 1){
                $notice_user->update(['is_favorite'=>0]);
                return redirect()->route('notice')->with('success', 'Notice removed from your favorites.');
            }else{
                $notice_user->update(['is_favorite'=>1]);
                return redirect()->route('notice')->with('success', 'Notice added to your favorites.');
            }
            
        }else{
            return redirect()->route('notice')->with('success', 'Notice added to your favorites.');
        }
        
    }

    public function notice_archive(Request $request){

        $id = $request->id;

        $notice_user = Notice_user::where('id',$id)->first();

        if($notice_user){
            $is_archive = ($notice_user->is_archive==1)?0:1;
            $notice_user->update(['is_archive'=>$is_archive]);
        }
        return redirect()->route('notice')->with('success', 'Notice added to your archive.');
    }

    public function notice_delete(Request $request){

        $id = $request->id;

        $notice_user = Notice_user::where('id',$id)->first();

        if($notice_user){
            $notice_user->update(['is_deleted'=>1]);
        }
        
        return redirect()->route('notice')->with('success', 'Notice deleted successfully.');
        
    }


    
}
