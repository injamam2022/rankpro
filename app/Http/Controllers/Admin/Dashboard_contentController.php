<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\Dashboard_content;
use App\Models\Language;

class Dashboard_contentController extends Controller
{
    public function list(){
        $data = [];
        $data['details'] = Dashboard_content::first();
        return view('admin.cms.dashboard_content.edit',$data);
    }

    public function update(Request $request)
    {
        
        $banner = Dashboard_content::findOrFail($request->id);
        $updateData = [];

        $updateData['board'] = $request->board;
        $updateData['leader_board'] = $request->leader_board;
        $banner->update($updateData);

        toastr()->success('Dashboard Content updated successfully.');
        return redirect()->route('admin.dashboard_content');
    }

}
