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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_type_id')->constrained()->onDelete('cascade');
            $table->string('domain_name')->nullable();
            $table->json('configuration')->nullable();
            $table->enum('status', ['pending', 'active', 'suspended', 'cancelled'])->default('pending');
            $table->date('next_billing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->json('provisioning_data')->nullable();
            $table->timestamps();
            
            $table->index('customer_id');
            $table->index('service_type_id');
            $table->index('status');
            $table->index('next_billing_date');
            $table->index(['customer_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};