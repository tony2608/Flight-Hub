<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manager Dashboard - Flight Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    {{-- Wajib panggil Chart.js buat grafiknya --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f5f6fa; }
        .card-stat { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.3s; }
        .card-stat:hover { transform: translateY(-5px); }
        .icon-box { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="fas fa-chart-line me-2 text-info"></i> Manager Portal</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 small"><i class="fas fa-user-tie me-1"></i> {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-sign-out-alt"></i> Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        
        <h3 class="fw-bold mb-4">Ringkasan Keuangan & Performa</h3>

        {{-- 3 KARTU STATISTIK ATAS --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card card-stat p-3 d-flex flex-row align-items-center mb-3 mb-md-0">
                    <div class="icon-box bg-success me-3"><i class="fas fa-wallet"></i></div>
                    <div>
                        <span class="text-muted small fw-bold">TOTAL PENDAPATAN</span>
                        <h4 class="fw-bold text-success mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stat p-3 d-flex flex-row align-items-center mb-3 mb-md-0">
                    <div class="icon-box bg-primary me-3"><i class="fas fa-ticket-alt"></i></div>
                    <div>
                        <span class="text-muted small fw-bold">TIKET TERJUAL (LUNAS)</span>
                        <h4 class="fw-bold mb-0">{{ $tiketTerjual }} <span class="fs-6 text-muted">Transaksi</span></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stat p-3 d-flex flex-row align-items-center">
                    <div class="icon-box bg-warning text-dark me-3"><i class="fas fa-exclamation-circle"></i></div>
                    <div>
                        <span class="text-muted small fw-bold">REQUEST BATAL</span>
                        <h4 class="fw-bold text-warning mb-0">{{ $pendingCancel }} <span class="fs-6 text-muted">Pending</span></h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- AREA GRAFIK --}}
        <div class="card card-stat p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Grafik Pendapatan Tahun {{ date('Y') }}</h5>
                <span class="badge bg-light text-dark border"><i class="fas fa-calendar-alt me-1"></i> Data Real-Time</span>
            </div>
            
            {{-- Canvas untuk nggambar Chart --}}
            <canvas id="revenueChart" height="100"></canvas>
        </div>

    </div>

    {{-- SCRIPT MENGGAMBAR GRAFIK --}}
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'bar', // Bisa diganti 'line' kalau mau bentuknya garis
            data: {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($dataPendapatan) !!}, // Nangkep array 12 bulan dari Controller
                    backgroundColor: 'rgba(13, 110, 253, 0.8)', // Warna Biru Primary Bootstrap
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1,
                    borderRadius: 6 // Biar ujung batangnya agak membulat (modern)
                }]
            },
            options: {
                responsive: true,
                scales: { 
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    } 
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>