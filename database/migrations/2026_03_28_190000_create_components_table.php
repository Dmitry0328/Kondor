<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('components', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 40);
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('vendor')->nullable();
            $table->string('sku')->nullable();
            $table->string('summary')->nullable();
            $table->string('socket')->nullable();
            $table->string('ram_type')->nullable();
            $table->string('form_factor')->nullable();
            $table->json('supported_mb_form_factors')->nullable();
            $table->json('supported_sockets')->nullable();
            $table->json('supported_radiator_sizes')->nullable();
            $table->unsignedInteger('max_gpu_length_mm')->nullable();
            $table->unsignedInteger('max_cooler_height_mm')->nullable();
            $table->unsignedInteger('gpu_length_mm')->nullable();
            $table->unsignedInteger('gpu_power_w')->nullable();
            $table->unsignedInteger('gpu_power_connectors')->nullable();
            $table->unsignedInteger('cpu_tdp_w')->nullable();
            $table->unsignedInteger('psu_wattage')->nullable();
            $table->unsignedInteger('pcie_power_connectors')->nullable();
            $table->unsignedInteger('radiator_size_mm')->nullable();
            $table->unsignedInteger('memory_modules')->nullable();
            $table->unsignedInteger('memory_capacity_gb')->nullable();
            $table->unsignedInteger('memory_speed_mhz')->nullable();
            $table->string('storage_interface')->nullable();
            $table->json('meta')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};
