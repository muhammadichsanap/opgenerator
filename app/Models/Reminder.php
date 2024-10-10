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
        'harga',
        'tambahan',
        'belum_bayar',
        'dompet_digital',
        'waktu_dihentikan',
        'total',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'tambahan' => 'array', // Convert 'tambahan' to an array
    ];

    protected static $durations = [
        'paket1' => '2:20',
        'paket2' => '3:00',
        'paket3' => '4:00',
        'paket4' => '5:40',
        'paket5' => '7:10',
        'paket6' => '9:00',
        'paket8' => '00:35',
        'paket9' => '01:00',
        'paket0' => '00:02',
        'vip20k' => '3:00',
        'vip30k' => '6:00',
    ];

    protected static $prices = [
        'paket1' => 10000,
        'paket2' => 12000,
        'paket3' => 15000,
        'paket4' => 20000,
        'paket5' => 25000,
        'paket6' => 30000,
        'paket8' => 3000,
        'paket9' => 5000,
        'paket0' => 2000,
        'vip20k' => 20000,
        'vip30k' => 30000,
    ];

    protected static function booted()
    {
        static::saving(function ($reminder) {
            if (!$reminder->created_at) {
                $reminder->created_at = now();
            }

            $reminder->waktu_selesai = self::calculateEndTime($reminder->waktu_mulai, $reminder->paket);
            $reminder->durasi = self::$durations[$reminder->paket] ?? '0:00';
            $reminder->harga = self::$prices[$reminder->paket] ?? 0;

            // Ensure 'tambahan' is an array or empty array if null
            $tambahanArray = is_array($reminder->tambahan) ? $reminder->tambahan : json_decode($reminder->tambahan, true);
            $tambahanArray = $tambahanArray ?? []; // Set to empty array if null
            $totalTambahan = self::getTotalPriceFromTambahan($tambahanArray);

            // Calculate total price
            $reminder->total = $reminder->harga + $totalTambahan;

            // Calculate digital wallet
            if ($reminder->dompet_digital) {
                $reminder->total -= $reminder->dompet_digital;
            }

            // Calculate outstanding balance
            if ($reminder->belum_bayar) {
                $reminder->total -= $reminder->belum_bayar;
            }
        });
    }

    protected static function getTotalPriceFromTambahan(array $tambahan): int
    {
        $tambahanPrices = [
            'Minola' => 2000,
            'Golda/Milku/Abc' => 3500,
            'TehPucuk' => 4000,
        ];

        $total = 0;
        foreach ($tambahan as $item) {
            // Split the item into name and quantity
            $parts = explode(' ', $item);
            $tambahanName = $parts[0]; // Item name
            $tambahanQuantity = isset($parts[1]) ? (int)$parts[1] : 1; // Quantity, default to 1

            // Calculate price
            if (array_key_exists($tambahanName, $tambahanPrices)) {
                $total += $tambahanPrices[$tambahanName] * $tambahanQuantity;
            }
        }
        return $total;
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

    public function getFormattedTambahanAttribute()
    {
        return implode(' + ', $this->tambahan);
    }
}
