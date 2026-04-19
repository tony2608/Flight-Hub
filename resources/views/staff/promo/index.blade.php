@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Manajemen Promo</h1>
        <a href="{{ route('staff.promos.create') }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="fas fa-plus me-2"></i> Tambah Promo Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4 border-0" style="border-radius: 15px;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>Gambar</th>
                            <th>Judul Promo</th>
                            <th>Label/Badge</th>
                            <th>Kode Promo</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promos as $promo)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $promo->image) }}" class="rounded shadow-sm" style="width: 80px; height: 50px; object-fit: cover;">
                                </td>
                                <td class="fw-bold">{{ $promo->title }}</td>
                                <td><span class="badge bg-danger">{{ $promo->badge }}</span></td>
                                <td>
                                    {{-- INI YANG DIGANTI BOSKU --}}
                                    @if($promo->code)
                                        <span class="badge border border-primary text-primary">{{ $promo->code }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('staff.promos.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus promo ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm rounded-3"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada promo yang ditambahkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection