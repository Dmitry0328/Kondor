<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accessories', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 40);
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('vendor')->nullable();
            $table->string('sku')->nullable();
            $table->string('summary')->nullable();
            $table->json('gallery_paths')->nullable();
            $table->json('specs')->nullable();
            $table->json('package_items')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accessories');
    }
};
