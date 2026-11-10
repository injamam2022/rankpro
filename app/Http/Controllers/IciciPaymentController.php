<?php

namespace App\Http\Controllers;

use App\Models\CoursePackageOrder;
use App\Models\CoursePackageOrderChapter;
use App\Models\Payment;
use App\Services\IciciGateway;
use App\Support\CoursePackageAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IciciPaymentController extends Controller
{
    public function returned(Request $request)
    {
        $txn = $this->txn($request->input('merchantTxnNo'));
        try {
            $this->settle($txn);
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->view('site.payments.icici_handoff', [
            'url' => route('icici.result', ['txn' => $txn !== '' ? $txn : 'none']),
        ]);
    }

    public function advice(Request $request)
    {
        $txn = $this->txn($request->input('merchantTxnNo'));
        try {
            $result = $this->settle($txn);
        } catch (\Throwable $e) {
            report($e);

            return response('ERROR', 500);
        }

        if (($result['retry'] ?? false) === true) {
            return response('RETRY', 503);
        }

        return response('OK', 200);
    }

    public function result(string $txn)
    {
        $txn = $this->txn($txn);
        if ($txn !== '' && $txn !== 'none') {
            try {
                $this->settle($txn);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $order = $txn !== '' ? CoursePackageOrder::with('package')->where('gateway_order_id', $txn)->first() : null;
        $payment = (!$order && $txn !== '') ? Payment::where('order_id', $txn)->first() : null;

        $record = $order ?: $payment;
        $owned = $record && Auth::check() && (int) Auth::id() === (int) $record->user_id;
        $status = $record->payment_status ?? 'FAILED';

        if ($status === 'SUCCESS' || $status === 'FREE') {
            $state = 'success';
            $message = 'Payment received. Your purchase is active.';
        } elseif ($status === 'PENDING') {
            $state = 'pending';
            $message = 'ICICI has not confirmed this payment yet. If money was deducted, it will show in My Orders once the bank confirms it.';
        } else {
            $state = 'failed';
            $message = 'Payment was not completed. You can try again from the package page.';
        }

        if (!$record) {
            $state = 'failed';
            $message = 'We could not find this payment.';
        }

        $continue = route('course_packages');
        $continueLabel = 'Back to packages';
        if ($state === 'success' && $owned && $order) {
            $continue = $this->courseContinue($order);
            $continueLabel = 'Continue';
        } elseif ($state === 'success' && $owned) {
            $continue = route('dashboard');
            $continueLabel = 'Continue';
        } elseif ($state === 'success') {
            $continue = route('my_orders');
            $continueLabel = 'Continue';
        } elseif (!Auth::check()) {
            $continue = route('login');
            $continueLabel = 'Log in';
        }

        return view('site.payments.icici_result', [
            'state' => $state,
            'message' => $message,
            'reference' => $owned ? ($order->order_number ?? $txn) : '',
            'amount' => $owned ? (float) ($order->final_amount ?? $payment->amount ?? 0) : null,
            'continue' => $continue,
            'continueLabel' => $continueLabel,
        ]);
    }

    /**
     * @return array{state: string, retry?: bool}
     */
    public function settle(string $merchantTxnNo): array
    {
        $merchantTxnNo = $this->txn($merchantTxnNo);
        if ($merchantTxnNo === '') {
            return ['state' => 'failed'];
        }

        $order = CoursePackageOrder::where('gateway_order_id', $merchantTxnNo)->first();
        if ($order) {
            return $this->settleCourseOrder($order);
        }

        $payment = Payment::where('order_id', $merchantTxnNo)->first();
        if ($payment) {
            return $this->settleLegacyPayment($payment);
        }

        return ['state' => 'failed'];
    }

    private function settleCourseOrder(CoursePackageOrder $order): array
    {
        if ($order->payment_status === 'SUCCESS') {
            return ['state' => 'success'];
        }

        $status = $this->bankStatus($order->gateway_order_id, (float) $order->final_amount);
        if ($status['state'] === 'pending') {
            return $status;
        }

        return DB::transaction(function () use ($order, $status) {
            $order = CoursePackageOrder::where('id', $order->id)->lockForUpdate()->first();
            if ($order->payment_status === 'SUCCESS') {
                return ['state' => 'success'];
            }

            $meta = is_array($order->gateway_meta) ? $order->gateway_meta : [];
            $meta['bank'] = $status['bank'];

            if ($status['state'] === 'success') {
                $order->payment_status = 'SUCCESS';
                $order->transaction_id = $status['txn_id'];
                $order->fulfillment_status = $meta['fulfillment'] ?? ($order->fulfillment_status ?: 'access_granted');
                $order->gateway_meta = $meta;
                $order->save();
                $this->attachChapters($order, $meta);

                return ['state' => 'success'];
            }

            $order->payment_status = 'FAILED';
            $order->gateway_meta = $meta;
            $order->save();

            return ['state' => 'failed'];
        });
    }

    private function settleLegacyPayment(Payment $payment): array
    {
        if ($payment->payment_status === 'SUCCESS') {
            return ['state' => 'success'];
        }

        $status = $this->bankStatus($payment->order_id, (float) $payment->amount);
        if ($status['state'] === 'pending') {
            return $status;
        }

        return DB::transaction(function () use ($payment, $status) {
            $payment = Payment::where('id', $payment->id)->lockForUpdate()->first();
            if ($payment->payment_status === 'SUCCESS') {
                return ['state' => 'success'];
            }

            $payment->payment_status = $status['state'] === 'success' ? 'SUCCESS' : 'FAILED';
            if ($status['state'] === 'success') {
                $payment->transaction_id = $status['txn_id'];
            }
            $payment->save();

            return ['state' => $payment->payment_status === 'SUCCESS' ? 'success' : 'failed'];
        });
    }

    /**
     * @return array{state: string, retry?: bool, txn_id?: ?string, bank?: array}
     */
    private function bankStatus(string $merchantTxnNo, float $expectedAmount): array
    {
        try {
            $status = app(IciciGateway::class)->status($merchantTxnNo);
        } catch (\Throwable $e) {
            Log::warning('ICICI status check failed', ['merchantTxnNo' => $merchantTxnNo, 'error' => $e->getMessage()]);

            return ['state' => 'pending', 'retry' => true];
        }

        $bank = [
            'txnStatus' => $status['txnStatus'] ?? null,
            'txnResponseCode' => $status['txnResponseCode'] ?? null,
            'responseCode' => $status['responseCode'] ?? null,
            'respDescription' => $status['respDescription'] ?? $status['txnRespDescription'] ?? null,
            'paymentMode' => $status['paymentMode'] ?? null,
            'txnID' => $status['txnID'] ?? null,
            'paymentID' => $status['paymentID'] ?? null,
            'amount' => $status['amount'] ?? null,
        ];

        $gateway = app(IciciGateway::class);
        $amountOk = isset($status['amount']) && abs((float) $status['amount'] - $expectedAmount) < 0.01;
        $returnedMerchant = (string) ($status['merchantId'] ?? '');
        $merchantOk = $returnedMerchant === '' || $returnedMerchant === (string) config('services.icici.merchant_id');

        if ($gateway->isPaid($status) && $amountOk && $merchantOk) {
            return [
                'state' => 'success',
                'txn_id' => $status['txnID'] ?? $status['paymentID'] ?? null,
                'bank' => $bank,
            ];
        }

        if ($gateway->isFailed($status) || ($amountOk === false && $gateway->isPaid($status))) {
            return ['state' => 'failed', 'bank' => $bank];
        }

        return ['state' => 'pending', 'bank' => $bank];
    }

    private function attachChapters(CoursePackageOrder $order, array $meta): void
    {
        $ids = array_values(array_unique(array_map('intval', (array) ($meta['chapter_ids'] ?? []))));
        $price = (float) ($meta['unit_price'] ?? 0);
        foreach ($ids as $chapterId) {
            if ($chapterId < 1) {
                continue;
            }
            $exists = CoursePackageOrderChapter::where('user_id', $order->user_id)
                ->where('chapter_id', $chapterId)
                ->exists();
            if ($exists) {
                continue;
            }
            CoursePackageOrderChapter::create([
                'course_package_order_id' => $order->id,
                'user_id' => $order->user_id,
                'chapter_id' => $chapterId,
                'price' => $price,
            ]);
        }
    }

    private function courseContinue(CoursePackageOrder $order): string
    {
        $package = $order->package;
        if (!$package) {
            return route('my_orders');
        }
        if ($package->code === 'chapter_master') {
            return route('chapter_master');
        }
        if (in_array($package->code, CoursePackageAccess::seriesPackageCodes(), true)) {
            return route('package_series.show', $package->slug);
        }
        if ($package->code === 'free_custom') {
            return route('custom_test');
        }

        return route('my_orders');
    }

    private function txn($value): string
    {
        $txn = preg_replace('/[^A-Za-z0-9]/', '', (string) $value);

        return substr($txn, 0, 20);
    }
}
