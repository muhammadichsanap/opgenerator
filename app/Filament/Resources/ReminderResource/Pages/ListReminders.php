<?php

namespace App\Filament\Resources\ReminderResource\Pages;

use App\Filament\Resources\ReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReminders extends ListRecords
{
    protected static string $resource = ReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // Override the mount method without parameters
    public function mount(): void
    {
        parent::mount();

        // Apply default filters if not already set
        $this->applyDefaultFilters();
    }

    protected function applyDefaultFilters(): void
    {
        $this->tableFilters = [
            'today' => [
                'isActive' => true,
            ],
            'completed' => [
                'isActive' => false,
            ],
        ];
    }
}
