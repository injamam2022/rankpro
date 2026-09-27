<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        foreach (['batch_user' => ['batch_id', 'user_id'], 'batch_exam' => ['batch_id', 'exam_id'], 'exam_assignments' => ['exam_id', 'user_id']] as $name => $columns) {
            Schema::create($name, function (Blueprint $table) use ($columns) {
                foreach ($columns as $column) {
                    $table->unsignedBigInteger($column)->index();
                }
                $table->unique($columns);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('exam_assignments');
        Schema::dropIfExists('batch_exam');
        Schema::dropIfExists('batch_user');
        Schema::dropIfExists('batches');
    }
};
