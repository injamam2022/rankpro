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
        Schema::create('success_story_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('success_story_id');
            $table->integer('language');
            $table->text('text');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('success_story_id')->references('id')->on('success_stories')->onDelete('cascade');
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
        Schema::dropIfExists('success_story_descriptions');
    }
};
