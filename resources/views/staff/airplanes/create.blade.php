@extends('layouts.staff')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Pesawat Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.airplanes.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Pilih Maskapai</label>
                        <select name="airline_id" class="form-control" required>
                            <option value="">-- Pilih Maskapai --</option>
                            @foreach($airlines as $airline)
                                <option value="{{ $airline->id }}">{{ $airline->name }} ({{ $airline->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Model Pesawat</label>
                        <input type="text" name="model" class="form-control" placeholder="Contoh: Boeing 737-800" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Nomor Registrasi</label>
                        <input type="text" name="registration_number" class="form-control" placeholder="Contoh: PK-LGP" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Kapasitas Kursi</label>
                        <input type="number" name="capacity" class="form-control" placeholder="Contoh: 180" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Deskripsi Tambahan (Opsional)</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <hr>
                <button type="submit" class="btn btn-success px-4">Simpan Pesawat</button>
                <a href="{{ route('admin.airplanes.index') }}" class="btn btn-light">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection