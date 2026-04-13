<?php

namespace App\Console\Commands;

use App\Services\KondorDeviceImporter;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('accessories:import-kondor-device {--keep-missing : Do not deactivate imported items that disappeared on the source site}')]
#[Description('Import devices from kondordevice.com into the accessories table')]
class ImportKondorDeviceAccessories extends Command
{
    public function handle(KondorDeviceImporter $importer): int
    {
        $stats = $importer->import(
            deactivateMissing: ! (bool) $this->option('keep-missing'),
        );

        $this->table(
            ['Found', 'Created', 'Updated', 'Skipped', 'Failed', 'Deactivated'],
            [[
                $stats['found'],
                $stats['created'],
                $stats['updated'],
                $stats['skipped'],
                $stats['failed'],
                $stats['deactivated'],
            ]],
        );

        foreach ((array) ($stats['errors'] ?? []) as $error) {
            $this->warn($error);
        }

        $this->info('Імпорт із Kondor Device завершено.');

        return ($stats['failed'] ?? 0) > 0 ? self::FAILURE : self::SUCCESS;
    }
}
