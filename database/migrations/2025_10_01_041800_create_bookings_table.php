<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            
            // User Info
            $table->string('full_name');
            $table->string('contact_number');
            $table->string('email')->nullable();
            
            // Event Info
            $table->string('event_name');
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('venue');
            $table->text('notes')->nullable();
            
            // Status
            $table->string('status')->default('Pending'); // Pending, Confirmed, Declined
            
            // Payment Info
            $table->string('payment_method')->nullable();
            $table->decimal('event_price', 10, 2)->nullable();
            $table->decimal('downpayment_amount', 10, 2)->nullable();
            $table->string('gcash_name')->nullable();
            $table->string('gcash_number')->nullable();
            $table->string('gcash_receipt')->nullable();
            
            // Timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};