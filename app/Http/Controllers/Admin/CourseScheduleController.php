<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseSchedule;
use Illuminate\Http\Request;

class CourseScheduleController extends Controller
{
    public function list()
    {
        $data['list'] = CourseSchedule::orderBy('section')->orderBy('test_date')->orderBy('seq')->get();
        return view('admin.course_package.schedule_list', $data);
    }

    public function change(Request $request)
    {
        $row = CourseSchedule::findOrFail($request->id);
        $row->update(['status' => $row->status ? 0 : 1]);
        toastr()->success('Schedule status updated.');
        return redirect()->route('admin.course_schedule');
    }
}
