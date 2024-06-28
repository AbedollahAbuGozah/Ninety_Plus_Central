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
        Schema::create('lectures', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('link');
            $table->string('description');
            $table->date('starts_at');

            $table->foreignId('chapter_id')
                ->constrained('chapters');

            $table->enum('status', ['draft', 'live', 'over'])->default('draft');

            $table->foreignId('course_id')->constrained('courses');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};
