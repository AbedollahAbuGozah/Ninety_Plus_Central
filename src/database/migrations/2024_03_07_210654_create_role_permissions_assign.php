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
        Schema::create('role_permissions_assign', function (Blueprint $table) {
            $table->id();
            $table->string('resource_name');

            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('resource_name')
                ->references('name')
                ->on('resources')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->boolean('view_access')->default(false);
            $table->boolean('modify_access')->default(false);
            $table->boolean('delete_access')->default(false);
            $table->boolean('add_access')->default(false);
            $table->boolean('manage_access')->default(false);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions_assign');
    }
};
