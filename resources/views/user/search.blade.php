<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian Tiket - Flight Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background-color: #f5f7fa; }
        
        /* Header Biru di Atas */
        .search-header {
            background-color: #0194f3;
            color: white;
            padding: 20px 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        /* Desain Kotak Tiket (Card) */
        .flight-card {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .flight-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .airline-logo {
            max-width: 60px;
            max-height: 40px;
            object-fit: contain;
        }
        
        /* Garis Waktu Penerbangan */
        .flight-timeline {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: center;
        }
        .time-box h4 { font-weight: bold; margin: 0; color: #333; }
        .time-box p { margin: 0; color: #888; font-size: 0.85rem; }
        
        .timeline-line {
            flex-grow: 1;
            height: 2px;
            background-color: #ddd;
            margin: 0 20px;
            position: relative;
        }
        .timeline-line i {
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            color: #0194f3;
            background: white;
            padding: 0 10px;
        }

        .btn-pilih {
            background-color: #ff5e1f;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            padding: 10px 30px;
        }
        .btn-pilih:hover { background-color: #e04a11; color: white; }
    </style>
</head>

{{-- TAMBAHKAN CLASS d-flex flex-column min-vh-100 DI BODY --}}
<body class="d-flex flex-column min-vh-100">

    {{-- HEADER --}}
    <div class="search-header mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold">
                    {{ $bandaraAsal->city ?? 'Asal' }} ({{ $bandaraAsal->iata_code ?? 'IATA' }}) 
                    <i class="fas fa-arrow-right mx-2"></i> 
                    {{ $bandaraTujuan->city ?? 'Tujuan' }} ({{ $bandaraTujuan->iata_code ?? 'IATA' }})
                </h4>
                <p class="mb-0 fs-6">
                    <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($request->tanggal_pergi ?? now())->translatedFormat('l, d F Y') }} | 
                    <i class="fas fa-users mx-1"></i> {{ $dewasa ?? 0 }} Dewasa, {{ $anak ?? 0 }} Anak, {{ $bayi ?? 0 }} Bayi | 
                    <i class="fas fa-chair mx-1"></i> Kelas {{ $kelas ?? 'Economy' }}
                </p>
            </div>
            <div>
                <a href="{{ route('home') }}" class="btn btn-outline-light fw-bold rounded-pill px-4">Ubah Pencarian</a>
            </div>
        </div>
    </div>

    {{-- KONTEN UTAMA (Tambahkan flex-grow-1 agar sisa layarnya mendorong footer ke bawah) --}}
    <div class="flex-grow-1">
        <div class="container">
            <div class="row">
                <div class="col-md-9 mx-auto">
                    
                    @if(isset($flights) && $flights->isEmpty())
                        <div class="alert alert-warning text-center p-5 rounded-4 shadow-sm bg-white border-0">
                            <i class="fas fa-plane-slash fa-4x text-muted mb-3"></i>
                            <h4 class="fw-bold">Waduh, Penerbangan Tidak Ditemukan!</h4>
                            <p class="mb-0 text-muted">Coba cari tanggal lain atau rute yang berbeda.</p>
                            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Kembali ke Beranda</a>
                        </div>
                    @elseif(isset($flights))
                        
                        <p class="text-muted fw-bold mb-3">Menampilkan {{ $flights->count() }} penerbangan terbaik</p>
                        
                        @foreach($flights as $flight)
                            @php
                                // LOGIKA KALKULATOR HARGA
                                $hargaDasar = $flight->price;
                                
                                // Pengali Kelas (Business lebih mahal 2x, First Class 3x)
                                $multiplierKelas = ($kelas == 'Business') ? 2 : (($kelas == 'First') ? 3 : 1);
                                
                                // Harga per kategori
                                $hargaDewasa = $hargaDasar * $multiplierKelas;
                                $hargaAnak   = $hargaDewasa * 0.8; // Anak diskon 20%
                                $hargaBayi   = $hargaDewasa * 0.1; // Bayi bayar 10%

                                // Total Keseluruhan
                                $totalHarga = ($hargaDewasa * $dewasa) + ($hargaAnak * $anak) + ($hargaBayi * $bayi);
                            @endphp

                            <div class="card flight-card">
                                <div class="card-body p-4">
                                    <div class="row align-items-center">
                                        
                                        <div class="col-md-3 text-center text-md-start mb-3 mb-md-0">
                                            @if($flight->airline && $flight->airline->logo)
                                                <img src="{{ asset('storage/' . $flight->airline->logo) }}" alt="Logo" class="airline-logo mb-2">
                                            @else
                                                <i class="fas fa-plane fa-2x text-primary mb-2"></i>
                                            @endif
                                            <h6 class="fw-bold mb-0">{{ $flight->airline->name ?? 'Unknown' }}</h6>
                                            <small class="text-muted">{{ $flight->airplane->model ?? '' }} • {{ $flight->flight_number ?? '' }}</small>
                                        </div>

                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <div class="flight-timeline">
                                                <div class="time-box text-end">
                                                    <h4>{{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }}</h4>
                                                    <p>{{ $flight->departureAirport->iata_code ?? '' }}</p>
                                                </div>
                                                
                                                <div class="timeline-line">
                                                    <i class="fas fa-plane"></i>
                                                </div>
                                                
                                                <div class="time-box text-start">
                                                    <h4>{{ \Carbon\Carbon::parse($flight->arrival_time)->format('H:i') }}</h4>
                                                    <p>{{ $flight->arrivalAirport->iata_code ?? '' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 text-center text-md-end border-start-md ps-md-4">
                                            <p class="text-muted mb-1" style="font-size: 13px;">Total Harga</p>
                                            <h4 class="fw-bold text-primary mb-3">Rp {{ number_format($totalHarga, 0, ',', '.') }}</h4>
                                            
                                            <a href="{{ route('booking.create', [
                                                'flight_id' => $flight->id, 
                                                'dewasa' => $dewasa, 
                                                'anak' => $anak, 
                                                'bayi' => $bayi, 
                                                'kelas' => $kelas, 
                                                'total_harga' => $totalHarga
                                            ]) }}" class="btn btn-pilih w-100">Pilih Tiket</a>
                                            
                                            <p class="text-success mt-2 mb-0" style="font-size: 12px;"><i class="fas fa-check-circle"></i> Sisa {{ $flight->available_seats }} kursi</p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="pt-5 pb-3 mt-auto" style="background-color: #1a1e29; color: #a1a1a8;">
        <div class="container">
            <div class="row mb-4">
                {{-- Kolom 1 --}}
                <div class="col-md-4 mb-4">
                    <h5 class="text-white fw-bold mb-3"><i class="fas fa-plane-departure me-2"></i>Flight Hub</h5>
                    <p class="small" style="line-height: 1.8;">Platform pemesanan tiket pesawat terpercaya di Indonesia. Kami siap membawa Anda terbang ke berbagai destinasi impian di seluruh dunia.</p>
                </div>
                {{-- Kolom 2 --}}
                <div class="col-md-4 mb-4">
                    <h5 class="text-white fw-bold mb-3">Layanan Kami</h5>
                    <ul class="list-unstyled small" style="line-height: 2;">
                        <li><a href="#pencarian" class="text-decoration-none" style="color: #a1a1a8;">Pesan Tiket</a></li>
                        <li><a href="#" class="text-decoration-none" style="color: #a1a1a8;">Cek Pesanan</a></li>
                        <li><a href="#" class="text-decoration-none" style="color: #a1a1a8;">Cara Pembayaran</a></li>
                        <li><a href="#" class="text-decoration-none" style="color: #a1a1a8;">Bantuan & FAQ</a></li>
                    </ul>
                </div>
                
                {{-- Kolom 3 (Diubah jadi tombol Pop-up) --}}
                <div class="col-md-4 mb-4">
                    <h5 class="text-white fw-bold mb-3">Hubungi Kami</h5>
                    <p class="small mb-3" style="color: #a1a1a8; line-height: 1.8;">Punya pertanyaan atau butuh bantuan? Jangan ragu untuk menghubungi tim dukungan kami.</p>
                    
                    {{-- Tombol Pemicu Pop-Up --}}
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#contactModal">
                        <i class="fas fa-headset me-2"></i> Lihat Kontak
                    </button>
                </div>
            </div>
            <hr style="border-color: #2e3344;">
            <div class="text-center small mt-3">
                &copy; {{ date('Y') }} Flight Hub. All rights reserved. Build by Fathoni.
            </div>
        </div>
    </footer>

    {{-- ========================================== --}}
    {{-- MODAL / POP-UP HUBUNGI KAMI --}}
    {{-- ========================================== --}}
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg" style="border-radius: 15px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="contactModalLabel">Kontak Flight Hub</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pb-4 pt-3">
                    <p class="text-muted small mb-4">Pilih saluran komunikasi di bawah ini untuk terhubung langsung dengan tim dukungan kami.</p>
                    
                    <div class="d-grid gap-3 px-3">
                        {{-- Tombol WhatsApp --}}
                        <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-outline-success d-flex align-items-center justify-content-start py-2 px-3" style="border-radius: 10px;">
                            <i class="fab fa-whatsapp fs-3 me-3"></i> 
                            <div class="text-start">
                                <div class="fw-bold">WhatsApp</div>
                                <small>+62 812-3456-7890</small>
                            </div>
                        </a>
                        
                        {{-- Tombol Instagram --}}
                        <a href="https://instagram.com/flighthub_id" target="_blank" class="btn btn-outline-danger d-flex align-items-center justify-content-start py-2 px-3" style="border-radius: 10px;">
                            <i class="fab fa-instagram fs-3 me-3"></i> 
                            <div class="text-start">
                                <div class="fw-bold">Instagram</div>
                                <small>@flighthub_id</small>
                            </div>
                        </a>

                        {{-- Tombol Email --}}
                        <a href="mailto:flighthub@gmail.com" class="btn btn-outline-primary d-flex align-items-center justify-content-start py-2 px-3" style="border-radius: 10px;">
                            <i class="fas fa-envelope fs-3 me-3"></i> 
                            <div class="text-start">
                                <div class="fw-bold">Email</div>
                                <small>flighthub@gmail.com</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KARENA DI HALAMAN INI ADA MODAL, HARUS LOAD SCRIPT BOOTSTRAP --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>