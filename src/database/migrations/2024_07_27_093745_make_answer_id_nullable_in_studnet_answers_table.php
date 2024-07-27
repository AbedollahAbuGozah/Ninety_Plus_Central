<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class  extends Migration
{
    public function up(): void
    {
        Schema::table('student_answers', function (Blueprint $table) {
            $table->dropForeign(['answer_id']);

            $table->unsignedBigInteger('answer_id')->nullable()->change();

            $table->foreign('answer_id')
                ->references('id')
                ->on('answer_options')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }


    public function down(): void
    {
        Schema::table('student_answers', function (Blueprint $table) {
            $table->dropForeign(['answer_id']);
            $table->unsignedBigInteger('answer_id')->nullable(false)->change();
            $table->foreign('answer_id')->references('id')->on('answer_options')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

};
