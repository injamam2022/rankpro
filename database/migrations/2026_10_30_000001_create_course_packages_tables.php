<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_packages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('theme')->default('blue');
            $table->decimal('display_price', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->default(0);
            $table->boolean('is_free')->default(false);
            $table->boolean('includes_online')->default(false);
            $table->boolean('includes_omr')->default(false);
            /** fixed = one mode auto; choose_one = student picks among modes */
            $table->string('mode_selection')->default('fixed');
            $table->string('schedule_scope')->nullable(); // part|full|both|chapter|none
            $table->unsignedInteger('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('course_package_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_package_id')->constrained('course_packages')->cascadeOnDelete();
            $table->string('text');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('course_package_modes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_package_id')->constrained('course_packages')->cascadeOnDelete();
            /** online | centre | delivery | bundle */
            $table->string('mode_code');
            $table->string('label');
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('requires_location')->default(false);
            $table->boolean('requires_address')->default(false);
            $table->boolean('includes_online')->default(false);
            $table->boolean('includes_omr')->default(false);
            $table->text('help_text')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('course_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('section'); // part|full
            $table->unsignedInteger('seq')->default(0);
            $table->date('test_date')->nullable();
            $table->string('test_type')->nullable();
            $table->string('timing')->nullable();
            $table->json('subjects')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('course_package_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            // users.id is INT(11) in this project — avoid foreignId()/bigint mismatch
            $table->integer('user_id');
            $table->unsignedBigInteger('course_package_id');
            $table->unsignedBigInteger('course_package_mode_id')->nullable();
            $table->string('mode_code')->nullable();
            $table->string('mode_label')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('coupon_discount', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2)->default(0);
            $table->unsignedBigInteger('location_id')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_pincode')->nullable();
            $table->string('fulfillment_status')->default('pending'); // pending|confirmed|packed|shipped|delivered|centre_registered|access_granted
            $table->string('payment_status')->default('PENDING'); // PENDING|SUCCESS|FREE|FAILED
            $table->string('transaction_id')->nullable();
            $table->string('gateway_order_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'payment_status']);
            $table->index('fulfillment_status');
            $table->index('course_package_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_package_orders');
        Schema::dropIfExists('course_schedules');
        Schema::dropIfExists('course_package_modes');
        Schema::dropIfExists('course_package_features');
        Schema::dropIfExists('course_packages');
    }
};
