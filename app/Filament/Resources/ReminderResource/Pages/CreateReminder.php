<?php

namespace App\Filament\Resources\ReminderResource\Pages;

use App\Filament\Resources\ReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReminder extends CreateRecord
{
    protected static string $resource = ReminderResource::class;

    // Ubah parameter metode create agar sesuai
    public function create(bool $another = false): void
    {
        parent::create($another); // Memanggil metode create dari parent

        // Redirect ke halaman daftar dengan filter setelah menyimpan
        $this->redirect('/admin/reminders?tableFilters[today][isActive]=true&tableFilters[completed][isActive]=false'); // Mengarahkan ke /admin/reminders dengan filter
    }
}