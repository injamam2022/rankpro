<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('blogs')) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'keywords')) {
                $table->text('keywords')->nullable()->after('meta_description');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('blogs') || !Schema::hasColumn('blogs', 'keywords')) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('keywords');
        });
    }
};
