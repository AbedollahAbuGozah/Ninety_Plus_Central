<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->foreignId('chapter_id')->constrained('chapters');
            $table->integer('expectation_time')->nullable();
            $table->enum('label', ['easy', 'medium', 'hard']);
            $table->integer('weight')->nullable();
            $table->text('hint')->nullable();
            $table->string('type');
            \App\Facades\NinetyPlusCentralFacade::addPropsColumn($table);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
