<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    protected $guarded = ['id'];

    // 1 Penumpang dimiliki oleh 1 Transaksi
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}