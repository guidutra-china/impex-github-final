<?php

namespace App\Filament\Resources\ProductionScheduleResource\Pages;

use App\Filament\Resources\ProductionScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductionSchedule extends EditRecord
{
    protected static string $resource = ProductionScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
