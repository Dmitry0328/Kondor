<?php

namespace App\Console\Commands;

use App\Models\SharedBuildLink;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('shared-build-links:prune-expired')]
#[Description('Delete expired shared build links')]
class PruneExpiredSharedBuildLinks extends Command
{
    public function handle(): int
    {
        $expiredQuery = SharedBuildLink::query()->expired();
        $expiredCount = (clone $expiredQuery)->count();

        if ($expiredCount === 0) {
            $this->info('Прострочених посилань на збірки не знайдено.');

            return self::SUCCESS;
        }

        $expiredQuery->delete();

        $this->info("Видалено {$expiredCount} прострочених посилань на збірки.");

        return self::SUCCESS;
    }
};
