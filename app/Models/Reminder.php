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
        'dompet digital',
        'total',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'tambahan' => 'array', // Mengubah tambahan menjadi array
    ];

    protected static $durations = [
        'paket1' => '2:20',
        'paket2' => '3:00',
        'paket3' => '4:00',
        'paket4' => '7:10',
        'paket5' => '8:00',
        'paket6' => '10:00',
        'paket0' => '00:02',
    ];

    protected static $prices = [
        'paket1' => 10000, // Harga untuk paket 1
        'paket2' => 20000, // Harga untuk paket 2
        'paket3' => 30000, // Harga untuk paket 3
        'paket4' => 35000, // Harga untuk paket 4
        'paket5' => 40000, // Harga untuk paket 5
        'paket6' => 55000, // Harga untuk paket 6
        'paket0' => 2000,  // Harga untuk paket 0
    ];

    protected static function booted()
    {
        static::saving(function ($reminder) {
            if (!$reminder->created_at) {
                $reminder->created_at = now();
            }

            $reminder->waktu_selesai = self::calculateEndTime($reminder->waktu_mulai, $reminder->paket);
            $reminder->durasi = self::$durations[$reminder->paket] ?? '0:00';
            $reminder->harga = self::$prices[$reminder->paket] ?? 0; // Mengatur harga berdasarkan paket
            
            // Menghitung total harga tambahan
            $totalTambahan = self::getTotalPriceFromTambahan($reminder->tambahan);
            $reminder->total = $reminder->harga + $totalTambahan - $reminder->belum_bayar - $reminder->{'dompet_digital'}; // Total = harga + total_harga_tambahan - belum_bayar - dompet_digital
        });
    }

    protected static function getTotalPriceFromTambahan($tambahan): int
    {
        $tambahanPrices = [
            'Minola' => 2000, // Rp2.000
            'Golda/Milku/Abc' => 3500, // Rp3.500
            'TehPucuk' => 4000, // Rp4.000
        ];

        // Menghitung total harga dari tambahan yang diberikan
        $total = 0;
        foreach ((array)$tambahan as $item) {
            // Memisahkan nama tambahan dan jumlah
            $parts = explode(' ', $item);
            $tambahanName = $parts[0]; // Nama tambahan
            $tambahanQuantity = isset($parts[1]) ? (int)$parts[1] : 1; // Jumlah tambahan, default 1

            // Menghitung harga tambahan
            if (array_key_exists($tambahanName, $tambahanPrices)) {
                $total += $tambahanPrices[$tambahanName] * $tambahanQuantity; // Menambahkan harga jika tambahan ditemukan
            }
        }
        return $total; // Mengembalikan total harga
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
        return implode(' + ', $this->tambahan); // Menggabungkan tambahan dengan tanda "+"
    }
}