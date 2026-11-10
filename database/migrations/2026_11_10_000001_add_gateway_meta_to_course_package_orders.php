<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_package_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('course_package_orders', 'gateway_meta')) {
                $table->text('gateway_meta')->nullable()->after('gateway_order_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('course_package_orders', function (Blueprint $table) {
            if (Schema::hasColumn('course_package_orders', 'gateway_meta')) {
                $table->dropColumn('gateway_meta');
            }
        });
    }
};
