<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Subscription::get();
        return view('admin.subscription.list',$data);
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Subscription::where('id',$id)->delete();
        toastr()->success('Subscription deleted successfully.');
        return redirect()->route('admin.subscription');
    }
}