<?php

namespace App\Http\Controllers;

use App\Models\CoursePackage;
use App\Models\CoursePackageMode;
use App\Models\CoursePackageOrder;
use App\Models\CourseSchedule;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CoursePackageController extends Controller
{
    public function index()
    {
        $packages = CoursePackage::active()
            ->with(['features', 'activeModes'])
            ->orderBy('sort_order')
            ->get();

        return view('site.course_packages.index', compact('packages'));
    }

    public function show(Request $request, $slug)
    {
        $package = CoursePackage::active()
            ->with(['features', 'activeModes'])
            ->where('slug', $slug)
            ->firstOrFail();

        $schedules = $this->schedulesForPackage($package);
        $locations = Location::where('status', 1)->where('is_deleted', 0)->orderBy('location_name')->get();
        $preselectMode = $request->query('mode'); // online|centre|delivery|bundle|home→delivery

        if ($preselectMode === 'home') {
            $preselectMode = 'delivery';
        }

        return view('site.course_packages.show', compact('package', 'schedules', 'locations', 'preselectMode'));
    }

    public function checkout(Request $request, $slug)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $package = CoursePackage::active()
            ->with('activeModes')
            ->where('slug', $slug)
            ->firstOrFail();

        if ($package->is_free) {
            return $this->activateFreePackage($package);
        }

        $mode = $this->resolveMode($package, $request->input('mode_id'));
        if (!$mode) {
            return redirect()->route('course_packages.show', $package->slug)
                ->with('error', 'Please select a valid fulfilment option.');
        }

        $locations = Location::where('status', 1)->where('is_deleted', 0)->orderBy('location_name')->get();

        return view('site.course_packages.checkout', compact('package', 'mode', 'locations'));
    }

    public function placeOrder(Request $request, $slug)
    {
        if (!Auth::check()) {
            return response()->json(['success' => 0, 'message' => 'Please login.'], 401);
        }

        $package = CoursePackage::active()->with('activeModes')->where('slug', $slug)->firstOrFail();
        $mode = $this->resolveMode($package, $request->input('mode_id'));

        if (!$mode) {
            return response()->json(['success' => 0, 'message' => 'Invalid mode selected.'], 422);
        }

        $rules = [];
        if ($mode->requires_location) {
            $rules['location_id'] = 'required|exists:locations,id';
        }
        if ($mode->requires_address) {
            $rules['shipping_name'] = 'required|string|max:120';
            $rules['shipping_phone'] = 'required|string|max:20';
            $rules['shipping_address'] = 'required|string|max:500';
            $rules['shipping_city'] = 'required|string|max:100';
            $rules['shipping_state'] = 'required|string|max:100';
            $rules['shipping_pincode'] = 'required|string|max:12';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $amount = (float) $mode->price;
        $tax = (float) ($package->tax ?? 0);
        $couponDiscount = (float) ($request->coupon_discount ?? 0);
        $discount = (float) ($request->total_discount ?? 0);
        $final = max(0, $amount + $tax - $discount);

        $fulfillment = 'access_granted';
        if ($mode->mode_code === 'centre') {
            $fulfillment = 'centre_registered';
        } elseif ($mode->mode_code === 'delivery') {
            $fulfillment = 'pending';
        } elseif ($mode->mode_code === 'bundle') {
            $fulfillment = 'access_granted';
        }

        $paymentStatus = $final <= 0 ? 'FREE' : 'SUCCESS';
        if ($final > 0 && empty($request->razorpay_payment_id)) {
            return response()->json(['success' => 0, 'message' => 'Payment details missing.'], 422);
        }

        $order = CoursePackageOrder::create([
            'user_id' => Auth::id(),
            'course_package_id' => $package->id,
            'course_package_mode_id' => $mode->id,
            'mode_code' => $mode->mode_code,
            'mode_label' => $mode->label,
            'amount' => $amount,
            'discount' => $discount,
            'coupon_discount' => $couponDiscount,
            'coupon_code' => $request->coupon_code,
            'tax' => $tax,
            'final_amount' => $final,
            'location_id' => $request->location_id,
            'shipping_name' => $request->shipping_name,
            'shipping_phone' => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_state' => $request->shipping_state,
            'shipping_pincode' => $request->shipping_pincode,
            'fulfillment_status' => $fulfillment,
            'payment_status' => $paymentStatus,
            'transaction_id' => $request->razorpay_payment_id,
            'gateway_order_id' => $request->razorpay_order_id,
        ]);

        $redirect = route('my_orders');
        if ($package->code === 'chapter_master') {
            $redirect = route('chapter_master');
        } elseif (in_array($package->code, \App\Support\CoursePackageAccess::seriesPackageCodes(), true)) {
            $redirect = route('package_series.show', $package->slug);
        } elseif ($package->code === 'free_custom') {
            $redirect = route('custom_test');
        }

        return response()->json([
            'success' => 1,
            'message' => 'Order placed successfully.',
            'order_id' => $order->id,
            'redirect' => $redirect,
        ]);
    }

    public function myOrders()
    {
        $orders = CoursePackageOrder::with(['package', 'location'])
            ->where('user_id', Auth::id())
            ->orderByDesc('id')
            ->get();

        return view('site.course_packages.orders', compact('orders'));
    }

    private function activateFreePackage(CoursePackage $package)
    {
        $mode = $package->activeModes()->first();

        $exists = CoursePackageOrder::where('user_id', Auth::id())
            ->where('course_package_id', $package->id)
            ->whereIn('payment_status', ['SUCCESS', 'FREE'])
            ->exists();

        if (!$exists && $mode) {
            CoursePackageOrder::create([
                'user_id' => Auth::id(),
                'course_package_id' => $package->id,
                'course_package_mode_id' => $mode->id,
                'mode_code' => $mode->mode_code,
                'mode_label' => $mode->label,
                'amount' => 0,
                'final_amount' => 0,
                'fulfillment_status' => 'access_granted',
                'payment_status' => 'FREE',
            ]);
        }

        return redirect()->route('custom_test')
            ->with('success', 'Free Custom Test Builder is ready. Start building your test.');
    }

    private function resolveMode(CoursePackage $package, $modeId): ?CoursePackageMode
    {
        if ($modeId) {
            return $package->activeModes()->where('id', $modeId)->first();
        }

        if ($package->mode_selection === 'fixed') {
            return $package->activeModes()->first();
        }

        return null;
    }

    private function schedulesForPackage(CoursePackage $package)
    {
        $scope = $package->schedule_scope;
        if (!$scope || $scope === 'none' || $scope === 'chapter') {
            return collect();
        }

        $query = CourseSchedule::active()->orderBy('test_date')->orderBy('seq');

        if ($scope === 'part') {
            $query->where('section', 'part');
        } elseif ($scope === 'full') {
            $query->where('section', 'full');
        }

        return $query->get();
    }
}
