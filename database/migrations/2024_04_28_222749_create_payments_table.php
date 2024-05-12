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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->OnDelete('set null')->cascadeOnUpdate();
            $table->foreignId('ticket_id')->constrained('tickets')->OnDelete('set null')->cascadeOnUpdate();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('payment_method');
            $table->string('payment_date');
            $table->string('reference');
            $table->string('payee_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
