<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kursi - Flight Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f5f6fa; }
        .plane-container { background: white; border-radius: 50px 50px 15px 15px; padding: 40px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 400px; margin: 0 auto; border: 5px solid #dee2e6; border-bottom: none; }
        .seat { width: 45px; height: 45px; border-radius: 8px; border: 2px solid #0d6efd; background-color: white; color: #0d6efd; font-weight: bold; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; user-select: none; }
        .seat:hover:not(.taken) { background-color: #e9ecef; }
        .seat.taken { background-color: #dc3545; border-color: #dc3545; color: white; cursor: not-allowed; opacity: 0.5; }
        .seat.selected { background-color: #0d6efd; color: white; }
        .aisle { width: 30px; }
        .seat-row { display: flex; justify-content: center; margin-bottom: 15px; gap: 10px; }
    </style>
</head>
<body>

    <div class="container py-5">
        <div class="row">
            
            {{-- KOLOM KIRI: INFO PENUMPANG --}}
            <div class="col-md-5 mb-4">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h4 class="fw-bold"><i class="fas fa-users text-primary me-2"></i> Penumpang</h4>
                    <p class="text-muted small mb-4">Silakan klik kursi di denah untuk mengisi kursi penumpang di bawah ini secara berurutan.</p>
                    
                    <form action="{{ route('booking.seats.save', $transaction->booking_code) }}" method="POST" id="seatForm">
                        @csrf
                        @foreach($transaction->passengers as $index => $pax)
                            <div class="mb-3 p-3 border rounded bg-light">
                                <label class="form-label fw-bold mb-1">{{ $pax->title ?? '' }} {{ $pax->name }} <span class="badge bg-secondary ms-1">{{ $pax->type }}</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-chair"></i></span>
                                    <input type="text" name="seats[{{ $pax->id }}]" id="pax-{{ $index }}" class="form-control seat-input" placeholder="Belum pilih kursi" readonly required>
                                </div>
                            </div>
                        @endforeach
                        
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold mt-3" id="btnSubmit" disabled>
                            <i class="fas fa-save me-2"></i> Simpan Kursi
                        </button>
                    </form>
                </div>
            </div>

            {{-- KOLOM KANAN: DENAH PESAWAT --}}
            <div class="col-md-7">
                <div class="plane-container text-center">
                    <h5 class="fw-bold mb-1">Depan (Kokpit)</h5>
                    <p class="text-muted small mb-4 border-bottom pb-3">Kelas Ekonomi</p>

                    {{-- Legend --}}
                    <div class="d-flex justify-content-center gap-3 mb-4 small fw-bold">
                        <div><span class="d-inline-block seat" style="width:20px;height:20px;"></span> Tersedia</div>
                        <div><span class="d-inline-block seat selected" style="width:20px;height:20px;"></span> Dipilih</div>
                        <div><span class="d-inline-block seat taken" style="width:20px;height:20px;"></span> Terisi</div>
                    </div>

                    {{-- Generate 10 Baris Kursi (A, B, C - D, E, F) --}}
                    @for($row = 1; $row <= 10; $row++)
                        <div class="seat-row">
                            @foreach(['A', 'B', 'C'] as $col)
                                @php $seatCode = $row . $col; @endphp
                                <div class="seat {{ in_array($seatCode, $takenSeats) ? 'taken' : 'available' }}" data-seat="{{ $seatCode }}">{{ $seatCode }}</div>
                            @endforeach
                            
                            <div class="aisle"></div> {{-- Jalan Setapak --}}
                            
                            @foreach(['D', 'E', 'F'] as $col)
                                @php $seatCode = $row . $col; @endphp
                                <div class="seat {{ in_array($seatCode, $takenSeats) ? 'taken' : 'available' }}" data-seat="{{ $seatCode }}">{{ $seatCode }}</div>
                            @endforeach
                        </div>
                    @endfor

                </div>
            </div>

        </div>
    </div>

    {{-- LOGIKA JAVASCRIPT UNTUK KLIK KURSI --}}
    <script>
        const availableSeats = document.querySelectorAll('.seat.available');
        const seatInputs = document.querySelectorAll('.seat-input');
        const btnSubmit = document.getElementById('btnSubmit');
        let selectedSeats = [];

        availableSeats.forEach(seat => {
            seat.addEventListener('click', function() {
                const seatCode = this.getAttribute('data-seat');

                // Jika kursi sudah dipilih (mau dibatalkan)
                if (this.classList.contains('selected')) {
                    this.classList.remove('selected');
                    selectedSeats = selectedSeats.filter(s => s !== seatCode);
                } 
                // Jika kursi belum dipilih dan masih ada kuota penumpang
                else if (selectedSeats.length < seatInputs.length) {
                    this.classList.add('selected');
                    selectedSeats.push(seatCode);
                }

                updateInputs();
            });
        });

        function updateInputs() {
            // Kosongkan semua input dulu
            seatInputs.forEach(input => input.value = '');
            
            // Isi input sesuai kursi yang dipilih
            selectedSeats.forEach((seat, index) => {
                if(seatInputs[index]) {
                    seatInputs[index].value = seat;
                }
            });

            // Aktifkan tombol simpan kalau semua penumpang udah dapet kursi
            if (selectedSeats.length === seatInputs.length) {
                btnSubmit.removeAttribute('disabled');
            } else {
                btnSubmit.setAttribute('disabled', 'true');
            }
        }
    </script>
</body>
</html>