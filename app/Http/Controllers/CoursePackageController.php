<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\CoursePackage;
use App\Models\CoursePackageMode;
use App\Models\CoursePackageOrder;
use App\Models\CoursePackageOrderChapter;
use App\Models\CourseSchedule;
use App\Models\Location;
use App\Services\IciciGateway;
use App\Support\CoursePackageAccess;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $chapterGroups = [];
        $chapterMasterLegacy = false;
        $chapterUnitPrice = 0;
        $chapterUnownedCount = 0;
        $preselectChapterId = (int) $request->query('chapter');

        if ($package->code === 'chapter_master') {
            $chapterUnitPrice = (float) ($package->lowestModePrice() ?? 0);
            $chapterMasterLegacy = Auth::check() && CoursePackageAccess::chapterMasterLegacyFull();
            if (!$chapterMasterLegacy) {
                $ownedIds = Auth::check() ? CoursePackageAccess::ownedChapterIds() : [];
                $chapterGroups = CoursePackageAccess::chapterCatalog($ownedIds, true);
                foreach ($chapterGroups as $group) {
                    foreach ($group['chapters'] as $chapter) {
                        if (!$chapter['owned']) {
                            $chapterUnownedCount++;
                        }
                    }
                }
            }
        }

        return view('site.course_packages.show', compact(
            'package',
            'schedules',
            'locations',
            'preselectMode',
            'chapterGroups',
            'chapterMasterLegacy',
            'chapterUnitPrice',
            'chapterUnownedCount',
            'preselectChapterId'
        ));
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

        $lineChapters = collect();
        $unitPrice = (float) $mode->price;
        if ($package->code === 'chapter_master') {
            $picked = $this->chapterMasterSelection($request);
            if (isset($picked['error'])) {
                return redirect()->route('course_packages.show', $package->slug)
                    ->with('error', $picked['error']);
            }
            $lineChapters = $picked['chapters'];
            session(['cm_checkout_chapter_ids' => $lineChapters->pluck('id')->map(fn ($id) => (int) $id)->all()]);
        }

        return view('site.course_packages.checkout', compact(
            'package',
            'mode',
            'locations',
            'lineChapters',
            'unitPrice'
        ));
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

        $lineChapters = collect();
        $unitPrice = (float) $mode->price;
        if ($package->code === 'chapter_master') {
            $picked = $this->chapterMasterSelection($request);
            if (isset($picked['error'])) {
                return response()->json(['success' => 0, 'message' => $picked['error']], 422);
            }
            $lineChapters = $picked['chapters'];
        }

        $amount = $package->code === 'chapter_master'
            ? $unitPrice * $lineChapters->count()
            : $unitPrice;
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

        $paymentStatus = $final <= 0 ? 'FREE' : 'PENDING';

        $modeLabel = $mode->label;
        if ($package->code === 'chapter_master') {
            $count = $lineChapters->count();
            $modeLabel .= ' · '.$count.' chapter'.($count === 1 ? '' : 's');
        }

        try {
            $order = DB::transaction(function () use (
                $request, $package, $mode, $modeLabel, $amount, $discount, $couponDiscount,
                $tax, $final, $fulfillment, $paymentStatus, $lineChapters, $unitPrice
            ) {
                $order = CoursePackageOrder::create([
                    'user_id' => Auth::id(),
                    'course_package_id' => $package->id,
                    'course_package_mode_id' => $mode->id,
                    'mode_code' => $mode->mode_code,
                    'mode_label' => $modeLabel,
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
                    'fulfillment_status' => $final <= 0 ? $fulfillment : 'pending',
                    'payment_status' => $paymentStatus,
                    'gateway_meta' => [
                        'fulfillment' => $fulfillment,
                        'chapter_ids' => $lineChapters->pluck('id')->map(fn ($id) => (int) $id)->values()->all(),
                        'unit_price' => $unitPrice,
                    ],
                ]);

                if ($final <= 0) {
                    foreach ($lineChapters as $chapter) {
                        CoursePackageOrderChapter::create([
                            'course_package_order_id' => $order->id,
                            'user_id' => Auth::id(),
                            'chapter_id' => $chapter->id,
                            'price' => $unitPrice,
                        ]);
                    }
                }

                return $order;
            });
            session()->forget('cm_checkout_chapter_ids');
        } catch (QueryException $e) {
            if ($package->code === 'chapter_master') {
                return response()->json([
                    'success' => 0,
                    'message' => 'One of these chapters is already unlocked on your account.',
                ], 422);
            }
            throw $e;
        }

        if ($final > 0) {
            $gateway = app(IciciGateway::class);
            $txn = $gateway->newTxnNo();
            $order->gateway_order_id = $txn;
            $order->save();
            try {
                $bankUrl = $gateway->startSale($txn, $final, Auth::user());
            } catch (\Throwable $e) {
                $order->payment_status = 'FAILED';
                $order->save();

                return response()->json(['success' => 0, 'message' => $e->getMessage()], 422);
            }

            return response()->json([
                'success' => 1,
                'message' => 'Redirecting to ICICI Bank.',
                'order_id' => $order->id,
                'redirect' => $bankUrl,
            ]);
        }

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
        $orders = CoursePackageOrder::with(['package', 'mode', 'location', 'orderChapters.chapter.subject'])
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

    /**
     * Chapters selected on the Rank Chapter Master cart.
     * Price is the mode price × count, capped at CHAPTER_PICK_LIMIT.
     *
     * @return array{chapters?: \Illuminate\Support\Collection, error?: string}
     */
    private function chapterMasterSelection(Request $request): array
    {
        $raw = $request->input('chapter_ids', []);
        if (!is_array($raw) || $raw === []) {
            $csv = (string) $request->input('chapter_ids_csv', '');
            $raw = $csv !== '' ? preg_split('/\s*,\s*/', $csv) : [];
        }
        if ((!is_array($raw) || $raw === []) && $request->hasSession()) {
            $raw = (array) $request->session()->get('cm_checkout_chapter_ids', []);
        }

        $ids = array_values(array_unique(array_filter(array_map('intval', $raw))));
        $limit = CoursePackageAccess::CHAPTER_PICK_LIMIT;

        if (count($ids) < 1) {
            return ['error' => 'Select at least one chapter.'];
        }
        if (count($ids) > $limit) {
            return ['error' => 'You can select up to '.$limit.' chapters in one payment.'];
        }

        if (Auth::check() && CoursePackageAccess::chapterMasterLegacyFull()) {
            return ['error' => 'You already have access to every chapter.'];
        }

        $chapters = Chapter::query()
            ->with('subject')
            ->whereIn('id', $ids)
            ->where('status', 1)
            ->whereHas('subject', function ($q) {
                $q->where('status', 1)->where(function ($inner) {
                    $inner->where('is_deleted', 0)->orWhereNull('is_deleted');
                });
            })
            ->get();

        if ($chapters->count() !== count($ids)) {
            return ['error' => 'One of the selected chapters is not available.'];
        }

        if (Auth::check()) {
            $owned = array_flip(CoursePackageAccess::ownedChapterIds());
            $already = $chapters->filter(fn ($chapter) => isset($owned[(int) $chapter->id]));
            if ($already->isNotEmpty()) {
                return ['error' => 'You already own '.$already->pluck('name')->join(', ').'.'];
            }
        }

        $chapters->each(function (Chapter $chapter) {
            $name = $chapter->name;
            $level = (int) ($chapter->class_level ?? 0);
            if ($level === 11) {
                $name .= ' (Class 11)';
            } elseif ($level === 12) {
                $name .= ' (Class 12)';
            }
            $chapter->setAttribute('cart_label', trim(($chapter->subject->name ?? 'Subject').' · '.$name));
        });

        return ['chapters' => $chapters];
    }
}
