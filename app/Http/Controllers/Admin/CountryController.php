<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Country;

class CountryController extends Controller
{

    public function list(){
        $data = [];
        $data['list'] = Country::get();
        return view('admin.country.list',$data);
    }

    public function add(){
        $data = [];
        return view('admin.country.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        //dd($input);
        $loginCheck = Country::where(
            [
                'name'=> $input['name']
            ])->first();

        if (!$loginCheck){
            $saveData = [];
            $saveData['name'] = $request->name;
            $saveData['sortname'] = $request->sortname;
            $saveData['phonecode'] = $request->phonecode;
            $saveData['status'] = $request->status;

            Country::create($saveData);
        
            toastr()->success('Country added successfully.');
            return redirect()->route('admin.country.add');
        }else{
            toastr()->warning('Country already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Country::where(
            [
                'id'=> $input['id']
            ])->first();
        return view('admin.country.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Country::where(
            [
                'id'=> $input['id']
            ])->first();

        if ($loginCheck){
            $saveData = [];
            $saveData['name'] = $request->name;
            $saveData['sortname'] = $request->sortname;
            $saveData['phonecode'] = $request->phonecode;
            $saveData['status'] = $request->status;
            
            $loginCheck->update($saveData);
            toastr()->success('Country updated successfully.');
            return redirect()->route('admin.country');
        }else{
            toastr()->warning('Country already exist');
            return back()->withInput();
        }
    }
    
}
