<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('builds', function (Blueprint $table): void {
            $table->string('product_code', 64)->nullable()->after('name');
        });

        DB::table('builds')
            ->select(['id', 'product_code'])
            ->orderBy('id')
            ->get()
            ->each(function (object $build): void {
                $productCode = trim((string) ($build->product_code ?? ''));

                if ($productCode !== '') {
                    return;
                }

                DB::table('builds')
                    ->where('id', $build->id)
                    ->update([
                        'product_code' => (string) (570000 + (int) $build->id),
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('builds', function (Blueprint $table): void {
            $table->dropColumn('product_code');
        });
    }
};
