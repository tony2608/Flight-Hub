@extends('layouts.staff')

@section('content')
<div class="row">
    <div class="col-md-12">
        
        @if(session('success'))
            <div class="alert alert-success fw-bold"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title fw-bold"><i class="fas fa-shopping-cart text-primary me-2"></i> Data Pesanan Masuk</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode Booking</th>
                                <th>Pemesan</th>
                                <th>Penerbangan</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $index => $trx)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center"><span class="badge bg-primary">{{ $trx->booking_code }}</span></td>
                                <td>
                                    <strong>{{ $trx->contact_name }}</strong><br>
                                    <small class="text-muted"><i class="fas fa-phone"></i> {{ $trx->contact_phone }}</small>
                                </td>
                                <td>
                                    <strong>{{ $trx->flight->airline->name ?? 'Maskapai' }}</strong> ({{ $trx->flight->flight_number ?? '-' }})<br>
                                    <small>{{ \Carbon\Carbon::parse($trx->flight->departure_time)->format('d M Y, H:i') }}</small>
                                </td>
                                <td class="text-end fw-bold text-success">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                                
                                <td class="text-center">
                                    @if($trx->status == 'Paid')
                                        <span class="badge bg-success"><i class="fas fa-check"></i> Lunas</span>
                                    @elseif($trx->status == 'Unpaid')
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Belum Bayar</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fas fa-times"></i> Batal</span>
                                    @endif

                                    @if($trx->payment_receipt)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $trx->payment_receipt) }}" target="_blank" class="btn btn-sm btn-info text-white fw-bold">
                                                <i class="fas fa-image"></i> Cek Bukti
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                
                                <td class="text-center">
                                    <form action="{{ route('staff.transactions.updateStatus', $trx->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group input-group-sm">
                                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="Unpaid" {{ $trx->status == 'Unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                                                <option value="Paid" {{ $trx->status == 'Paid' ? 'selected' : '' }}>Lunas</option>
                                                <option value="Cancelled" {{ $trx->status == 'Cancelled' ? 'selected' : '' }}>Batal</option>
                                            </select>
                                        </div>
                                    </form>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Belum ada pesanan tiket yang masuk.</td>
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