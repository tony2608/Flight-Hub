<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Pakai $fillable untuk keamanan ekstra (Hanya kolom ini yang boleh diisi otomatis)
    protected $fillable = [
        'flight_id',
        'booking_code',
        'contact_name',
        'contact_phone',
        'contact_email',
        'total_price',
        'status',
        'payment_receipt',
        'snap_token',
        'cancel_reason', // <-- Ini yang wajib ada buat Midtrans
    ];

    // ============================================
    // RELASI DATABASE
    // ============================================

    /**
     * 1 Transaksi bisa punya BANYAK Penumpang
     */
    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    /**
     * 1 Transaksi milik 1 Jadwal Penerbangan
     */
    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }
}