@extends('layouts.staff')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>Kelola Pesawat</h3>
        <a href="{{ route('staff.airplanes.create') }}" class="btn btn-primary">+ Tambah Pesawat</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Maskapai</th>
                <th>Model Pesawat</th>
                <th>No. Registrasi</th>
                <th>Kapasitas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($airplanes as $index => $airplane)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><span class="badge bg-secondary">{{ $airplane->airline->name }}</span></td>
                <td>{{ $airplane->model }}</td>
                <td>{{ $airplane->registration_number }}</td>
                <td>{{ $airplane->capacity }} Kursi</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection