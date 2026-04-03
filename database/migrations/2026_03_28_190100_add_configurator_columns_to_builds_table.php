<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('builds', function (Blueprint $table): void {
            $table->json('base_components')->nullable()->after('about');
            $table->json('configurator_groups')->nullable()->after('base_components');
        });
    }

    public function down(): void
    {
        Schema::table('builds', function (Blueprint $table): void {
            $table->dropColumn([
                'base_components',
                'configurator_groups',
            ]);
        });
    }
};
