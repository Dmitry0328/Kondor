<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accessories', function (Blueprint $table): void {
            $table->string('import_source', 40)->nullable()->after('slug');
            $table->string('external_id')->nullable()->after('import_source');
            $table->string('source_url')->nullable()->after('external_id');
            $table->json('remote_image_urls')->nullable()->after('gallery_paths');
            $table->timestamp('last_synced_at')->nullable()->after('is_active');

            $table->index(['import_source', 'external_id'], 'accessories_import_source_external_id_idx');
            $table->index('import_source');
        });
    }

    public function down(): void
    {
        Schema::table('accessories', function (Blueprint $table): void {
            $table->dropIndex('accessories_import_source_external_id_idx');
            $table->dropIndex(['import_source']);

            $table->dropColumn([
                'import_source',
                'external_id',
                'source_url',
                'remote_image_urls',
                'last_synced_at',
            ]);
        });
    }
};
