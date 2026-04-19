<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    // Mengizinkan kolom-kolom ini diisi data dari form
    protected $fillable = [
        'name',
        'city',
        'country',
        'iata_code'
    ];
}
