<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

use App\Models\Flight;
use App\Models\Transaction;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;

class BookingController extends Controller
{
    /**
     * Konfigurasi Midtrans - JALUR PALING AMAN
     */
    public function __construct()
    {
        // Mengambil Server Key dari file .env biar rahasia aman dari GitHub!
        Config::$serverKey = env('MIDTRANS_SERVER_KEY'); 
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // 1. Menampilkan form isi data penumpang
    public function create(Request $request)
    {
        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])->findOrFail($request->flight_id);
        
        $data = [
            'flight' => $flight,
            'dewasa' => $request->dewasa,
            'anak'   => $request->anak,
            'bayi'   => $request->bayi,
            'kelas'  => $request->kelas,
            'total_harga' => $request->total_harga
        ];

        return view('user.booking', $data);
    }

    // 2. Menyimpan data & Minta Snap Token
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $bookingCode = 'TRV-' . strtoupper(Str::random(6));

            $transaction = Transaction::create([
                'flight_id'     => $request->flight_id,
                'booking_code'  => $bookingCode,
                'contact_name'  => $request->contact_name,
                'contact_phone' => $request->contact_phone,
                'contact_email' => $request->contact_email,
                'total_price'   => (int) $request->total_price,
                'status'        => 'Unpaid',
            ]);

            // Simpan Penumpang Dewasa
            if ($request->has('nama_dewasa')) {
                foreach ($request->nama_dewasa as $index => $nama) {
                    Passenger::create([
                        'transaction_id'  => $transaction->id,
                        'type'            => 'Dewasa',
                        'title'           => $request->title_dewasa[$index],
                        'name'            => $nama,
                        'identity_number' => $request->ktp_dewasa[$index],
                    ]);
                }
            }
            
            // Simpan Penumpang Anak & Bayi
            if ($request->has('nama_anak')) {
                foreach ($request->nama_anak as $index => $nama) {
                    Passenger::create([
                        'transaction_id'  => $transaction->id,
                        'type'            => 'Anak',
                        'name'            => $nama,
                        'date_of_birth'   => $request->tgl_lahir_anak[$index],
                    ]);
                }
            }

            if ($request->has('nama_bayi')) {
                foreach ($request->nama_bayi as $index => $nama) {
                    Passenger::create([
                        'transaction_id'  => $transaction->id,
                        'type'            => 'Bayi',
                        'name'            => $nama,
                        'date_of_birth'   => $request->tgl_lahir_bayi[$index],
                    ]);
                }
            }

            // Integrasi Snap Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $bookingCode,
                    'gross_amount' => (int) $transaction->total_price,
                ],
                'customer_details' => [
                    'first_name' => $transaction->contact_name,
                    'email' => $transaction->contact_email,
                    'phone' => $transaction->contact_phone,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);

            DB::commit();

            return view('user.payment', compact('transaction', 'snapToken'));

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Penanganan Setelah Pembayaran Berhasil (Auto-Update Status)
     */
    public function paymentSuccess($code)
    {
        $transaction = Transaction::where('booking_code', $code)->firstOrFail();
        
        if ($transaction->status == 'Unpaid') {
            $transaction->update(['status' => 'Paid']);
        }

        return redirect()->route('booking.success', ['code' => $code])
                         ->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    // 3. Menampilkan halaman sukses / invoice / Detail Pesanan
    public function success($code)
    {
        // KITA UPDATE DI SINI: Tarik relasi departureAirport dan arrivalAirport
        $transaction = Transaction::with([
            'flight.airline', 
            'flight.departureAirport', 
            'flight.arrivalAirport', 
            'passengers'
        ])->where('booking_code', $code)->firstOrFail();
        
        return view('user.success', compact('transaction'));
    }

    // 4. Cek Pesanan
    public function checkOrder() { return view('user.cek-pesanan'); }

    public function findOrder(Request $request)
    {
        $request->validate(['booking_code' => 'required', 'contact_email' => 'required|email']);
        $transaction = Transaction::where('booking_code', strtoupper($request->booking_code))
                                  ->where('contact_email', $request->contact_email)
                                  ->first();

        if (!$transaction) return back()->with('error', 'Pesanan tidak ditemukan!');
        return redirect()->route('booking.success', ['code' => $transaction->booking_code]);
    }

    // 5. Ajax Check Promo
    public function checkPromo(Request $request)
    {
        if (!Auth::check()) return response()->json(['success' => false, 'message' => 'Silakan login.']);
        $promo = \App\Models\Promo::where('code', strtoupper($request->promo_code))->where('is_active', true)->first();
        if (!$promo) return response()->json(['success' => false, 'message' => 'Promo tidak valid.']);
        
        return response()->json(['success' => true, 'discount' => $promo->discount_amount]);
    }

    // 6. Download E-Tiket PDF
    public function downloadTicket($code)
    {
        $transaction = Transaction::with(['passengers', 'flight.airline', 'flight.departureAirport', 'flight.arrivalAirport'])
                                  ->where('booking_code', $code)->firstOrFail();

        if ($transaction->status != 'Paid') return back()->with('error', 'Pembayaran belum lunas.');

        $pdf = Pdf::loadView('user.e-ticket', compact('transaction'));
        return $pdf->download('E-Ticket_' . $transaction->booking_code . '.pdf');
    }

    // 7. Pilih Kursi
    public function showSeatMap($code)
    {
        $transaction = Transaction::with(['passengers', 'flight'])->where('booking_code', $code)->firstOrFail();
        $takenSeats = Passenger::whereHas('transaction', function($q) use ($transaction) {
            $q->where('flight_id', $transaction->flight_id)->where('status', 'Paid');
        })->whereNotNull('seat_number')->pluck('seat_number')->toArray();

        return view('user.select-seats', compact('transaction', 'takenSeats'));
    }

    public function saveSeats(Request $request, $code)
    {
        if ($request->has('seats')) {
            foreach($request->seats as $passengerId => $seatNumber) {
                Passenger::where('id', $passengerId)->update(['seat_number' => $seatNumber]);
            }
        }
        return redirect()->route('booking.success', ['code' => $code])->with('success', 'Kursi berhasil dipilih!');
    }

    /**
     * 8. Menampilkan riwayat pesanan berdasarkan email user yang login
     */
    public function history()
    {
        // Ambil email user yang sedang login
        $userEmail = Auth::user()->email;

        // Tarik data transaksi yang emailnya cocok, urutkan dari yang terbaru
        $transactions = Transaction::with(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport'])
            ->where('contact_email', $userEmail)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.history', compact('transactions'));
    }
    
    /**
     * Memproses pembatalan pesanan dari user
     */
    public function cancelOrder(Request $request, $code)
    {
        $request->validate([
            'cancel_reason' => 'required|string|min:10'
        ], [
            'cancel_reason.required' => 'Alasan pembatalan wajib diisi.',
            'cancel_reason.min' => 'Alasan pembatalan minimal 10 karakter.'
        ]);

        $transaction = Transaction::where('booking_code', $code)
                                  ->where('contact_email', Auth::user()->email)
                                  ->firstOrFail();

        // Kalau statusnya Paid, kita ubah jadi Pending Cancel biar di-review Manager/HRD untuk kompensasi
        if ($transaction->status == 'Paid') {
            $transaction->update([
                'status' => 'Pending_Cancel', // Status baru untuk review HRD/Manager
                'cancel_reason' => $request->cancel_reason
            ]);
            return back()->with('success', 'Permintaan pembatalan tiket (Lunas) terkirim. Menunggu proses kompensasi dari Manajemen.');
        } 
        
        // Kalau belum dibayar, langsung batalin aja
        $transaction->update([
            'status' => 'Cancelled',
            'cancel_reason' => $request->cancel_reason
        ]);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}