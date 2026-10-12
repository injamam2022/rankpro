<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Footer;
use App\Support\WebsiteSettings;

class FooterController extends Controller
{
    public function list(){
        $data = [];
        $data['settings'] = WebsiteSettings::all();
        return view('admin.footer.settings',$data);
    }

    public function saveSettings(Request $request)
    {
        foreach (WebsiteSettings::defaults() as $key => $meta) {
            $value = trim((string) $request->input($key, ''));
            Footer::updateOrCreate(
                ['key_name' => $key],
                [
                    'name' => $meta['name'],
                    'value' => $value,
                    'status' => 1,
                ]
            );
        }

        $phone = trim((string) $request->input('phone', ''));
        if ($phone !== '') {
            Footer::updateOrCreate(
                ['key_name' => 'contact_us'],
                [
                    'name' => 'Contact Us',
                    'value' => $phone,
                    'status' => 1,
                ]
            );
        }

        WebsiteSettings::flush();
        toastr()->success('Website settings saved successfully.');
        return redirect()->route('admin.footer');
    }


    public function add(){
        $data = [];
        return view('admin.footer.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Footer::where('email',$request->email)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['email'] = $request->email;
            $insertData['phone_number'] = $request->phone_number;
            $insertData['college_name'] = $request->college_name;
            $insertData['location'] = $request->location;
            $insertData['access_token'] = md5(time().$request->email);
            $insertData['hash_code'] = md5(time().$request->email);
            $insertData['password'] = md5($request->password);
            $insertData['status'] = $request->status;

            Footer::create($insertData);
        
            toastr()->success('Footer added successfully.');
            return redirect()->route('admin.footer');
        }else{
            toastr()->warning('Footer already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Footer::where('id',$request->id)->first();
        return view('admin.footer.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        
        // dd($input);
        $loginCheck = Footer::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['value'] = $request->value;
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            WebsiteSettings::flush();
            toastr()->success('Footer updated successfully.');
            return redirect()->route('admin.footer');
        }else{
            toastr()->warning('Footer already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Footer::where('id',$id)->delete();
        WebsiteSettings::flush();
        toastr()->success('Footer deleted successfully.');
        return redirect()->route('admin.footer');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Footer::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        WebsiteSettings::flush();
        toastr()->success('Footer status change successfully.');
        return redirect()->route('admin.footer');
    }
    
}
