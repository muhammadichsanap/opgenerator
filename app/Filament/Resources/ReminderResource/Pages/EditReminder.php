<?php

namespace App\Filament\Resources\ReminderResource\Pages;

use App\Filament\Resources\ReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReminder extends EditRecord
{
    protected static string $resource = ReminderResource::class;

    protected function afterSave(): void
    {
        // Redirect ke halaman daftar setelah menyimpan
        $this->redirect('/admin/reminders?tableFilters[today][isActive]=true&tableFilters[completed][isActive]=false'); // Mengarahkan ke /admin/reminders
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}