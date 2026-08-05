<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Visitor extends Model
{
    protected $fillable = ['visitor_id', 'name', 'address', 'phone'];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Generate visitor_id: nama depan + nomor urut GLOBAL 4 digit.
     * Contoh: muhammad0001, lalu siapapun yang daftar berikutnya
     * (walau namanya beda) dapat 0002, 0003, dst — nomornya nggak
     * pernah ngulang dari 1 lagi.
     */
    public static function generateVisitorId(string $name): string
    {
        $firstName = strtolower(explode(' ', trim($name))[0]);
        // Bersihkan karakter non-alfanumerik
        $firstName = preg_replace('/[^a-z0-9]/', '', $firstName);

        if ($firstName === '') {
            $firstName = 'pengunjung';
        }

        return DB::transaction(function () use ($firstName) {
            // Kunci semua baris visitor selama transaksi ini, biar kalau ada
            // 2 orang daftar barengan, keduanya nggak bakal kebagi nomor yang sama.
            $allIds = self::lockForUpdate()->pluck('visitor_id');

            // Ambil nomor urut TERBESAR dari SEMUA visitor_id yang ada
            // (bukan cuma yang nama depannya sama), lalu +1.
            $lastNumber = 0;
            foreach ($allIds as $id) {
                if (preg_match('/(\d+)$/', $id, $match)) {
                    $lastNumber = max($lastNumber, (int) $match[1]);
                }
            }

            $number = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            return $firstName.$number;
        });
    }
}
