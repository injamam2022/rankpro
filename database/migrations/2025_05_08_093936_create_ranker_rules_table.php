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
        Schema::create('ranker_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uniq_id');
            $table->integer('language');
            $table->text('rule');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

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
        Schema::dropIfExists('ranker_rules');
    }
};
