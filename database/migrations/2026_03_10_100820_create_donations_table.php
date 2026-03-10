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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // link to users table
            $table->decimal('amount', 8, 2); // amount of donation
            $table->string('phone');
            $table->string('reference')->unique(); // internal reference for the donation
            $table->string('merchant_request_id')->nullable()->index(); // reference from safaricom
            $table->string('checkout_request_id')->nullable()->index(); // reference from safaricom
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->string('mpesa_receipt')->nullable(); // set after successful payment
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
