<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Builds\BuildResource;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function mount(): void
    {
        $this->redirect(BuildResource::getUrl('index', isAbsolute: false), navigate: false);
    }
}
