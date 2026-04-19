<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Hub - Tiket Pesawat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Efek meluncur mulus */
        html { scroll-behavior: smooth; }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)), url('https://alat-test.com/wp-content/uploads/2021/02/Pengertian-Pesawat-Terbang-dan-Sejarahnya-compressed.jpg') no-repeat center center/cover;
            min-height: 80vh;
            color: white;
            padding-top: 100px;
        }
        .search-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .search-container label { color: white; font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; }
        .form-control, .form-select { height: 50px; border-radius: 8px; border: none; font-weight: 500; }
        .btn-orange { background-color: #ff5e1f; color: white; height: 50px; border-radius: 8px; font-weight: bold; border: none; }
        .btn-orange:hover { background-color: #e04a11; color: white; }
        .passenger-btn { background: rgba(255, 255, 255, 0.2); color: white; border: 1px solid rgba(255, 255, 255, 0.3); text-align: left; display: flex; justify-content: space-between; align-items: center; cursor: pointer; }
        .passenger-popup { display: none; position: absolute; top: 100%; left: 15px; width: 320px; background: white; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); padding: 20px; z-index: 1000; margin-top: 5px; color: #333; }
        .passenger-popup.show { display: block; }
        .passenger-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .passenger-info h6 { margin: 0; font-weight: bold; font-size: 15px; }
        .passenger-info small { color: #888; font-size: 12px; }
        .counter-box { display: flex; align-items: center; gap: 10px; }
        .btn-counter { width: 32px; height: 32px; border-radius: 6px; border: 1px solid #0194f3; background: white; color: #0194f3; display: flex; justify-content: center; align-items: center; cursor: pointer; font-weight: bold; }
        .btn-counter.disabled { border-color: #ccc; color: #ccc; cursor: not-allowed; }
        .counter-val { width: 20px; text-align: center; font-weight: bold; border: none; background: transparent; }
        .top-options .form-select { background: rgba(255, 255, 255, 0.2); color: white; border: 1px solid rgba(255, 255, 255, 0.3); }
        .top-options .form-select option { color: black; }
    </style>
</head>

{{-- TAMBAHAN CLASS FLEXBOX DI BODY --}}
<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top pt-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="{{ route('home') }}">
                <i class="fas fa-dove"></i> Flight Hub
            </a>
            
            <div class="d-flex align-items-center">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light me-2 fw-bold border-0"><i class="fas fa-user"></i> Log In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary fw-bold rounded-pill px-4">Daftar</a>
                @else
                    <div class="dropdown">
                        <a class="btn btn-outline-light fw-bold dropdown-toggle border-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> Hai, {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('booking.history') }}">
                                    <i class="fas fa-receipt text-primary"></i> Riwayat Pesanan
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    {{-- BUNGKUS KONTEN UTAMA DENGAN FLEX-GROW --}}
    <main class="flex-grow-1">
        {{-- ID pencarian ditambahkan di sini untuk titik pendaratan scroll --}}
        <div id="pencarian" class="hero-section">
            <div class="container">
                <h2 class="text-center fw-bold mb-5">Pilihan utama untuk jelajahi dunia</h2>
                
                <form action="{{ route('search') }}" method="GET">
                    <div class="search-container">
                        
                        <div class="row mb-3 top-options align-items-center">
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="trip_type" id="oneway" value="oneway" checked onchange="toggleReturnDate()">
                                    <label class="form-check-label cursor-pointer" for="oneway">Sekali Jalan</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="trip_type" id="roundtrip" value="roundtrip" onchange="toggleReturnDate()">
                                    <label class="form-check-label cursor-pointer" for="roundtrip">Pulang Pergi</label>
                                </div>
                            </div>

                            <div class="col-md-5 position-relative">
                                <div class="form-control passenger-btn" id="btnPassenger" onclick="togglePassengerPopup()">
                                    <span><i class="fas fa-users me-2"></i> <span id="passengerSummary">1 Dewasa, 0 Anak, 0 Bayi</span></span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>

                                <div class="passenger-popup" id="passengerPopup">
                                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                        <h5 class="fw-bold mb-0">Jumlah Penumpang</h5>
                                        <i class="fas fa-times cursor-pointer text-muted" onclick="togglePassengerPopup()" style="cursor:pointer;"></i>
                                    </div>

                                    <div class="passenger-row">
                                        <div class="passenger-info">
                                            <h6><i class="fas fa-user text-primary me-1"></i> Dewasa</h6>
                                            <small>12 thn atau lebih</small>
                                        </div>
                                        <div class="counter-box">
                                            <button type="button" class="btn-counter disabled" id="btnMinDewasa" onclick="updateCount('dewasa', -1)">-</button>
                                            <input type="text" name="penumpang_dewasa" id="valDewasa" class="counter-val" value="1" readonly>
                                            <button type="button" class="btn-counter" onclick="updateCount('dewasa', 1)">+</button>
                                        </div>
                                    </div>

                                    <div class="passenger-row">
                                        <div class="passenger-info">
                                            <h6><i class="fas fa-child text-primary me-1"></i> Anak</h6>
                                            <small>2 - 11 thn</small>
                                        </div>
                                        <div class="counter-box">
                                            <button type="button" class="btn-counter disabled" id="btnMinAnak" onclick="updateCount('anak', -1)">-</button>
                                            <input type="text" name="penumpang_anak" id="valAnak" class="counter-val" value="0" readonly>
                                            <button type="button" class="btn-counter" onclick="updateCount('anak', 1)">+</button>
                                        </div>
                                    </div>

                                    <div class="passenger-row">
                                        <div class="passenger-info">
                                            <h6><i class="fas fa-baby text-primary me-1"></i> Bayi</h6>
                                            <small>Di bawah 2 tahun</small>
                                        </div>
                                        <div class="counter-box">
                                            <button type="button" class="btn-counter disabled" id="btnMinBayi" onclick="updateCount('bayi', -1)">-</button>
                                            <input type="text" name="penumpang_bayi" id="valBayi" class="counter-val" value="0" readonly>
                                            <button type="button" class="btn-counter" onclick="updateCount('bayi', 1)">+</button>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-primary w-100 fw-bold mt-2" onclick="togglePassengerPopup()">Selesai</button>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <select name="kelas_penerbangan" class="form-select" required>
                                    <option value="Economy">Economy</option>
                                    <option value="Business">Business</option>
                                    <option value="First">First Class</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label>Dari</label>
                                <select name="asal" class="form-select" required>
                                    <option value="">Pilih Asal</option>
                                    @if(isset($airports))
                                        @foreach($airports as $ap)
                                            <option value="{{ $ap->id }}">{{ $ap->city }} ({{ $ap->iata_code }})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Ke</label>
                                <select name="tujuan" class="form-select" required>
                                    <option value="">Pilih Tujuan</option>
                                    @if(isset($airports))
                                        @foreach($airports as $ap)
                                            <option value="{{ $ap->id }}">{{ $ap->city }} ({{ $ap->iata_code }})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Tanggal Pergi</label>
                                <input type="date" name="tanggal_pergi" class="form-control" required>
                            </div>

                            <div class="col-md-2" id="return_date_container" style="display: none;">
                                <label>Tanggal Pulang</label>
                                <input type="date" name="tanggal_pulang" id="tanggal_pulang" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-orange w-100">
                                    <i class="fas fa-search"></i> Cari Tiket
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- BAGIAN PROMO & DISKON (SEKARANG DINAMIS) --}}
        {{-- ========================================== --}}
        <section class="py-5" style="background-color: #f8f9fa;">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold">Promo Spesial Bulan Ini 🔥</h2>
                    <p class="text-muted">Dapatkan penawaran terbaik untuk destinasi impianmu</p>
                </div>
                
                <div class="row">
                    {{-- Card Promo 1 --}}
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; overflow: hidden;">
                            {{-- Cek apakah foto di-upload admin, kalau belum pakai gambar default --}}
                            <img src="{{ isset($contents['promo_1']) && $contents['promo_1']->image ? asset('storage/'.$contents['promo_1']->image) : 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=500&q=80' }}" class="card-img-top" alt="Promo 1" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                {{-- Label Diskon diambil dari button_text tabel --}}
                                <span class="badge bg-danger mb-2 px-3 py-2">{{ $contents['promo_1']->button_text ?? 'Diskon 20%' }}</span>
                                <h5 class="card-title fw-bold">{{ $contents['promo_1']->title ?? 'Terbang ke Bali' }}</h5>
                                <p class="card-text text-muted small">{{ $contents['promo_1']->description ?? 'Nikmati liburan di Pulau Dewata dengan harga spesial musim ini. Syarat dan ketentuan berlaku.' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card Promo 2 --}}
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; overflow: hidden;">
                            <img src="{{ isset($contents['promo_2']) && $contents['promo_2']->image ? asset('storage/'.$contents['promo_2']->image) : 'https://images.unsplash.com/photo-1540959733332-eab4ce049f1a?auto=format&fit=crop&w=500&q=80' }}" class="card-img-top" alt="Promo 2" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <span class="badge bg-warning text-dark mb-2 px-3 py-2">{{ $contents['promo_2']->button_text ?? 'Cashback Rp 500rb' }}</span>
                                <h5 class="card-title fw-bold">{{ $contents['promo_2']->title ?? 'Eksplorasi Tokyo' }}</h5>
                                <p class="card-text text-muted small">{{ $contents['promo_2']->description ?? 'Merasakan budaya dan teknologi Jepang kini lebih hemat dengan promo spesial dari Flight Hub.' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card Promo 3 --}}
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; overflow: hidden;">
                            <img src="{{ isset($contents['promo_3']) && $contents['promo_3']->image ? asset('storage/'.$contents['promo_3']->image) : 'https://images.unsplash.com/photo-1499856871958-5b9627545d1a?auto=format&fit=crop&w=500&q=80' }}" class="card-img-top" alt="Promo 3" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <span class="badge bg-success mb-2 px-3 py-2">{{ $contents['promo_3']->button_text ?? 'Beli 1 Gratis 1' }}</span>
                                <h5 class="card-title fw-bold">{{ $contents['promo_3']->title ?? 'Romantisme Paris' }}</h5>
                                <p class="card-text text-muted small">{{ $contents['promo_3']->description ?? 'Wujudkan mimpimu melihat Menara Eiffel secara langsung. Promo terbatas hanya minggu ini!' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- BAGIAN TENTANG KAMI SLIDER (SEKARANG DINAMIS) --}}
        {{-- ========================================== --}}
        <section class="py-5 bg-white">
            <div class="container py-4">
                
                <div id="aboutCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        
                        {{-- SLIDE 1 --}}
                        <div class="carousel-item active" data-bs-interval="4000">
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-4 mb-md-0">
                                    <img src="{{ isset($contents['slide_1']) && $contents['slide_1']->image ? asset('storage/'.$contents['slide_1']->image) : 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=600&q=80' }}" class="img-fluid shadow" alt="Slide 1" style="border-radius: 20px; height: 350px; width: 100%; object-fit: cover;">
                                </div>
                                <div class="col-md-6 ps-md-5">
                                    <h6 class="text-primary fw-bold text-uppercase mb-2">Tentang Kami</h6>
                                    <h2 class="fw-bold mb-4">{{ $contents['slide_1']->title ?? 'Pilihan Utama Untuk Jelajahi Dunia ✈️' }}</h2>
                                    <p class="text-muted" style="line-height: 1.8;">
                                        {{ $contents['slide_1']->description ?? 'Flight Hub hadir untuk memudahkan perjalanan Anda. Kami berkomitmen memberikan pelayanan terbaik, harga transparan, dan pengalaman pemesanan tiket pesawat yang cepat tanpa ribet.' }}
                                    </p>
                                    <a href="#pencarian" class="btn btn-primary px-4 py-2 mt-3 rounded-pill fw-bold">Mulai Pesan Tiket</a>
                                </div>
                            </div>
                        </div>

                        {{-- SLIDE 2 --}}
                        <div class="carousel-item" data-bs-interval="4000">
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-4 mb-md-0">
                                    <img src="{{ isset($contents['slide_2']) && $contents['slide_2']->image ? asset('storage/'.$contents['slide_2']->image) : 'https://images.unsplash.com/photo-1542296332-2e4473faf563?auto=format&fit=crop&w=600&q=80' }}" class="img-fluid shadow" alt="Slide 2" style="border-radius: 20px; height: 350px; width: 100%; object-fit: cover;">
                                </div>
                                <div class="col-md-6 ps-md-5">
                                    <h6 class="text-primary fw-bold text-uppercase mb-2">Kenyamanan Anda</h6>
                                    <h2 class="fw-bold mb-4">{{ $contents['slide_2']->title ?? 'Terbang Aman, Hati Tenang 🛡️' }}</h2>
                                    <p class="text-muted" style="line-height: 1.8;">
                                        {{ $contents['slide_2']->description ?? 'Didukung oleh berbagai maskapai ternama kelas dunia, kami memastikan Anda sampai ke tujuan dengan aman dan nyaman. Standar keselamatan adalah prioritas utama dari setiap rute penerbangan kami.' }}
                                    </p>
                                    <a href="#pencarian" class="btn btn-primary px-4 py-2 mt-3 rounded-pill fw-bold">Pilih Penerbangan</a>
                                </div>
                            </div>
                        </div>

                        {{-- SLIDE 3 --}}
                        <div class="carousel-item" data-bs-interval="4000">
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-4 mb-md-0">
                                    <img src="{{ isset($contents['slide_3']) && $contents['slide_3']->image ? asset('storage/'.$contents['slide_3']->image) : 'https://images.unsplash.com/photo-1517400508447-f8dd518b86db?auto=format&fit=crop&w=600&q=80' }}" class="img-fluid shadow" alt="Slide 3" style="border-radius: 20px; height: 350px; width: 100%; object-fit: cover;">
                                </div>
                                <div class="col-md-6 ps-md-5">
                                    <h6 class="text-primary fw-bold text-uppercase mb-2">Dukungan Pelanggan</h6>
                                    <h2 class="fw-bold mb-4">{{ $contents['slide_3']->title ?? 'Siap Membantu 24/7 Kapanpun 🕒' }}</h2>
                                    <p class="text-muted" style="line-height: 1.8;">
                                        {{ $contents['slide_3']->description ?? 'Butuh bantuan ubah jadwal atau batal tiket? Tim layanan pelanggan kami selalu siap mendampingi Anda 24 jam penuh. Nikmati kemudahan layanan dari genggaman Anda.' }}
                                    </p>
                                    <button type="button" class="btn btn-primary px-4 py-2 mt-3 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#contactModal">Hubungi Kami</button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="carousel-indicators position-relative mt-5 mb-0">
                        <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="0" class="active bg-primary rounded-circle" style="width: 12px; height: 12px;" aria-current="true"></button>
                        <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="1" class="bg-primary rounded-circle mx-2" style="width: 12px; height: 12px;"></button>
                        <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="2" class="bg-primary rounded-circle" style="width: 12px; height: 12px;"></button>
                    </div>
                </div>

            </div>
        </section>
    </main>

    {{-- ========================================== --}}
    {{-- FOOTER (DITAMBAH MT-AUTO BIAR MENTOK BAWAH) --}}
    {{-- ========================================== --}}
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

    <script>
        function toggleReturnDate() {
            var roundtripRadio = document.getElementById("roundtrip");
            var returnContainer = document.getElementById("return_date_container");
            var returnInput = document.getElementById("tanggal_pulang");
            if (roundtripRadio.checked) {
                returnContainer.style.display = "block"; returnInput.setAttribute("required", "required");
            } else {
                returnContainer.style.display = "none"; returnInput.removeAttribute("required"); returnInput.value = "";
            }
        }
        function togglePassengerPopup() { document.getElementById("passengerPopup").classList.toggle("show"); }
        function updateCount(type, change) {
            let input = document.getElementById('val' + capitalize(type));
            let btnMin = document.getElementById('btnMin' + capitalize(type));
            let currentVal = parseInt(input.value);
            let minLimit = (type === 'dewasa') ? 1 : 0;
            let newVal = currentVal + change;
            if (newVal >= minLimit && newVal <= 7) { input.value = newVal; }
            if (parseInt(input.value) === minLimit) { btnMin.classList.add('disabled'); } else { btnMin.classList.remove('disabled'); }
            let d = document.getElementById('valDewasa').value;
            let a = document.getElementById('valAnak').value;
            let b = document.getElementById('valBayi').value;
            document.getElementById('passengerSummary').innerText = d + " Dewasa, " + a + " Anak, " + b + " Bayi";
        }
        function capitalize(str) { return str.charAt(0).toUpperCase() + str.slice(1); }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>