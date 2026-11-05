<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_package_order_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_package_order_id')->constrained('course_package_orders')->cascadeOnDelete();
            $table->integer('user_id');
            $table->unsignedBigInteger('chapter_id');
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'chapter_id']);
            $table->index('chapter_id');
        });

        $packageId = DB::table('course_packages')->where('code', 'chapter_master')->value('id');
        if ($packageId) {
            DB::table('course_package_features')
                ->where('course_package_id', $packageId)
                ->where('text', '85 chapters across PCB')
                ->update(['text' => 'Pick up to 6 chapters at ₹49 each']);

            DB::table('course_package_modes')
                ->where('course_package_id', $packageId)
                ->where('mode_code', 'online')
                ->update([
                    'help_text' => 'Choose 1 to 6 chapters. ₹49 each. You can buy more chapters in a later payment.',
                ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('course_package_order_chapters');
    }
};
