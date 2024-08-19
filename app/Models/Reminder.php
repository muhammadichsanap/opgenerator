<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'waktu_mulai',
        'waktu_selesai',
        'no_pc',
        'paket',
        'durasi',
        'kelas_pc',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    protected static $durations = [
        'paket1' => '1:00',
        'paket2' => '2:20',
        'paket3' => '4:00',
        'paket4' => '5:00',
        'paket5' => '6:00',
        'paket6' => '10:00',
    ];

    protected static function booted()
    {
        static::saving(function ($reminder) {
            if (!$reminder->created_at) {
                $reminder->created_at = now();
            }

            $reminder->waktu_selesai = self::calculateEndTime($reminder->waktu_mulai, $reminder->paket);
            $reminder->durasi = self::$durations[$reminder->paket] ?? '0:00';
        });
    }

    public function setPaketAttribute($value)
    {
        $this->attributes['paket'] = $value;
    }

    protected static function calculateEndTime($startTime, $packageId)
    {
        $startTime = Carbon::parse($startTime);
        $duration = self::$durations[$packageId] ?? '0:00';
        [$hours, $minutes] = explode(':', $duration);

        return $startTime->addHours((int)$hours)->addMinutes((int)$minutes);
    }

    public static function getDurationFromPackage($packageId): string
    {
        return self::$durations[$packageId] ?? '0:00';
    }
}