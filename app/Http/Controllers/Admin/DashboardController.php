<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Ranker;
use App\Models\TestSeries;
use App\Models\Location;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(){
        $data = [];
        $data['total_test_series'] = TestSeries::count();
        $data['total_ranker'] = Ranker::count();
        $data['total_users'] = User::count();
        $data['total_location'] = Location::count();
        return view('admin.dashboard',$data);
    }

    public function list(){
        return view('admin.list');
    }
    
}
