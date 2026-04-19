<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Models\Airplane;
use Illuminate\Http\Request;

class staffAirplaneController extends Controller
{
    // Menampilkan daftar pesawat
    public function index()
    {
        // Menggunakan 'with' untuk memuat data relasi maskapai (Eager Loading) agar query lebih cepat
        $airplanes = Airplane::with('airline')->latest()->get();
        return view('staff.airplanes.index', compact('airplanes'));
    }

    // Menampilkan form tambah pesawat
    public function create()
    {
        // Ambil semua data maskapai untuk ditampilkan di menu dropdown
        $airlines = Airline::all();
        return view('staff.airplanes.create', compact('airlines'));
    }

    // Menyimpan data pesawat
    public function store(Request $request)
    {
        $request->validate([
            'airline_id'          => 'required|exists:airlines,id', // Pastikan ID maskapai valid
            'model'               => 'required|string|max:255',
            'registration_number' => 'required|string|max:255|unique:airplanes,registration_number',
            'capacity'            => 'required|integer|min:1',
            'description'         => 'nullable|string'
        ]);

        Airplane::create($request->all());

        return redirect()->route('staff.airplanes.index')->with('success', 'Data Pesawat berhasil ditambahkan!');
    }
}