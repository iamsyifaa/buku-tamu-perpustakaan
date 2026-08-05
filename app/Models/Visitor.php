<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = ['visitor_id', 'name', 'address', 'phone'];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Generate visitor_id: ambil nama depan + nomor urut 4 digit
     * Contoh: faisal0001
     */
    public static function generateVisitorId(string $name): string
    {
        $firstName = strtolower(explode(' ', trim($name))[0]);
        // Bersihkan karakter non-alfanumerik
        $firstName = preg_replace('/[^a-z0-9]/', '', $firstName);

        // Cari berapa banyak visitor dengan prefix nama yang sama
        $count = self::where('visitor_id', 'like', $firstName . '%')->count();
        $number = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        return $firstName . $number;
    }
}
