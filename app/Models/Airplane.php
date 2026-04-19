<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airplane extends Model
{
    // Mengizinkan semua kolom diisi
    protected $guarded = ['id'];

    // Relasi ke tabel Airlines
    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }
}