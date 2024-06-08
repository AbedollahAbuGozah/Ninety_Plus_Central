<?php

use App\constants\CourseStatusOptions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('period', ['first', 'second', 'third']);
            $table->date('starts_at')->default(now());
            $table->date('ends_at')->default(now());
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('status', CourseStatusOptions::options())->default(CourseStatusOptions::DRAFT);
            $table->integer('price')->default(0);
            $table->text('description');
            $table->json('properties');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
