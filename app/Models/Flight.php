<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    // Izinkan semua kolom diisi (Mass Assignment)
    protected $guarded = ['id'];

    // Relasi ke Maskapai
    public function airline() {
        return $this->belongsTo(Airline::class);
    }

    // Relasi ke Pesawat
    public function airplane() {
        return $this->belongsTo(Airplane::class);
    }

    // Relasi ke Bandara Keberangkatan
    public function departureAirport() {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    // Relasi ke Bandara Kedatangan
    public function arrivalAirport() {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }
}