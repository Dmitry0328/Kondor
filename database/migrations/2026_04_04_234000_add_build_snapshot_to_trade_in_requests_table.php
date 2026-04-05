<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_in_requests', function (Blueprint $table): void {
            $table->json('build_snapshot')->nullable()->after('photo_paths');
        });
    }

    public function down(): void
    {
        Schema::table('trade_in_requests', function (Blueprint $table): void {
            $table->dropColumn('build_snapshot');
        });
    }
};
