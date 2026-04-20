<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $totalPendapatan = Transaction::where('status', 'Paid')->sum('total_price');
        $tiketTerjual = Transaction::where('status', 'Paid')->count();
        $pendingCancel = Transaction::where('status', 'Pending_Cancel')->count();

        $grafikBulanan = Transaction::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('SUM(total_price) as total')
        )
        ->where('status', 'Paid')
        ->whereYear('created_at', date('Y'))
        ->groupBy('bulan')
        ->pluck('total', 'bulan')->toArray();

        $dataPendapatan = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataPendapatan[] = $grafikBulanan[$i] ?? 0; 
        }

        // AMBIL DATA PERMINTAAN BATAL UNTUK TABEL MANAGER
        $pendingCancels = Transaction::with(['flight.airline'])
            ->where('status', 'Pending_Cancel')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('manager.dashboard', compact('totalPendapatan', 'tiketTerjual', 'pendingCancel', 'dataPendapatan', 'pendingCancels'));
    }

    // FUNGSI ACC REFUND OLEH MANAGER
    public function approveCancel(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $potongan = $transaction->total_price * 0.10;
        $refund = $transaction->total_price - $potongan;

        $transaction->update([
            'status' => 'Cancelled',
            'cancel_reason' => $transaction->cancel_reason . " | (Refund Diproses: Rp " . number_format($refund, 0, ',', '.') . ")"
        ]);

        return back()->with('success', 'Pembatalan tiket ' . $transaction->booking_code . ' di-ACC! Dana Rp ' . number_format($refund, 0, ',', '.') . ' sedang diproses.');
    }
}