<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Wajib dipanggil untuk tabel user

class HrdController extends Controller
{
    public function dashboard(Request $request)
    {
        $search = $request->input('search');

        // Tarik semua akun user untuk diganti jabatannya
        $users = User::where('id', '!=', auth()->id())
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('hrd.dashboard', compact('users', 'search'));
    }

    // FUNGSI UBAH JABATAN OLEH HRD
    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);

        return back()->with('success', 'Berhasil! Jabatan ' . $user->name . ' sekarang adalah ' . strtoupper($request->role) . '.');
    }
}