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
        Schema::create('cvs', function (Blueprint $table) {
            $table->id();
            $table->string('path')->unique();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_accepted')->default(false);
            \App\Facades\NinetyPlusCentralFacade::addPropsColumn($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cvs');
    }
};
