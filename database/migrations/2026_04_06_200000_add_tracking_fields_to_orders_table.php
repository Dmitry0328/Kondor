<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dateTime('ordered_at')->nullable()->after('status')->index();
            $table->text('tracking_password')->nullable()->after('phone');
            $table->string('shipping_ttn')->nullable()->after('payment_method');
        });

        DB::table('orders')
            ->whereNull('ordered_at')
            ->update([
                'ordered_at' => DB::raw('created_at'),
            ]);

        DB::table('orders')
            ->select(['id', 'number', 'created_at', 'ordered_at', 'tracking_password'])
            ->orderBy('id')
            ->get()
            ->each(function (object $row): void {
                $updates = [];

                if (blank($row->number)) {
                    $date = Carbon::parse($row->ordered_at ?? $row->created_at ?? now())->format('ymd');
                    $updates['number'] = 'KP-' . $date . '-' . str_pad((string) $row->id, 5, '0', STR_PAD_LEFT);
                }

                if (blank($row->tracking_password)) {
                    $updates['tracking_password'] = Crypt::encryptString(Order::generateTrackingPassword());
                }

                if ($updates !== []) {
                    DB::table('orders')
                        ->where('id', $row->id)
                        ->update($updates);
                }
            });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropIndex(['ordered_at']);
            $table->dropColumn([
                'ordered_at',
                'tracking_password',
                'shipping_ttn',
            ]);
        });
    }
};
