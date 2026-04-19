<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class staffTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['flight.airline', 'passengers'])->latest()->get();
        return view('staff.transactions.index', compact('transactions'));
    }

    // TAMBAHKAN FUNGSI INI UNTUK MENGUBAH STATUS
    public function updateStatus(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:Unpaid,Paid,Cancelled'
        ]);

        // Cari transaksi dan ubah statusnya
        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status pesanan ' . $transaction->booking_code . ' berhasil diperbarui!');
    }
}