<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffPromoController extends Controller
{
    public function index() {
        $promos = Promo::latest()->get();
        return view('staff.promo.index', compact('promos'));
    }

    public function create() {
        return view('staff.promo.create');
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'badge' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $path = $request->file('image')->store('promos', 'public');

Promo::create([
            'title' => $request->title,
            'badge' => $request->badge,
            'description' => $request->description,
            'code' => strtoupper($request->promo_code), // <-- Ubah 'promo_code' jadi 'code'
            'image' => $path,
            'discount' => 0 // Jaga-jaga kalau tabel lama kamu mewajibkan isi diskon
        ]);

        return redirect()->route('staff.promos.index')->with('success', 'Promo baru berhasil ditambahkan!');
    }

    public function destroy($id) {
        $promo = Promo::findOrFail($id);
        Storage::disk('public')->delete($promo->image);
        $promo->delete();
        return back()->with('success', 'Promo berhasil dihapus!');
    }
}