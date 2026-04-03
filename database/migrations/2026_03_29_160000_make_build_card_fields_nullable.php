<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('builds', function (Blueprint $table): void {
            $table->string('gpu')->nullable()->change();
            $table->text('cpu')->nullable()->change();
            $table->string('ram')->nullable()->change();
            $table->string('storage')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('builds', function (Blueprint $table): void {
            $table->string('gpu')->nullable(false)->default('')->change();
            $table->text('cpu')->nullable(false)->change();
            $table->string('ram')->nullable(false)->default('')->change();
            $table->string('storage')->nullable(false)->default('')->change();
        });
    }
};
