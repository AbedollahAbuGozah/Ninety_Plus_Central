<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('answer_options', function (Blueprint $table) {
            $table->integer('seq')->nullable()->change();
            $table->boolean('is_correct')->nullable()->change();
            $table->dropForeign(['answer_id']);
            $table->unsignedBigInteger('answer_id')->nullable()->change();
            $table->foreign('answer_id')
                ->references('id')
                ->on('answer_options')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->dropForeign(['question_id']);
            $table->unsignedBigInteger('question_id')->change();
            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('answer_options', function (Blueprint $table) {
            $table->integer('seq')->change();
            $table->boolean('is_correct')->change();

            $table->dropForeign(['answer_id']);
            $table->unsignedBigInteger('answer_id')->nullable(false)->change();
            $table->foreign('answer_id')
                ->references('id')
                ->on('answer_options')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->dropForeign(['question_id']);
            $table->unsignedBigInteger('question_id')->change();
            $table->foreign('question_id')
                ->references('id')
                ->on('questions');
        });
    }
};
