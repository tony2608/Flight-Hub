<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Tiket - Flight Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="Mid-client-hVwQjgmZwNL7wBSg"></script>

    <style>
        body { background-color: #f5f6fa; }
        .payment-card {
            max-width: 500px;
            margin: 80px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px 30px;
            text-align: center;
        }
        .total-price { font-size: 36px; font-weight: bold; color: #0194f3; }
        .btn-pay { background-color: #ff5e1f; color: white; font-weight: bold; padding: 15px 30px; border-radius: 10px; font-size: 18px; width: 100%; border: none; transition: 0.3s; }
        .btn-pay:hover { background-color: #e04a11; transform: translateY(-2px); }
    </style>
</head>
<body>

    <div class="container">
        <div class="payment-card">
            <h4 class="fw-bold mb-4">Selesaikan Pembayaran Anda</h4>
            <p class="text-muted mb-1">Kode Booking:</p>
            <h5 class="fw-bold">{{ $transaction->booking_code }}</h5>
            
            <hr class="my-4">
            
            <p class="mb-1 text-muted">Total Tagihan</p>
            <div class="total-price mb-4">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</div>

            <button id="pay-button" class="btn-pay shadow-sm">Bayar Sekarang</button>
            
            <a href="{{ route('booking.success', $transaction->booking_code) }}" class="btn btn-link text-muted mt-3 text-decoration-none">Bayar Nanti (Cek E-Tiket)</a>
        </div>
    </div>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            // Panggil pop-up Snap Midtrans menggunakan Token dari Controller
            snap.pay('{{ $snapToken }}', {
onSuccess: function(result){
    window.location.href = "{{ route('payment.success_callback', $transaction->booking_code) }}";
},
                onPending: function(result){
                    alert("Menunggu pembayaran Anda!");
                    window.location.href = "{{ route('booking.success', $transaction->booking_code) }}";
                },
                onError: function(result){
                    alert("Pembayaran Gagal!");
                },
                onClose: function(){
                    alert('Anda menutup pop-up sebelum menyelesaikan pembayaran.');
                }
            });
        };
    </script>
</body>
</html>