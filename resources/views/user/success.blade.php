<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Flight Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    {{-- Manggil script Midtrans (Snap) --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>

    <style>
        body { background-color: #f5f6fa; }
        .invoice-card { background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); padding: 30px; margin-top: 30px; }
        .booking-code-box { background-color: #f8f9fa; padding: 15px; border-radius: 8px; border: 2px dashed #dee2e6; text-align: center; }
        .flight-detail { border-left: 4px solid #0d6efd; padding-left: 15px; background-color: #f8f9fa; padding: 15px; border-radius: 0 8px 8px 0; }
        .passenger-list .list-group-item { border: none; border-bottom: 1px solid #eee; padding: 15px 0; }
        .passenger-list .list-group-item:last-child { border-bottom: none; }
        
        /* Animasi biar tombol bayar nyala-nyala dikit */
        .btn-bayar-sekarang {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
            100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
        }
    </style>
</head>
<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-bold mb-0"><i class="fas fa-ticket-alt me-2 text-primary"></i> Detail Pesanan</h3>
                    <a href="{{ route('booking.history') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Riwayat
                    </a>
                </div>

                <div class="invoice-card">
                    
                    {{-- 1. HEADER STATUS & KODE BOOKING --}}
                    <div class="row align-items-center mb-4">
                        <div class="col-md-6">
                            @if($transaction->status == 'Paid')
                                <span class="badge bg-success fs-6 py-2 px-3 mb-2"><i class="fas fa-check-circle me-1"></i> Pembayaran Lunas</span>
                            @else
                                <span class="badge bg-warning text-dark fs-6 py-2 px-3 mb-2"><i class="fas fa-clock me-1"></i> Menunggu Pembayaran</span>
                            @endif
                            <p class="text-muted small mb-0">Dipesan pada: {{ $transaction->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <div class="booking-code-box">
                                <span class="d-block text-muted small fw-bold">KODE BOOKING</span>
                                <h3 class="fw-bold text-primary mb-0 tracking-widest">{{ $transaction->booking_code }}</h3>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- 2. DETAIL PENERBANGAN --}}
                    <h5 class="fw-bold mt-4 mb-3">Informasi Penerbangan</h5>
                    <div class="flight-detail mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold fs-5">{{ $transaction->flight->airline->name }}</span>
                            <span class="badge bg-secondary">Kode Penerbangan: {{ $transaction->flight->flight_code ?? 'TBA' }}</span>
                        </div>
                        <div class="row text-center mt-3">
                            <div class="col-5">
                                <h5 class="fw-bold mb-0">{{ $transaction->flight->departureAirport->iata_code ?? '' }}</h5>
                                <p class="text-muted small mb-0">{{ $transaction->flight->departureAirport->city }}</p>
                            </div>
                            <div class="col-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-plane text-primary fs-4"></i>
                            </div>
                            <div class="col-5">
                                <h5 class="fw-bold mb-0">{{ $transaction->flight->arrivalAirport->iata_code ?? '' }}</h5>
                                <p class="text-muted small mb-0">{{ $transaction->flight->arrivalAirport->city }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- 3. DAFTAR NAMA PENUMPANG --}}
                    <h5 class="fw-bold mt-4 mb-3">Daftar Penumpang</h5>
                    <ul class="list-group passenger-list mb-4">
                        @foreach($transaction->passengers as $pax)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-user-circle text-secondary fs-4 me-2 align-middle"></i>
                                    <span class="fw-bold">{{ $pax->title ?? '' }} {{ $pax->name }}</span>
                                    <span class="badge bg-light text-dark border ms-2">{{ $pax->type }}</span>
                                </div>
                                <div>
                                    @if($pax->seat_number)
                                        <span class="badge bg-primary fs-6 px-3 py-2">Kursi: {{ $pax->seat_number }}</span>
                                    @else
                                        <span class="badge bg-secondary fs-6 px-3 py-2">Belum Pilih Kursi</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <hr>

                    {{-- 4. TOTAL HARGA & KONTAK --}}
                    <div class="row align-items-center mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Kontak Pemesan:</p>
                            <p class="fw-bold mb-0">{{ $transaction->contact_name }}</p>
                            <p class="text-muted small mb-0">{{ $transaction->contact_email }} | {{ $transaction->contact_phone }}</p>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <span class="text-muted d-block">Total Tagihan:</span>
                            <h3 class="fw-bold text-primary mb-0">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</h3>
                        </div>
                    </div>

                    {{-- 5. TOMBOL AKSI --}}
                    <div class="bg-light p-3 rounded text-center">
                        @if($transaction->status == 'Unpaid')
                            <div class="alert alert-warning small mb-3">
                                <i class="fas fa-exclamation-triangle"></i> Selesaikan pembayaran Anda sebelum batas waktu habis untuk mengamankan pesanan.
                            </div>
                            
                            {{-- INI DIA TOMBOL BAYARNYA BOSKU --}}
                            <div class="row g-2">
                                <div class="col-md-8">
                                    @if($transaction->snap_token)
                                        <button class="btn btn-primary w-100 fw-bold py-3 btn-bayar-sekarang" id="pay-button">
                                            <i class="fas fa-wallet me-2"></i> BAYAR SEKARANG
                                        </button>
                                    @else
                                        <div class="alert alert-danger mb-0">Snap Token tidak ditemukan. Silakan pesan ulang.</div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 fw-bold py-3">Pesan Ulang</a>
                                </div>
                            </div>
                            
                        @elseif($transaction->status == 'Paid')
                            
                            @if(is_null($transaction->passengers->first()->seat_number))
                                <a href="{{ route('booking.seats', $transaction->booking_code) }}" class="btn btn-primary w-100 fw-bold py-2 mb-2">
                                    <i class="fas fa-chair me-2"></i> Pilih Kursi Sekarang
                                </a>
                            @endif
                            
                            <a href="{{ route('booking.ticket', $transaction->booking_code) }}" class="btn btn-success w-100 fw-bold py-2">
                                <i class="fas fa-download me-2"></i> Unduh E-Tiket (PDF)
                            </a>
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPT UNTUK MANGGIL POP-UP MIDTRANS KETIKA TOMBOL BAYAR DIKLIK --}}
    @if($transaction->status == 'Unpaid' && $transaction->snap_token)
    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        if (payButton) {
            payButton.addEventListener('click', function () {
                window.snap.pay('{{ $transaction->snap_token }}', {
                    onSuccess: function(result){
                        alert("Pembayaran berhasil!");
                        window.location.href = "{{ route('payment.success_callback', $transaction->booking_code) }}";
                    },
                    onPending: function(result){
                        alert("Menunggu pembayaran...");
                        location.reload();
                    },
                    onError: function(result){
                        alert("Pembayaran gagal!");
                    },
                    onClose: function(){
                        alert('Anda menutup pop-up sebelum menyelesaikan pembayaran.');
                    }
                });
            });
        }
    </script>
    @endif

</body>
</html>