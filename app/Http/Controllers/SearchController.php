<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // 1. Validasi dasar (Jika user iseng buka URL /search tanpa isi form, kembalikan ke home)
        if (!$request->has('asal') || !$request->has('tujuan')) {
            return redirect()->route('home');
        }

        // 2. Tangkap semua data dari form pencarian (home.blade.php)
        $asal          = $request->asal;
        $tujuan        = $request->tujuan;
        $tanggal_pergi = $request->tanggal_pergi;
        
        // Tangkap data penumpang (berikan nilai default jika kosong)
        $dewasa = $request->penumpang_dewasa ?? 1;
        $anak   = $request->penumpang_anak ?? 0;
        $bayi   = $request->penumpang_bayi ?? 0;
        $kelas  = $request->kelas_penerbangan ?? 'Economy';

        // 3. Query Database: Cari jadwal yang cocok
        // Menggunakan with() agar tabel relasi (maskapai & bandara) ikut terpanggil (Eager Loading)
        $flights = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->where('departure_airport_id', $asal)
            ->where('arrival_airport_id', $tujuan)
            ->whereDate('departure_time', $tanggal_pergi) // whereDate mengabaikan jam, hanya mencocokkan tanggal
            ->orderBy('price', 'asc') // Urutkan dari harga termurah
            ->get();

        // 4. Ambil informasi nama Bandara Asal dan Tujuan untuk judul halaman
        $bandaraAsal   = Airport::find($asal);
        $bandaraTujuan = Airport::find($tujuan);

        // 5. Lempar semua data tersebut ke file tampilan (search.blade.php)
        return view('user.search', compact(
            'flights', 
            'request', 
            'bandaraAsal', 
            'bandaraTujuan', 
            'dewasa', 
            'anak', 
            'bayi', 
            'kelas'
        ));
    }
}