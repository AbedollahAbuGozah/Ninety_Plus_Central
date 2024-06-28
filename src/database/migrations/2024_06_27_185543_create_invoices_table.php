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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->morphs('invoiceable');
            $table->foreignId('user_id')->constrained('users');
            $table->date('date');
            $table->text('description');
            $table->string('currency');
            $table->string('payment_method');
            $table->integer('quantity');
            $table->string('payment_status');
            $table->string('paid_date');
            $table->date('due_date');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
