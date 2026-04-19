<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;

class HrdController extends Controller
{
    // 1. Menampilkan Dashboard HRD (Daftar Batal + Kelola Akun + Search)
    public function dashboard(Request $request)
    {
        // Tangkap kata kunci dari form search
        $search = $request->input('search');

        // Tarik transaksi yang statusnya minta dibatalkan (Bisa dicari via Kode Booking)
        $pendingCancels = Transaction::with(['flight.airline'])
            ->where('status', 'Pending_Cancel')
            ->when($search, function ($query, $search) {
                return $query->where('booking_code', 'like', "%{$search}%");
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        // Tarik semua akun user untuk diganti jabatannya (Bisa dicari via Nama/Email)
        $users = User::where('id', '!=', auth()->id())
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('hrd.dashboard', compact('pendingCancels', 'users', 'search'));
    }

    // 2. Proses ACC Pembatalan & Kompensasi (Pakai Logika Potongan 10%)
    public function approveCancel(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        // Simulasi hitung kompensasi (potong biaya admin 10%)
        $potongan = $transaction->total_price * 0.10;
        $refund = $transaction->total_price - $potongan;

        // Ubah status jadi Batal Permanen
        $transaction->update([
            'status' => 'Cancelled',
            'cancel_reason' => $transaction->cancel_reason . " | (Refund Diproses: Rp " . number_format($refund, 0, ',', '.') . ")"
        ]);

        return back()->with('success', 'Pembatalan tiket ' . $transaction->booking_code . ' di-ACC! Dana Rp ' . number_format($refund, 0, ',', '.') . ' sedang diproses ke user.');
    }

    // 3. Proses Ganti Jabatan/Role Akun
    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);

        return back()->with('success', 'Berhasil! Jabatan ' . $user->name . ' sekarang adalah ' . strtoupper($request->role) . '.');
    }
}