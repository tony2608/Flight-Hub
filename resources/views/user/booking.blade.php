<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Data Penumpang - Flight Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f5f6fa; }
        .navbar-custom { background-color: #0194f3; }
        .card-custom { border-radius: 12px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .card-header-custom { background-color: white; border-bottom: 1px solid #eee; padding: 15px 20px; border-radius: 12px 12px 0 0; font-weight: bold; font-size: 1.1rem; }
        .form-label { font-size: 0.9rem; color: #555; font-weight: 600; }
        .btn-orange { background-color: #ff5e1f; color: white; font-weight: bold; border-radius: 8px; padding: 12px; }
        .btn-orange:hover { background-color: #e04a11; color: white; }
    </style>
</head>

{{-- TAMBAHKAN CLASS INI BIAR FOOTER TETAP DI BAWAH --}}
<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-dark navbar-custom py-3 shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="javascript:history.back()"><i class="fas fa-arrow-left me-2"></i> Form Pemesanan</a>
        </div>
    </nav>

    {{-- KONTEN UTAMA DENGAN FLEX-GROW --}}
    <div class="flex-grow-1">
        <div class="container">
            <div class="row">
                
                <div class="col-md-8">
                    <form action="{{ route('booking.store') }}" method="POST">
                        @csrf
                        
                        <input type="hidden" name="flight_id" value="{{ $flight->id }}">
                        <input type="hidden" name="total_price" id="hiddenTotalPrice" value="{{ $total_harga }}">
                        <input type="hidden" name="applied_promo_code" id="applied_promo_code" value="">
                        
                        <div class="card card-custom">
                            <div class="card-header-custom"><i class="fas fa-address-book text-primary me-2"></i> Detail Kontak (Untuk E-Tiket)</div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" name="contact_name" class="form-control" value="{{ Auth::check() ? Auth::user()->name : '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nomor Telepon</label>
                                        <input type="text" name="contact_phone" class="form-control" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Alamat Email</label>
                                        <input type="email" name="contact_email" class="form-control" value="{{ Auth::check() ? Auth::user()->email : '' }}" required>
                                        <small class="text-muted">E-Tiket akan dikirim ke email ini.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-3 mt-4">Data Penumpang</h5>
                        
                        {{-- PENUMPANG DEWASA --}}
                        @for($i = 1; $i <= $dewasa; $i++)
                        <div class="card card-custom border-start border-primary border-4">
                            <div class="card-header-custom"><i class="fas fa-user text-primary me-2"></i> Penumpang Dewasa {{ $i }}</div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Titel</label>
                                        <select name="title_dewasa[]" class="form-select" required>
                                            <option value="Tn.">Tn.</option>
                                            <option value="Ny.">Ny.</option>
                                            <option value="Nn.">Nn.</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label class="form-label">Nama Lengkap (Sesuai KTP/Paspor)</label>
                                        <input type="text" name="nama_dewasa[]" class="form-control" required>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label class="form-label">Nomor Identitas (NIK/Paspor)</label>
                                        <input type="text" name="ktp_dewasa[]" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor

                        {{-- PENUMPANG ANAK --}}
                        @if($anak > 0)
                            @for($i = 1; $i <= $anak; $i++)
                            <div class="card card-custom border-start border-info border-4">
                                <div class="card-header-custom"><i class="fas fa-child text-info me-2"></i> Penumpang Anak {{ $i }}</div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-7 mb-3">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" name="nama_anak[]" class="form-control" required>
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <input type="date" name="tgl_lahir_anak[]" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        @endif

                        {{-- PENUMPANG BAYI --}}
                        @if($bayi > 0)
                            @for($i = 1; $i <= $bayi; $i++)
                            <div class="card card-custom border-start border-warning border-4">
                                <div class="card-header-custom"><i class="fas fa-baby text-warning me-2"></i> Penumpang Bayi {{ $i }}</div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-7 mb-3">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" name="nama_bayi[]" class="form-control" required>
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <input type="date" name="tgl_lahir_bayi[]" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        @endif

                        <button type="submit" class="btn btn-orange w-100 mt-2 mb-5">Lanjutkan ke Pembayaran</button>
                    </form>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom sticky-top" style="top: 20px;">
                        <div class="card-header-custom"><i class="fas fa-plane-departure text-primary me-2"></i> Detail Penerbangan</div>
                        <div class="card-body">
                            <h6 class="fw-bold">{{ $flight->departureAirport->city }} ➔ {{ $flight->arrivalAirport->city }}</h6>
                            <p class="text-muted small mb-3">{{ \Carbon\Carbon::parse($flight->departure_time)->translatedFormat('l, d F Y') }}</p>
                            
                            <div class="d-flex align-items-center mb-3">
                                @if($flight->airline->logo)
                                    <img src="{{ asset('storage/' . $flight->airline->logo) }}" width="40" class="me-2">
                                @endif
                                <div>
                                    <span class="fw-bold d-block">{{ $flight->airline->name }}</span>
                                    <small class="text-muted">{{ $flight->flight_number }} • Kelas {{ $kelas ?? 'Economy' }}</small>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h6 class="fw-bold mb-3">Rincian Harga</h6>
                            <div class="d-flex justify-content-between mb-2 small">
                                <span>Dewasa (x{{ $dewasa }})</span>
                            </div>
                            @if($anak > 0)
                            <div class="d-flex justify-content-between mb-2 small">
                                <span>Anak (x{{ $anak }})</span>
                            </div>
                            @endif
                            @if($bayi > 0)
                            <div class="d-flex justify-content-between mb-2 small">
                                <span>Bayi (x{{ $bayi }})</span>
                            </div>
                            @endif
                            
                            <div class="d-flex justify-content-between mb-2 small text-success" id="discountRow" style="display: none !important;">
                                <span>Diskon Promo</span>
                                <span id="discountText">- Rp 0</span>
                            </div>

                            <hr>
                            
                            <div class="input-group mb-3">
                                <input type="text" id="promoInput" class="form-control" placeholder="Kode promo?" style="text-transform: uppercase;">
                                <button class="btn btn-outline-primary fw-bold" type="button" id="btnPromo" onclick="applyPromo()">Pakai</button>
                            </div>
                            <small id="promoMessage" class="d-block mb-3 fw-bold"></small>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Total Harga</span>
                                <h4 class="fw-bold text-primary mb-0" id="finalPriceText">Rp {{ number_format($total_harga, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="pt-5 pb-3 mt-auto" style="background-color: #1a1e29; color: #a1a1a8;">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-4 mb-4">
                    <h5 class="text-white fw-bold mb-3"><i class="fas fa-plane-departure me-2"></i>Flight Hub</h5>
                    <p class="small" style="line-height: 1.8;">Platform pemesanan tiket pesawat terpercaya di Indonesia. Kami siap membawa Anda terbang ke berbagai destinasi impian di seluruh dunia.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white fw-bold mb-3">Layanan Kami</h5>
                    <ul class="list-unstyled small" style="line-height: 2;">
                        <li><a href="{{ route('home') }}" class="text-decoration-none" style="color: #a1a1a8;">Pesan Tiket</a></li>
                        <li><a href="{{ route('booking.history') }}" class="text-decoration-none" style="color: #a1a1a8;">Cek Pesanan</a></li>
                        <li><a href="#" class="text-decoration-none" style="color: #a1a1a8;">Cara Pembayaran</a></li>
                        <li><a href="#" class="text-decoration-none" style="color: #a1a1a8;">Bantuan & FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white fw-bold mb-3">Hubungi Kami</h5>
                    <p class="small mb-3" style="color: #a1a1a8; line-height: 1.8;">Punya pertanyaan atau butuh bantuan? Jangan ragu untuk menghubungi tim dukungan kami.</p>
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

    {{-- MODAL / POP-UP HUBUNGI KAMI --}}
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
                        <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-outline-success d-flex align-items-center justify-content-start py-2 px-3" style="border-radius: 10px;">
                            <i class="fab fa-whatsapp fs-3 me-3"></i> 
                            <div class="text-start"><div class="fw-bold">WhatsApp</div><small>+62 812-3456-7890</small></div>
                        </a>
                        <a href="https://instagram.com/flighthub_id" target="_blank" class="btn btn-outline-danger d-flex align-items-center justify-content-start py-2 px-3" style="border-radius: 10px;">
                            <i class="fab fa-instagram fs-3 me-3"></i> 
                            <div class="text-start"><div class="fw-bold">Instagram</div><small>@flighthub_id</small></div>
                        </a>
                        <a href="mailto:flighthub@gmail.com" class="btn btn-outline-primary d-flex align-items-center justify-content-start py-2 px-3" style="border-radius: 10px;">
                            <i class="fas fa-envelope fs-3 me-3"></i> 
                            <div class="text-start"><div class="fw-bold">Email</div><small>flighthub@gmail.com</small></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let basePrice = {{ $total_harga }};
        let currentDiscount = 0;

        function applyPromo() {
            let code = document.getElementById('promoInput').value.toUpperCase();
            let msgBox = document.getElementById('promoMessage');
            let btn = document.getElementById('btnPromo');

            if(code.trim() === '') return;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            fetch('{{ route("promo.check") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ promo_code: code })
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = 'Pakai';
                
                if(data.success) {
                    currentDiscount = data.discount;
                    let finalPrice = basePrice - currentDiscount;
                    
                    msgBox.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Promo berhasil digunakan!</span>';
                    document.getElementById('discountRow').style.setProperty('display', 'flex', 'important');
                    document.getElementById('discountText').innerText = '- Rp ' + currentDiscount.toLocaleString('id-ID');
                    document.getElementById('finalPriceText').innerText = 'Rp ' + finalPrice.toLocaleString('id-ID');
                    document.getElementById('hiddenTotalPrice').value = finalPrice;
                    document.getElementById('applied_promo_code').value = code;
                    
                    document.getElementById('promoInput').readOnly = true;
                    btn.disabled = true;
                } else {
                    msgBox.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> ' + data.message + '</span>';
                }
            })
            .catch(error => {
                btn.innerHTML = 'Pakai';
                msgBox.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> Terjadi kesalahan jaringan.</span>';
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>