<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Hub - Tiket Pesawat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Background Pemandangan */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)), url('https://alat-test.com/wp-content/uploads/2021/02/Pengertian-Pesawat-Terbang-dan-Sejarahnya-compressed.jpg') no-repeat center center/cover;
            min-height: 80vh;
            color: white;
            padding-top: 100px;
        }
        
        /* Desain Kotak Pencarian Utama */
        .search-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .search-container label {
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .form-control, .form-select {
            height: 50px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
        }

        .btn-orange {
            background-color: #ff5e1f;
            color: white;
            height: 50px;
            border-radius: 8px;
            font-weight: bold;
            border: none;
        }

        .btn-orange:hover {
            background-color: #e04a11;
            color: white;
        }

        /* ----------------------------------------- */
        /* CSS KHUSUS POPUP PENUMPANG ALA TRAVELOKA  */
        /* ----------------------------------------- */
        .passenger-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }
        
        .passenger-popup {
            display: none; /* Disembunyikan awalnya */
            position: absolute;
            top: 100%;
            left: 15px;
            width: 320px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            padding: 20px;
            z-index: 1000;
            margin-top: 5px;
            color: #333; /* Teks hitam agar mudah dibaca */
        }

        .passenger-popup.show {
            display: block;
        }

        .passenger-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .passenger-info h6 { margin: 0; font-weight: bold; font-size: 15px; }
        .passenger-info small { color: #888; font-size: 12px; }

        .counter-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-counter {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 1px solid #0194f3;
            background: white;
            color: #0194f3;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-counter.disabled {
            border-color: #ccc;
            color: #ccc;
            cursor: not-allowed;
        }

        .counter-val {
            width: 20px;
            text-align: center;
            font-weight: bold;
            border: none;
            background: transparent;
        }

        /* ----------------------------------------- */
        
        .top-options .form-select {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .top-options .form-select option { color: black; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top pt-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="/">
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
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                            <li><a class="dropdown-item" href="{{ route('booking.check') }}"><i class="fas fa-receipt text-primary me-2"></i> Pesanan Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
            </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Pilihan utama untuk jelajahi dunia</h2>
            
            <form action="/search" method="GET">
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
                                @foreach($airports as $ap)
                                    <option value="{{ $ap->id }}">{{ $ap->city }} ({{ $ap->iata_code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Ke</label>
                            <select name="tujuan" class="form-select" required>
                                <option value="">Pilih Tujuan</option>
                                @foreach($airports as $ap)
                                    <option value="{{ $ap->id }}">{{ $ap->id != $ap->id ? $ap->city : $ap->city }} ({{ $ap->iata_code }})</option>
                                @endforeach
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

    <script>
        // Logika Pulang Pergi
        function toggleReturnDate() {
            var roundtripRadio = document.getElementById("roundtrip");
            var returnContainer = document.getElementById("return_date_container");
            var returnInput = document.getElementById("tanggal_pulang");

            if (roundtripRadio.checked) {
                returnContainer.style.display = "block";
                returnInput.setAttribute("required", "required");
            } else {
                returnContainer.style.display = "none";
                returnInput.removeAttribute("required");
                returnInput.value = "";
            }
        }

        // Logika Popup Penumpang
        function togglePassengerPopup() {
            document.getElementById("passengerPopup").classList.toggle("show");
        }

        // Logika Tambah/Kurang Penumpang
        function updateCount(type, change) {
            let input = document.getElementById('val' + capitalize(type));
            let btnMin = document.getElementById('btnMin' + capitalize(type));
            let currentVal = parseInt(input.value);
            
            // Aturan Batas Minimum (Dewasa min 1, sisanya min 0)
            let minLimit = (type === 'dewasa') ? 1 : 0;
            let newVal = currentVal + change;

            if (newVal >= minLimit && newVal <= 7) { // Maksimal 7 penumpang per kategori
                input.value = newVal;
            }

            // Atur warna tombol minus
            if (parseInt(input.value) === minLimit) {
                btnMin.classList.add('disabled');
            } else {
                btnMin.classList.remove('disabled');
            }

            // Update Tulisan di Tombol Utama
            updateSummaryText();
        }

        function updateSummaryText() {
            let d = document.getElementById('valDewasa').value;
            let a = document.getElementById('valAnak').value;
            let b = document.getElementById('valBayi').value;
            document.getElementById('passengerSummary').innerText = d + " Dewasa, " + a + " Anak, " + b + " Bayi";
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>