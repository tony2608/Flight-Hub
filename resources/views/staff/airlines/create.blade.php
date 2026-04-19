@extends('layouts.staff')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0 text-white">Tambah Maskapai Baru</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('staff.airlines.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group mb-3">
                        <label class="fw-bold">Nama Maskapai <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Garuda Indonesia" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold">Kode Maskapai <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" placeholder="Contoh: GA" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold">Logo Maskapai</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Format gambar: JPG, PNG, JPEG. Maks 2MB.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Penjelasan singkat tentang maskapai..."></textarea>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.airlines.index') }}" class="btn btn-danger px-4">Batal</a>
                        <button type="submit" class="btn btn-success px-4">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection