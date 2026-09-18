<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Location;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class LocationController extends Controller
{

    public function list(){
        $data = [];
        $data['list'] = Location::select(["locations.*","countries.name as country_name","states.name as state_name","cities.name as city_name"])->leftJoin('states', 'states.id', '=', 'locations.state_id')->leftJoin('countries', 'locations.country_id', '=', 'countries.id')->leftJoin('cities', 'locations.city_id', '=', 'cities.id')->get();
        // dd($data);
        return view('admin.location.list',$data);
    }

    public function add(){
        $data = [];
        $data['country_list'] = Country::where('status',1)->get();
        return view('admin.location.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'location_name' => 'required',
                            'country_id' => 'required',
                            'state_id' => 'required',
                            'city_id' => 'required'
                        ]);

        $input = $request->all();
        //dd($input);
        $loginCheck = Location::where(
            [
                'location_name'=> $input['location_name']
            ])->first();

        if (!$loginCheck){
            $saveData = [];
            if ($image = $request->file('logo')){
                $saveData['logo'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/location/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$saveData['logo']);


                $destinationPath = public_path('/uploads/location');
                $image->move($destinationPath, $saveData['logo']);
            }
            $saveData['location_name'] = $request->location_name;
            $saveData['location_description'] = $request->location_description;
            $saveData['address'] = $request->address;
            $saveData['zip_code'] = $request->zip_code;
            $saveData['phone_number'] = $request->phone_number;
            $saveData['country_id'] = $request->country_id;
            $saveData['state_id'] = $request->state_id;
            $saveData['city_id'] = $request->city_id;
            $saveData['status'] = $request->status;

            Location::create($saveData);
        
            toastr()->success('Location added successfully.');
            return redirect()->route('admin.location.add');
        }else{
            toastr()->warning('Location already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Location::where(
            [
                'id'=> $input['id']
            ])->first();
        $data['country_list'] = Country::where('status',1)->get();
        $data['state_list'] = State::where('status',1)->where('country_id',$data['details']->country_id)->get();
        $data['city_list'] = City::where('status',1)->where('state_id',$data['details']->state_id)->get();
        return view('admin.location.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'location_name' => 'required',
                            'country_id' => 'required',
                            'state_id' => 'required',
                            'city_id' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Location::where(
            [
                'id'=> $input['id']
            ])->first();

        if ($loginCheck){
            $saveData = [];
            if ($image = $request->file('logo')){
                $saveData['logo'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/location/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$saveData['logo']);


                $destinationPath = public_path('/uploads/location');
                $image->move($destinationPath, $saveData['logo']);

                if($loginCheck->logo){
                    if (file_exists(public_path('uploads/location/'.$loginCheck->logo))) {
                        unlink(public_path('uploads/location/'.$loginCheck->logo));
                    }
                    if (file_exists(public_path('uploads/location/thumbnail/'.$loginCheck->logo))) {
                        unlink(public_path('uploads/location/thumbnail/'.$loginCheck->logo));
                    }
                }
            }
            $saveData['country_id'] = $request->country_id;
            $saveData['state_id'] = $request->state_id;
            $saveData['city_id'] = $request->city_id;
            $saveData['location_name'] = $request->location_name;
            $saveData['location_description'] = $request->location_description;
            $saveData['address'] = $request->address;
            $saveData['zip_code'] = $request->zip_code;
            $saveData['phone_number'] = $request->phone_number;
            $saveData['status'] = $request->status;
            // dd($saveData);
            $loginCheck->update($saveData);
            toastr()->success('Location updated successfully.');
            return redirect()->route('admin.location');
        }else{
            toastr()->warning('Location already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Location::where('id',$id)->delete();
        toastr()->success('Location deleted successfully.');
        return redirect()->route('admin.location');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Location::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Location status change successfully.');
        return redirect()->route('admin.location');
    }
    
}
