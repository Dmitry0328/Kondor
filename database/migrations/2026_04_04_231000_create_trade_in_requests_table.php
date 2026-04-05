<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_in_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('build_id')->nullable()->constrained()->nullOnDelete();
            $table->string('build_slug')->nullable()->index();
            $table->string('build_name')->nullable();
            $table->string('status', 24)->default('new')->index();
            $table->string('customer_name');
            $table->string('phone', 40);
            $table->string('messenger_contact')->nullable();
            $table->text('description');
            $table->json('photo_paths')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_in_requests');
    }
};
