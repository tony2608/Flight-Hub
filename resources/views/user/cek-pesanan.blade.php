<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Pesanan - Flight Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f5f6fa; }
        .navbar-custom { background-color: #0194f3; }
        .card-custom { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .btn-orange { background-color: #ff5e1f; color: white; font-weight: bold; padding: 12px; border-radius: 8px; }
        .btn-orange:hover { background-color: #e04a11; color: white; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark navbar-custom py-3 shadow-sm mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}"><i class="fas fa-dove me-2"></i> Flight Hub</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Cek Status Pesanan</h3>
                    <p class="text-muted">Masukkan Kode Booking dan Email yang digunakan saat memesan tiket.</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
                @endif

                <div class="card card-custom p-4">
                    <div class="card-body">
                        <form action="{{ route('booking.find') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kode Booking (Contoh: TRV-XYZ123)</label>
                                <input type="text" name="booking_code" class="form-control form-control-lg" required placeholder="Masukkan Kode Booking" style="text-transform: uppercase;">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Alamat Email</label>
                                <input type="email" name="contact_email" class="form-control form-control-lg" required placeholder="Masukkan Email">
                            </div>
                            <button type="submit" class="btn btn-orange w-100 fs-5"><i class="fas fa-search me-2"></i> Cek Pesanan Saya</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>