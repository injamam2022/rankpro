<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Country;
use App\Models\State;
use App\Models\City;

class CityController extends Controller
{

    public function list(){
        $data = [];
        $data['list'] = City::select(["cities.*","countries.name as country_name","states.name as state_name"])->leftJoin('states', 'states.id', '=', 'cities.state_id')->leftJoin('countries', 'states.country_id', '=', 'countries.id')->get();
        return view('admin.city.list',$data);
    }

    public function add(){
        $data = [];
        $data['country_list'] = Country::where('status',1)->get();
        $data['state_list'] = [];
        return view('admin.city.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required',
                            'state_id' => 'required'
                        ]);

        $input = $request->all();
        //dd($input);
        $loginCheck = City::where(
            [
                'name'=> $input['name']
            ])->first();

        if (!$loginCheck){
            $saveData = [];
            $saveData['name'] = $request->name;
            $saveData['country_id'] = $request->country_id;
            $saveData['state_id'] = $request->state_id;
            $saveData['status'] = $request->status;

            City::create($saveData);
        
            toastr()->success('City added successfully.');
            return redirect()->route('admin.city.add');
        }else{
            toastr()->warning('City already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = City::where(
            [
                'id'=> $input['id']
            ])->first();
        $data['country_list'] = Country::where('status',1)->get();
        $data['state_list'] = State::where('status',1)->where('country_id',$data['details']->country_id)->get();
        return view('admin.city.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required',
                            'state_id' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = City::where(
            [
                'id'=> $input['id']
            ])->first();

        if ($loginCheck){
            $saveData = [];
            $saveData['name'] = $request->name;
            $saveData['country_id'] = $request->country_id;
            $saveData['state_id'] = $request->state_id;
            $saveData['status'] = $request->status;
            
            $loginCheck->update($saveData);
            toastr()->success('City updated successfully.');
            return redirect()->route('admin.city');
        }else{
            toastr()->warning('City already exist');
            return back()->withInput();
        }
    }
    
}
