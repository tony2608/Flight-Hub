@extends('layouts.staff')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Daftar Maskapai</h4>
                <a href="{{ route('staff.airlines.create') }}" class="btn btn-primary btn-round ms-auto">
                    <i class="fa fa-plus"></i> Tambah Maskapai
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Logo</th>
                                <th>Kode</th>
                                <th>Nama Maskapai</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($airlines as $index => $al)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($al->logo)
                                        <img src="{{ asset('storage/' . $al->logo) }}" alt="Logo" width="50" class="img-thumbnail">
                                    @else
                                        <span class="text-muted">Tidak ada logo</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-primary">{{ $al->code }}</span></td>
                                <td><strong>{{ $al->name }}</strong></td>
                                <td>{{ \Illuminate\Support\Str::limit($al->description, 50) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data maskapai.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection