<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    // INI DIA SATPAMNYA BOSKU! Wajib didaftarin biar datanya boleh masuk
protected $fillable = [
        'title', 
        'badge', 
        'description', 
        'image', 
        'promo_code',
        'code',
        'discount' // <--- TAMBAHKAN INI BOSKU
    ];
}