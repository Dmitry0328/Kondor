<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable()->unique();
            $table->string('status')->default('new');
            $table->string('customer_name');
            $table->string('phone', 40);
            $table->string('email')->nullable();
            $table->text('comment')->nullable();
            $table->string('payment_method')->default('cash_on_delivery');
            $table->unsignedInteger('total_amount');
            $table->string('currency', 3)->default('UAH');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
