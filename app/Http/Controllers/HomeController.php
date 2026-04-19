<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airport;
use App\Models\LandingContent; // Pastikan model ini di-import

class HomeController extends Controller
{
    public function index()
    {
        $airports = Airport::all();
        
        // Ambil data untuk CMS. Jika belum ada tabelnya (error), lewati dulu agar web tidak crash
        $contents = [];
        try {
            $contents = LandingContent::all()->keyBy('section');
        } catch (\Exception $e) {
            // Biarkan kosong jika tabel belum di-migrate
        }

        return view('home', compact('airports', 'contents'));
    }
}