<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('number')->nullable()->unique();
            $table->string('status')->default('new');
            $table->string('customer_name');
            $table->string('phone', 40);
            $table->string('messenger_contact')->nullable();
            $table->string('email')->nullable();
            $table->text('comment')->nullable();
            $table->string('payment_method')->default('cash_on_delivery');
            $table->unsignedInteger('total_amount');
            $table->string('currency', 3)->default('UAH');
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('shared_carts', function (Blueprint $table): void {
            $table->id();
            $table->string('token')->unique();
            $table->json('payload');
            $table->timestamp('expires_at')->index();
            $table->timestamps();
        });

        Schema::create('site_images', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('disk')->default('public');
            $table->string('path');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('fps_games', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('badge')->nullable();
            $table->string('accent', 32)->nullable();
            $table->string('scene_from', 32)->nullable();
            $table->string('scene_to', 32)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('fps_displays', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('mobile_name')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('fps_presets', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('builds', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('tone', 32)->default('violet');
            $table->string('name');
            $table->string('product_code', 64)->nullable();
            $table->string('gpu')->nullable();
            $table->text('cpu')->nullable();
            $table->string('ram')->nullable();
            $table->string('storage')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('fps_score')->default(90);
            $table->json('fps_profiles')->nullable();
            $table->json('product_specs')->nullable();
            $table->json('about')->nullable();
            $table->json('base_components')->nullable();
            $table->json('configurator_groups')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('components', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 40);
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('vendor')->nullable();
            $table->string('sku')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->string('summary')->nullable();
            $table->json('gallery_paths')->nullable();
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

        Schema::create('shared_build_links', function (Blueprint $table): void {
            $table->id();
            $table->string('token', 64)->unique();
            $table->string('build_slug')->index();
            $table->string('build_name');
            $table->json('payload')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('accessories', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 40);
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('vendor')->nullable();
            $table->string('sku')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->string('summary')->nullable();
            $table->json('gallery_paths')->nullable();
            $table->json('specs')->nullable();
            $table->json('package_items')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

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
            $table->json('build_snapshot')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('build_slug');
            $table->string('build_name');
            $table->string('build_url')->nullable();
            $table->string('tone', 40)->nullable();
            $table->unsignedInteger('unit_price');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('line_total');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('trade_in_requests');
        Schema::dropIfExists('accessories');
        Schema::dropIfExists('shared_build_links');
        Schema::dropIfExists('components');
        Schema::dropIfExists('builds');
        Schema::dropIfExists('fps_presets');
        Schema::dropIfExists('fps_displays');
        Schema::dropIfExists('fps_games');
        Schema::dropIfExists('site_images');
        Schema::dropIfExists('shared_carts');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('notifications');
    }
};
