<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;

class staffAirportController extends Controller
{
    // Menampilkan daftar bandara
    public function index()
    {
        $airports = Airport::latest()->get();
        return view('staff.airports.index', compact('airports'));
    }

    // Menampilkan form tambah bandara
    public function create()
    {
        return view('staff.airports.create');
    }

    // Menyimpan data ke database
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'city'      => 'required|string|max:255',
            'country'   => 'required|string|max:255',
            'iata_code' => 'required|string|max:5|unique:airports,iata_code',
        ]);

        Airport::create($request->all());

        return redirect()->route('staff.airports.index')
                         ->with('success', 'Bandara berhasil ditambahkan!');
    }

    // Menghapus data
    public function destroy(Airport $airport)
    {
        $airport->delete();
        return redirect()->back()->with('success', 'Bandara berhasil dihapus!');
    }
}