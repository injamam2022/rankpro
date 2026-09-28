<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('batches') && !Schema::hasColumn('batches', 'status')) {
            Schema::table('batches', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->after('name');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('batches') && Schema::hasColumn('batches', 'status')) {
            Schema::table('batches', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
