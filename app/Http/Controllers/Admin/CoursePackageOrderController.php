<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoursePackageOrder;
use App\Models\CoursePackageOrderChapter;
use Illuminate\Http\Request;

class CoursePackageOrderController extends Controller
{
    public function list()
    {
        $data['list'] = CoursePackageOrder::with(['user', 'package', 'location', 'orderChapters.chapter.subject'])
            ->orderByDesc('id')
            ->get();

        return view('admin.course_package.orders', $data);
    }

    public function chapterPurchases()
    {
        $data['rows'] = CoursePackageOrderChapter::query()
            ->with(['order.user', 'order.package', 'chapter.subject'])
            ->whereHas('order')
            ->orderByDesc('id')
            ->get();

        return view('admin.course_package.chapter_purchases', $data);
    }

    public function detail(Request $request)
    {
        $data['details'] = CoursePackageOrder::with(['user', 'package', 'mode', 'location', 'orderChapters.chapter.subject'])
            ->findOrFail($request->id);

        return view('admin.course_package.order_detail', $data);
    }

    public function updateFulfillment(Request $request)
    {
        $order = CoursePackageOrder::findOrFail($request->id);
        $request->validate([
            'fulfillment_status' => 'required|string|max:40',
        ]);

        $order->update([
            'fulfillment_status' => $request->fulfillment_status,
            'admin_notes' => $request->admin_notes,
        ]);

        toastr()->success('Order fulfilment updated.');
        return redirect()->route('admin.course_package.order_detail', ['id' => $order->id]);
    }
}
