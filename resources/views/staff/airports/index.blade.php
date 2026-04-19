@extends('layouts.staff')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Daftar Bandara</h4>
                <a href="{{ route('staff.airports.create') }}" class="btn btn-primary btn-round ms-auto">
                    <i class="fa fa-plus"></i> Tambah Bandara
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
                                <th>Kode IATA</th>
                                <th>Nama Bandara</th>
                                <th>Kota</th>
                                <th>Negara</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($airports as $index => $ap)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="badge badge-success">{{ $ap->iata_code }}</span></td>
                                <td>{{ $ap->name }}</td>
                                <td>{{ $ap->city }}</td>
                                <td>{{ $ap->country }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data bandara.</td>
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