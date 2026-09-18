<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Contact;

class ContactController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Contact::select(['contacts.*','locations.location_name'])
                        ->leftJoin('locations', 'contacts.location_id', '=', 'locations.id')
                        ->get();
        return view('admin.contact.list',$data);
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Contact::where('id',$request->id)->first();
        return view('admin.contact.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Contact::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            
            $insertData['name'] = $request->name;
            $insertData['email'] = $request->email;
            $insertData['mobile_number'] = $request->mobile_number;
            $insertData['message'] = $request->message;
            
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Contact updated successfully.');
            return redirect()->route('admin.contact');
        }else{
            toastr()->warning('Contact already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Contact::where('id',$id)->delete();
        toastr()->success('Contact deleted successfully.');
        return redirect()->route('admin.contact');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Contact::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Contact status change successfully.');
        return redirect()->route('admin.contact');
    }
    
}
