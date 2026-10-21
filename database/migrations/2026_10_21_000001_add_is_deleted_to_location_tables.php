<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        foreach (['countries', 'states', 'cities', 'locations'] as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'is_deleted')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->tinyInteger('is_deleted')->default(0)->index();
                });
            }
        }
    }

    public function down()
    {
        foreach (['countries', 'states', 'cities', 'locations'] as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'is_deleted')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('is_deleted');
                });
            }
        }
    }
};
