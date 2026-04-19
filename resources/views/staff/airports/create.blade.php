@extends('layouts.staff')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Bandara Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.airports.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nama Bandara</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Soekarno-Hatta" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Kode IATA</label>
                        <input type="text" name="iata_code" class="form-control" placeholder="Contoh: CGK" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Kota</label>
                        <input type="text" name="city" class="form-control" placeholder="Contoh: Jakarta" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Negara</label>
                        <input type="text" name="country" class="form-control" placeholder="Contoh: Indonesia" required>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-success px-4">Simpan Ke Database</button>
                <a href="{{ route('admin.airports.index') }}" class="btn btn-light">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection