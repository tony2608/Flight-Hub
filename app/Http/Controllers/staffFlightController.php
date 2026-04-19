<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Airline;
use App\Models\Airplane;
use App\Models\Airport;
use Illuminate\Http\Request;

class staffFlightController extends Controller
{
    public function index()
    {
        // Memuat semua relasi agar tidak query berulang-ulang (N+1 Problem)
        $flights = Flight::with(['airline', 'airplane', 'departureAirport', 'arrivalAirport'])
                         ->latest()
                         ->get();
                         
        return view('staff.flights.index', compact('flights'));
    }

    public function create()
    {
        // Mengirim semua data master ke form
        $airlines = Airline::all();
        $airplanes = Airplane::all();
        $airports = Airport::all();
        
        return view('staff.flights.create', compact('airlines', 'airplanes', 'airports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'airline_id'           => 'required|exists:airlines,id',
            'airplane_id'          => 'required|exists:airplanes,id',
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id'   => 'required|exists:airports,id|different:departure_airport_id', // Tidak boleh sama dengan rute asal
            'flight_number'        => 'required|string|unique:flights,flight_number',
            'departure_time'       => 'required|date',
            'arrival_time'         => 'required|date|after:departure_time', // Harus setelah jam berangkat
            'price'                => 'required|numeric|min:0',
            'available_seats'      => 'required|integer|min:1',
        ]);

        Flight::create($request->all());

        return redirect()->route('staff.flights.index')->with('success', 'Jadwal penerbangan berhasil ditambahkan!');
    }
}