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
        Schema::create('ranker_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ranker_id');
            $table->unsignedBigInteger('language');
            $table->text('text');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('ranker_id')->references('id')->on('rankers')->onDelete('cascade');
            $table->foreign('language')->references('id')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ranker_feedbacks');
    }
};
