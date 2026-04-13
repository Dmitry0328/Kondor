<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('builds', function (Blueprint $table): void {
            if (! Schema::hasColumn('builds', 'case_variants')) {
                $table->json('case_variants')->nullable()->after('resolution_tags');
            }
        });
    }

    public function down(): void
    {
        Schema::table('builds', function (Blueprint $table): void {
            if (Schema::hasColumn('builds', 'case_variants')) {
                $table->dropColumn('case_variants');
            }
        });
    }
};
