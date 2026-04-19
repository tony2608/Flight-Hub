@extends('layouts.staff')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>Kelola Jadwal Penerbangan</h3>
        <a href="{{ route('staff.flights.create') }}" class="btn btn-primary">+ Tambah Jadwal</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>No. Penerbangan</th>
                <th>Maskapai & Pesawat</th>
                <th>Rute (Asal ➔ Tujuan)</th>
                <th>Waktu Berangkat</th>
                <th>Harga & Kuota</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flights as $flight)
            <tr>
                <td><strong>{{ $flight->flight_number }}</strong></td>
                <td>
                    {{ $flight->airline->name }} <br>
                    <small class="text-muted">{{ $flight->airplane->model }}</small>
                </td>
                <td>
                    {{ $flight->departureAirport->iata_code }} ➔ {{ $flight->arrivalAirport->iata_code }} <br>
                    <small>({{ $flight->departureAirport->city }} ke {{ $flight->arrivalAirport->city }})</small>
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($flight->departure_time)->format('d M Y, H:i') }}
                </td>
                <td>
                    Rp {{ number_format($flight->price, 0, ',', '.') }} <br>
                    <span class="badge bg-success">{{ $flight->available_seats }} Sisa</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection