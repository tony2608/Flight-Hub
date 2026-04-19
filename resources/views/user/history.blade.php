<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Flight Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f5f6fa; }
        .history-card { background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); padding: 25px; margin-top: 30px; }
        .badge-status { padding: 6px 12px; border-radius: 20px; font-weight: bold; font-size: 0.85rem; }
        .table thead { background-color: #f8f9fa; }
    </style>
</head>
<body>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold"><i class="fas fa-history me-2 text-primary"></i> Riwayat Pesanan</h3>
            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">Cari Tiket Lagi</a>
        </div>

        {{-- Notifikasi Sukses Batal --}}
        @if(session('success'))
            <div class="alert alert-success fw-bold"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>
        @endif

        <div class="history-card">
            @if($transactions->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Kamu belum punya riwayat pesanan tiket.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Booking</th>
                                <th>Maskapai / Rute</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $trx)
                                <tr>
                                    {{-- KOLOM 1: TANGGAL --}}
                                    <td class="small text-muted">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                                    
                                    {{-- KOLOM 2: KODE --}}
                                    <td class="fw-bold text-primary">{{ $trx->booking_code }}</td>
                                    
                                    {{-- KOLOM 3: MASKAPAI & RUTE --}}
                                    <td>
                                        <div class="fw-bold">{{ $trx->flight->airline->name }}</div>
                                        <div class="small text-muted">
                                            {{ $trx->flight->departureAirport->city }} <i class="fas fa-arrow-right mx-1"></i> {{ $trx->flight->arrivalAirport->city }}
                                        </div>
                                    </td>
                                    
                                    {{-- KOLOM 4: HARGA --}}
                                    <td class="fw-bold">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                                    
                                    {{-- KOLOM 5: STATUS --}}
                                    <td>
                                        @if($trx->status == 'Paid')
                                            <span class="badge bg-success badge-status">Lunas</span>
                                        @elseif($trx->status == 'Unpaid')
                                            <span class="badge bg-warning text-dark badge-status">Menunggu</span>
                                        @elseif($trx->status == 'Pending_Cancel')
                                            <span class="badge bg-info text-dark badge-status">Proses Batal</span>
                                        @elseif($trx->status == 'Cancelled')
                                            <span class="badge bg-danger badge-status">Dibatalkan</span>
                                        @endif
                                    </td>
                                    
                                    {{-- KOLOM 6: AKSI & MODAL --}}
                                    <td>
                                        <a href="{{ route('booking.success', $trx->booking_code) }}" class="btn btn-sm btn-light border shadow-sm mb-1">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                        
                                        {{-- Tombol Batal hanya muncul kalau belum dicancel --}}
                                        @if($trx->status == 'Unpaid' || $trx->status == 'Paid')
                                            <button type="button" class="btn btn-sm btn-outline-danger shadow-sm mb-1" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $trx->booking_code }}">
                                                <i class="fas fa-times me-1"></i> Batal
                                            </button>

                                            {{-- Modal Input Alasan Cancel --}}
                                            <div class="modal fade" id="cancelModal{{ $trx->booking_code }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Batalkan Pesanan</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('booking.cancel', $trx->booking_code) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body text-start">
                                                                <p>Anda yakin ingin membatalkan pesanan tiket <strong>{{ $trx->flight->airline->name }}</strong> ({{ $trx->booking_code }})?</p>
                                                                
                                                                @if($trx->status == 'Paid')
                                                                    <div class="alert alert-warning small">
                                                                        Pesanan ini sudah lunas. Permintaan pembatalan akan diteruskan ke pihak manajemen untuk proses kompensasi.
                                                                    </div>
                                                                @endif

                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold">Alasan Pembatalan <span class="text-danger">*</span></label>
                                                                    <textarea name="cancel_reason" class="form-control" rows="3" placeholder="Contoh: Salah jadwal, sakit, urusan mendadak..." required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-danger">Kirim Pembatalan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Script wajib biar modal Pop-up nya mau kebuka pas diklik --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>