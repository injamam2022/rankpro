<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Models\Booking;
use App\Models\User;
use App\Models\Ranker;

class RankerController extends Controller
{
    public function index(){
        return view('site.home');
    }

    public function testseries(){
        $user = Auth::user();
        // dd($user);
        return view('site.testseries');
    }
    
}
