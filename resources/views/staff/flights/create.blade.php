@extends('layouts.staff')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Jadwal Penerbangan</h5>
        </div>
        <div class="card-body">
            
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <strong>Gagal Menyimpan Data!</strong> Cek kesalahan berikut:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('staff.flights.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nomor Penerbangan</label>
                        <input type="text" name="flight_number" class="form-control" value="{{ old('flight_number') }}" placeholder="Contoh: JT-610" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Maskapai</label>
                        <select name="airline_id" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @foreach($airlines as $al)
                                <option value="{{ $al->id }}" {{ old('airline_id') == $al->id ? 'selected' : '' }}>{{ $al->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Pesawat</label>
                        <select name="airplane_id" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @foreach($airplanes as $ap)
                                <option value="{{ $ap->id }}" {{ old('airplane_id') == $ap->id ? 'selected' : '' }}>{{ $ap->model }} ({{ $ap->registration_number }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Bandara Keberangkatan (Asal)</label>
                        <select name="departure_airport_id" class="form-control" required>
                            <option value="">-- Pilih Asal --</option>
                            @foreach($airports as $ap)
                                <option value="{{ $ap->id }}" {{ old('departure_airport_id') == $ap->id ? 'selected' : '' }}>{{ $ap->city }} - {{ $ap->name }} ({{ $ap->iata_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Bandara Kedatangan (Tujuan)</label>
                        <select name="arrival_airport_id" class="form-control" required>
                            <option value="">-- Pilih Tujuan --</option>
                            @foreach($airports as $ap)
                                <option value="{{ $ap->id }}" {{ old('arrival_airport_id') == $ap->id ? 'selected' : '' }}>{{ $ap->city }} - {{ $ap->name }} ({{ $ap->iata_code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Waktu Berangkat</label>
                        <input type="datetime-local" name="departure_time" class="form-control" value="{{ old('departure_time') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Waktu Tiba</label>
                        <input type="datetime-local" name="arrival_time" class="form-control" value="{{ old('arrival_time') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Harga Tiket (Rp)</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Kursi Tersedia</label>
                        <input type="number" name="available_seats" class="form-control" value="{{ old('available_seats') }}" required>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-success px-4">Terbitkan Jadwal</button>
            </form>
        </div>
    </div>
</div>
@endsection