<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class staffAirlineController extends Controller
{
    public function index() {
        $airlines = Airline::all();
        return view('staff.airlines.index', compact('airlines'));
    }

    public function create() {
        return view('staff.airlines.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'code' => 'required|max:10',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi gambar
        ]);

        $data = $request->all();

        // Logika Upload Logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->store('logos', 'public'); // Simpan di storage/app/public/logos
            $data['logo'] = $path;
        }

        Airline::create($data);

        return redirect()->route('staff.airlines.index')->with('success', 'Maskapai berhasil ditambah!');
    }
}
