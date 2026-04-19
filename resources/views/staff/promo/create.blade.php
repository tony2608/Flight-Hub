@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Tambah Promo Baru</h1>
        <a href="{{ route('staff.promos.index') }}" class="btn btn-outline-secondary fw-bold shadow-sm">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4 border-0" style="border-radius: 15px;">
        <div class="card-body bg-light" style="border-radius: 15px;">
            <form action="{{ route('staff.promos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row bg-white p-4 rounded shadow-sm">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold small">Judul Promo (Destinasi)</label>
                        <input type="text" name="title" class="form-control" placeholder="Cth: Terbang ke Bali" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold small">Label/Badge</label>
                        <input type="text" name="badge" class="form-control" placeholder="Cth: Diskon 20% atau Cashback 50rb" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold small">Deskripsi Singkat</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Cth: Nikmati liburan murah ke Bali akhir pekan ini..." required></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold small">Kode Promo <span class="text-muted fw-normal">(Opsional)</span></label>
                        <input type="text" name="promo_code" class="form-control text-uppercase" placeholder="Cth: BALIMURAH">
                        <small class="text-muted">Isi jika user butuh kode pas checkout.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold small text-danger">Upload Gambar Promo</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <small class="text-muted">Resolusi disarankan: Landscape (Lebar).</small>
                    </div>
                    <div class="col-md-12 text-end mt-3">
                        <button type="submit" class="btn btn-primary px-5 fw-bold rounded-pill">Simpan Promo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection