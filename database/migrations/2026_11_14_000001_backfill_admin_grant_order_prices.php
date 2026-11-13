<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $orders = DB::table('course_package_orders')
            ->where('transaction_id', 'ADMIN-GRANT')
            ->where(function ($q) {
                $q->whereNull('final_amount')->orWhere('final_amount', '<=', 0);
            })
            ->get(['id', 'course_package_id', 'course_package_mode_id']);

        foreach ($orders as $order) {
            $modePrice = null;
            if ($order->course_package_mode_id) {
                $modePrice = DB::table('course_package_modes')
                    ->where('id', $order->course_package_mode_id)
                    ->value('price');
            }
            if ($modePrice === null) {
                $modePrice = DB::table('course_package_modes')
                    ->where('course_package_id', $order->course_package_id)
                    ->where('status', 1)
                    ->orderBy('sort_order')
                    ->value('price');
            }

            $package = DB::table('course_packages')->where('id', $order->course_package_id)->first(['tax', 'display_price']);
            $amount = (float) ($modePrice ?? $package->display_price ?? 0);
            $tax = (float) ($package->tax ?? 0);
            $final = max(0, $amount + $tax);

            DB::table('course_package_orders')->where('id', $order->id)->update([
                'amount' => $amount,
                'tax' => $tax,
                'final_amount' => $final,
                'gateway_meta' => json_encode([
                    'source' => 'admin_assigned',
                    'listed_price' => $final,
                    'backfilled' => true,
                ]),
            ]);
        }
    }

    public function down(): void
    {
        // Intentionally left blank — do not zero prices again.
    }
};
