<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (! Schema::hasTable('questions')) {
            return;
        }

        if (! Schema::hasColumn('questions', 'is_premium')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->unsignedTinyInteger('is_premium')->default(0)->index();
            });
        }

        DB::table('questions')->update(['is_premium' => 0]);
    }

    public function down()
    {
        if (Schema::hasTable('questions') && Schema::hasColumn('questions', 'is_premium')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn('is_premium');
            });
        }
    }
};
