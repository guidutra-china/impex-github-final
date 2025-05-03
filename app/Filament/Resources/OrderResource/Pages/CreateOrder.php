<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use App\Models\OrderItem;

class CreateOrder extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = OrderResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make("Order Details")
                // Pass the array returned by the method directly to schema()
                ->schema(OrderResource::getStep1FormSchema()),
            Step::make("Add Products & Schedule") // Renamed step as schedule is now nested
            // Pass the array returned by the method directly to schema()
            ->schema(OrderResource::getStep2FormSchema()),
            // Removed Step 3 as scheduling is now part of Step 2
//            Step::make("Production Schedule")
//                ->schema(OrderResource::getStep3FormSchema()),
        ];
    }

    protected function afterCreate(): void
    {
        $orderItemsData = $this->data["orderItems"] ?? [];
        $savedOrderItems = $this->record->orderItems()->get()->keyBy("product_id");

        foreach ($orderItemsData as $itemData) {
            $productId = $itemData["product_id"] ?? null;
            $productionSchedulesData = $itemData["productionSchedules"] ?? [];

            if ($productId && isset($savedOrderItems[$productId]) && !empty($productionSchedulesData)) {
                $orderItem = $savedOrderItems[$productId];
                foreach ($productionSchedulesData as $scheduleData) {
                    $orderItem->productionSchedules()->create([
                        "scheduled_date" => $scheduleData["scheduled_date"],
                        "quantity_scheduled" => $scheduleData["quantity_scheduled"],
                    ]);
                }
            }
        }
    }

}
