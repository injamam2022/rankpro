<?php

namespace App\Http\Controllers\Counsellor;

use App\Http\Controllers\Controller;
use App\Models\CoursePackageOrder;
use App\Models\CoursePackageOrderChapter;
use App\Models\Payment;
use Illuminate\Http\Request;

class CounsellorPaymentController extends Controller
{
    public function ranker()
    {
        $data = [];
        $data['list'] = Payment::select([
                'payments.*',
                'users.first_name',
                'users.last_name',
                'users.email_id',
                'rankers.name as ranker_name',
                'ranker_prices.title',
            ])
            ->leftJoin('users', 'users.id', '=', 'payments.user_id')
            ->leftJoin('ranker_prices', 'ranker_prices.id', '=', 'payments.ranker_id')
            ->leftJoin('rankers', 'rankers.id', '=', 'ranker_prices.ranker_id')
            ->where('payments.type', 1)
            ->orderByDesc('payments.id')
            ->get();

        return view('counsellor.payment.ranker', $data);
    }

    public function rankerDetail(Request $request)
    {
        $data = [];
        $data['details'] = Payment::select([
                'payments.*',
                'users.first_name',
                'users.last_name',
                'users.email_id',
                'rankers.name as ranker_name',
                'ranker_prices.title',
            ])
            ->leftJoin('users', 'users.id', '=', 'payments.user_id')
            ->leftJoin('ranker_prices', 'ranker_prices.id', '=', 'payments.ranker_id')
            ->leftJoin('rankers', 'rankers.id', '=', 'ranker_prices.ranker_id')
            ->where('payments.type', 1)
            ->where('payments.id', $request->id)
            ->firstOrFail();

        return view('counsellor.payment.ranker_detail', $data);
    }

    public function coursePackageOrders()
    {
        $data['list'] = CoursePackageOrder::with(['user', 'package', 'location', 'orderChapters.chapter.subject'])
            ->orderByDesc('id')
            ->get();

        return view('counsellor.payment.orders', $data);
    }

    public function coursePackageOrderDetail(Request $request)
    {
        $data['details'] = CoursePackageOrder::with(['user', 'package', 'mode', 'location', 'orderChapters.chapter.subject'])
            ->findOrFail($request->id);

        return view('counsellor.payment.order_detail', $data);
    }

    public function chapterPurchases()
    {
        $data['rows'] = CoursePackageOrderChapter::query()
            ->with(['order.user', 'order.package', 'chapter.subject'])
            ->whereHas('order')
            ->orderByDesc('id')
            ->get();

        return view('counsellor.payment.chapter_purchases', $data);
    }
}
