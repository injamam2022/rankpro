<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\AdminUserRole;
use App\Models\AdminUserAccess;
use App\Models\AdminUserRoleAccess;

class Admin_user_roleController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = AdminUserRole::get();
        return view('admin.admin_user_role.list',$data);
    }


    public function add(){
        $data = [];
        $data['list'] = AdminUserAccess::where("status",1)->where("parent_id",0)->orderBy('order', 'asc')->get();
        foreach ($data['list'] as $key => $value) {
            $data['list'][$key]->list = AdminUserAccess::where("status",1)->where("parent_id",$value->id)->get();
        }
        return view('admin.admin_user_role.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();
        // dd($input);
        $loginCheck = AdminUserRole::where('name',$request->name)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name ?? '';
            $insertData['description'] = $request->description ?? '';
            $insertData['status'] = $request->status;

            $res = AdminUserRole::create($insertData);

            if ($res){

                foreach($request->permission as $val){
                    $parentData = [
                        'admin_user_role_id' => $res->id,
                        'admin_user_access_id' => $val,
                        'status' => 1
                    ];
                    AdminUserRoleAccess::create($parentData);
                }

                $list = AdminUserAccess::where("status",1)->where("parent_id", '!=', 0)->get();

                foreach($list as $val){
                    $subData = [];
                    $subData = [
                        'admin_user_role_id' => $res->id,
                        'admin_user_access_id' => $val->id,
                        'parent_id' => $val->parent_id,
                        'is_add' => isset($input['permission_add'][$val->id])?1:0,
                        'is_edit' => isset($input['permission_edit'][$val->id])?1:0,
                        'is_list' => isset($input['permission_list'][$val->id])?1:0,
                        'is_delete' => isset($input['permission_delete'][$val->id])?1:0,
                        'is_export' => isset($input['permission_export'][$val->id])?1:0,
                        'status' => 1
                    ];
                    AdminUserRoleAccess::create($subData);
                }
            }

            toastr()->success('Admin User Role successfully saved.');
            return redirect()->route('admin.admin_user_role');
        }else{
            toastr()->warning('Admin user role already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request)
    {
        $data = [];

        $data['role'] = AdminUserRole::findOrFail($request->id);
        $data['list'] = AdminUserAccess::where("status", 1)->where("parent_id", 0)->orderBy('order', 'asc')->get();

        foreach ($data['list'] as $key => $value) {
            $data['list'][$key]->list = AdminUserAccess::where("status", 1)->where("parent_id", $value->id)->get();
        }

        $data['permissions'] = AdminUserRoleAccess::where('admin_user_role_id', $request->id)->get()->keyBy('admin_user_access_id');

        return view('admin.admin_user_role.edit', $data);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();
        $role = AdminUserRole::findOrFail($request->id);

        $role->update([
            'name' => $request->name ?? '',
            'description' => $request->description ?? '',
            'status' => $request->status
        ]);

        AdminUserRoleAccess::where('admin_user_role_id', $request->id)->delete();

        if (!empty($request->permission)) {
            foreach ($request->permission as $val) {
                AdminUserRoleAccess::create([
                    'admin_user_role_id' => $request->id,
                    'admin_user_access_id' => $val,
                    'status' => 1
                ]);
            }
        }

        $list = AdminUserAccess::where("status", 1)->where("parent_id", '!=', 0)->get();
        foreach ($list as $val) {
            AdminUserRoleAccess::create([
                'admin_user_role_id' => $request->id,
                'admin_user_access_id' => $val->id,
                'parent_id' => $val->parent_id,
                'is_add' => isset($input['permission_add'][$val->id]) ? 1 : 0,
                'is_edit' => isset($input['permission_edit'][$val->id]) ? 1 : 0,
                'is_list' => isset($input['permission_list'][$val->id]) ? 1 : 0,
                'is_delete' => isset($input['permission_delete'][$val->id]) ? 1 : 0,
                'is_export' => isset($input['permission_export'][$val->id]) ? 1 : 0,
                'status' => 1
            ]);
        }

        toastr()->success('Admin User Role successfully updated.');
        return redirect()->route('admin.admin_user_role');
    }

    // public function edit(Request $request){
    //     $input = $request->all();
    //     $data = [];
    //     $data['details'] = AdminUserRole::where('id',$request->id)->first();
    //     return view('admin.admin_user_role.edit',$data);
    // }

    // public function update(Request $request){
    //     $validatedData = $request->validate([
    //                         'name' => 'required'
    //                     ]);

    //     $input = $request->all();
    //     $loginCheck = AdminUserRole::where('id',$request->id)->first();

    //     if ($loginCheck){
    //         $insertData = [];
    //         $insertData['name'] = $request->name ?? '';
    //         $insertData['email'] = $request->email ?? '';
    //         $insertData['phone_number'] = $request->phone_number ?? '';
    //         $insertData['college_name'] = $request->college_name ?? '';
    //         $insertData['about'] = $request->about ?? '';
    //         $insertData['description'] = $request->description ?? '';
    //         $insertData['mentorship'] = $request->mentorship ?? '';
    //         $insertData['test_series'] = $request->test_series ?? '';
    //         $insertData['location'] = $request->location ?? '';
    //         if($request->password){
    //             $insertData['password'] = md5($request->password);
    //         }

    //         $insertData['status'] = $request->status;

    //         $loginCheck->update($insertData);
    //         toastr()->success('Admin user role updated successfully.');
    //         return redirect()->route('admin.admin_user_role');
    //     }else{
    //         toastr()->warning('Admin user role already exist');
    //         return back()->withInput();
    //     }
    // }

    public function delete(Request $request){
        $id = $request->id;
        AdminUserRole::where('id',$id)->delete();
        toastr()->success('Admin user role deleted successfully.');
        return redirect()->route('admin.admin_user_role');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = AdminUserRole::where('id',$request->id)->first();
        if($loginCheck){
            if($loginCheck->status == 1){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }
        // dd($loginCheck);

        toastr()->success('Admin user role status change successfully.');
        return redirect()->route('admin.admin_user_role');
    }

}
