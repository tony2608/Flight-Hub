<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    public function dashboard()
    {
        // 1. Hitung Total Pendapatan (Hanya yang statusnya 'Paid')
        $totalPendapatan = Transaction::where('status', 'Paid')->sum('total_price');
        
        // 2. Hitung Total Transaksi Sukses
        $tiketTerjual = Transaction::where('status', 'Paid')->count();
        
        // 3. Hitung Request Batal yang belum di-ACC HRD
        $pendingCancel = Transaction::where('status', 'Pending_Cancel')->count();

        // 4. Data buat Grafik (Total Pendapatan per Bulan di tahun ini)
        $grafikBulanan = Transaction::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('SUM(total_price) as total')
        )
        ->where('status', 'Paid')
        ->whereYear('created_at', date('Y'))
        ->groupBy('bulan')
        ->pluck('total', 'bulan')->toArray();

        // Siapkan array 12 bulan (Januari - Desember) biar grafiknya nggak bolong
        $dataPendapatan = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataPendapatan[] = $grafikBulanan[$i] ?? 0; // Kalau bulan itu kosong, isi 0
        }

        return view('manager.dashboard', compact('totalPendapatan', 'tiketTerjual', 'pendingCancel', 'dataPendapatan'));
    }
}