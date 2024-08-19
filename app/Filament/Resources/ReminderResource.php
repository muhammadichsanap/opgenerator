<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReminderResource\Pages;
use App\Models\Reminder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

class ReminderResource extends Resource
{
    protected static ?string $model = Reminder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string $timeFormat = 'H:i';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TimePicker::make('waktu_mulai')
                    ->label('Waktu Mulai')
                    ->required()
                    ->default(Carbon::now()->format(self::$timeFormat))
                    ->format(self::$timeFormat)
                    ->withoutSeconds(),

                TimePicker::make('waktu_selesai')
                    ->label('Waktu Selesai')
                    ->required()
                    ->disabled()
                    ->format(self::$timeFormat)
                    ->withoutSeconds()
                    ->afterStateHydrated(fn ($state, callable $set, callable $get) =>
                        $set('waktu_selesai', self::calculateEndTime($get('waktu_mulai'), $get('paket')))),

                TextInput::make('no_pc')
                    ->label('Nomor PC')
                    ->numeric()
                    ->required(),

                Select::make('paket')
                    ->label('Paket')
                    ->options([
                        'paket1' => 'Paket 1',
                        'paket2' => 'Paket 2',
                        'paket3' => 'Paket 3',
                        'paket4' => 'Paket 4',
                        'paket5' => 'Paket 5',
                        'paket6' => 'Paket 6',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                        $set('waktu_selesai', self::calculateEndTime($get('waktu_mulai'), $state))),

                Select::make('kelas_pc')
                    ->label('Kelas PC')
                    ->options([
                        'regular' => 'Regular',
                        'vip' => 'VIP',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_pc')->label('Nomor PC'),
                TextColumn::make('paket')->label('Paket'),
                TextColumn::make('kelas_pc')->label('Kelas PC'),
                TextColumn::make('waktu_mulai')->label('Waktu Mulai')->sortable(),
                TextColumn::make('waktu_selesai')->label('Waktu Selesai')->sortable(),
                TextColumn::make('durasi')
                    ->label('Durasi/Jam')
                    ->getStateUsing(function (Reminder $record): string {
                        return self::calculateElapsedDuration($record);
                    })
                    ->extraAttributes(fn ($record) => [
                        'class' => 'durasi-column',
                        'data-start-time' => $record->waktu_mulai,
                        'data-end-time' => $record->waktu_selesai,
                        'data-created-at' => $record->created_at->timestamp, // Tambahkan ini
                    ]),            
                ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReminders::route('/'),
            'create' => Pages\CreateReminder::route('/create'),
            'edit' => Pages\EditReminder::route('/{record}/edit'),
        ];
    }

    protected static function getDurationFromPackage($packageId): string
    {
        $durations = [
            'paket1' => '1:00',
            'paket2' => '2:20',
            'paket3' => '4:00',
            'paket4' => '5:00',
            'paket5' => '6:00',
            'paket6' => '10:00',
        ];

        return $durations[$packageId] ?? '0:00';
    }

    protected static function calculateEndTime($startTime, $packageId): string
    {
        try {
            $startTime = Carbon::createFromFormat(self::$timeFormat, $startTime);
            $duration = self::getDurationFromPackage($packageId);
            [$hours, $minutes] = explode(':', $duration);

            return $startTime->copy()->addHours((int)$hours)->addMinutes((int)$minutes)->format(self::$timeFormat);
        } catch (\Exception $e) {
            return 'Error';
        }
    }

    protected static function calculateElapsedDuration(Reminder $reminder): string
    {
        $now = Carbon::now();
        $startTime = Carbon::parse($reminder->waktu_mulai);
        $endTime = Carbon::parse($reminder->waktu_selesai);
        $createdAt = $reminder->created_at;

        if ($now > $endTime) {
            return self::formatDuration($startTime->diffInSeconds($endTime));
        }

        return self::formatDuration($createdAt->diffInSeconds($now));
    }

    protected static function formatDuration(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
    }
}
