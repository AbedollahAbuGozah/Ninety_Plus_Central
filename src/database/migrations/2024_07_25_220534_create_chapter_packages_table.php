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
        Schema::create('chapter_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('chapter_id')->constrained('chapters')->cascadeOnUpdate()->cascadeOnUpdate();
            $table->integer('exams_count')->default(2);
            $table->unique(['package_id', 'chapter_id']);

            NinetyPlus::addPropsColumn($table);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapter_packages');
    }
};
