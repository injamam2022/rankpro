<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_series_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_series_id');
            $table->string('date_name');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('test_series_id')->references('id')->on('test_series')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_series_dates');
    }
};
