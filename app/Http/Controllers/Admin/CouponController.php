<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Coupon;

class CouponController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Coupon::get();
        return view('admin.coupon.list',$data);
    }


    public function add(){
        $data = [];
        return view('admin.coupon.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Coupon::where('name',$request->name)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['code'] = $request->code;
            $insertData['type'] = $request->type;
            $insertData['value'] = $request->value;
            $insertData['start_date'] = $request->start_date;
            $insertData['end_date'] = $request->end_date;
            $insertData['status'] = $request->status;

            Coupon::create($insertData);
        
            toastr()->success('Coupon added successfully.');
            return redirect()->route('admin.coupon');
        }else{
            toastr()->warning('Coupon already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Coupon::where('id',$request->id)->first();
        return view('admin.coupon.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Coupon::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            
            $insertData['name'] = $request->name;
            $insertData['code'] = $request->code;
            $insertData['type'] = $request->type;
            $insertData['value'] = $request->value;
            $insertData['start_date'] = $request->start_date;
            $insertData['end_date'] = $request->end_date;
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Coupon updated successfully.');
            return redirect()->route('admin.coupon');
        }else{
            toastr()->warning('Coupon already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Coupon::where('id',$id)->delete();
        toastr()->success('Coupon deleted successfully.');
        return redirect()->route('admin.coupon');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Coupon::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Coupon status change successfully.');
        return redirect()->route('admin.coupon');
    }
    
}
