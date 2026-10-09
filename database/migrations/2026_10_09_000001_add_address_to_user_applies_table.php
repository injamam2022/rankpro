<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        foreach (['user_applies', 'user_apply'] as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'address')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->string('address')->nullable();
                });
            }
        }
    }

    public function down()
    {
        foreach (['user_applies', 'user_apply'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'address')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropColumn('address');
                });
            }
        }
    }
};
