<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = ['visitor_id', 'baca_buku', 'pinjam_buku', 'belajar_komputer', 'visited_at'];

    protected $casts = [
        'baca_buku'        => 'boolean',
        'pinjam_buku'      => 'boolean',
        'belajar_komputer' => 'boolean',
        'visited_at'       => 'datetime',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
