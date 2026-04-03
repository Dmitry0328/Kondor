<?php

namespace App\Console\Commands;

use App\Models\SharedCart;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('shared-carts:prune-expired')]
#[Description('Delete expired shared cart links')]
class PruneExpiredSharedCarts extends Command
{
    public function handle(): int
    {
        $expiredQuery = SharedCart::query()->expired();
        $expiredCount = (clone $expiredQuery)->count();

        if ($expiredCount === 0) {
            $this->info('Прострочених shared-корзин не знайдено.');

            return self::SUCCESS;
        }

        $expiredQuery->delete();

        $this->info("Видалено {$expiredCount} прострочених shared-корзин.");

        return self::SUCCESS;
    }
}
