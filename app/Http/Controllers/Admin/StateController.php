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

class StateController extends Controller
{

    public function list(){
        $data = [];
        $data['list'] = State::select(["states.*","countries.name as country_name"])->leftJoin('countries', 'states.country_id', '=', 'countries.id')->get();
        return view('admin.state.list',$data);
    }

    public function add(){
        $data = [];
        $data['country_list'] = Country::where('status',1)->get();
        return view('admin.state.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        //dd($input);
        $loginCheck = State::where(
            [
                'name'=> $input['name']
            ])->first();

        if (!$loginCheck){
            $saveData = [];
            $saveData['name'] = $input['name'];
            $saveData['country_id'] = $input['country_id'];
            $saveData['status'] = $input['status'];

            State::create($saveData);
        
            toastr()->success('State added successfully.');
            return redirect()->route('admin.state.add');
        }else{
            toastr()->warning('State already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = State::where(
            [
                'id'=> $input['id']
            ])->first();
        $data['country_list'] = Country::where('status',1)->get();
        return view('admin.state.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = State::where(
            [
                'id'=> $input['id']
            ])->first();

        if ($loginCheck){
            $saveData = [];
            $saveData['name'] = $input['name'];
            $saveData['country_id'] = $input['country_id'];
            $saveData['status'] = $input['status'];
            
            $loginCheck->update($saveData);
            toastr()->success('State updated successfully.');
            return redirect()->route('admin.state');
        }else{
            toastr()->warning('State already exist');
            return back()->withInput();
        }
    }
    
}
