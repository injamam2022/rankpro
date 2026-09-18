<?php

namespace App\Http\Controllers\Rankers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Ranker;
use App\Models\User;

class RankerDashboardController extends Controller
{
    public function index(){
        $data = [];
        dd(session()->get('rankersAuth'));
        $data['total_booking'] = session()->get('rankersAuth');
        $data['total_open_booking'] = 0;
        $data['total_users'] = User::count();
        $data['total_earning'] = 0;
        return view('rankers.dashboard',$data);
    }

    public function list(){
        return view('rankers.list');
    }
    
}
