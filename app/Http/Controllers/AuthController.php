<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // --- REGISTRASI ---
    public function showRegister()
    {
        return view('auth.register');
    }

    public function registerSubmit(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed', 
        ]);

        // 2. Simpan User Baru ke Database
        // Secara default, MySQL akan mengisi kolom 'role' dengan 'user' (pembeli biasa)
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), 
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan Log In.');
    }

    // --- LOGIN ---
    public function showLogin()
    {
        return view('auth.login');
    }

    public function loginSubmit(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // 2. Cek apakah email dan password cocok
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // ==========================================
            // LOGIKA PEMISAH JABATAN (MULTI-ROLE)
            // ==========================================
            $role = Auth::user()->role;

            if ($role === 'hrd') {
                return redirect()->route('hrd.dashboard')->with('success', 'Selamat datang di Portal HRD!');
            } 
            elseif ($role === 'manager') {
                return redirect()->route('manager.dashboard')->with('success', 'Selamat datang Bos Manager!');
            } 
            elseif ($role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang Admin!');
            } 
            elseif ($role === 'staff') {
                return redirect()->route('staff.dashboard')->with('success', 'Selamat datang di Portal Staff!');
            } 
            else {
                // Default: Kalau yang login User biasa, lempar ke beranda
                return redirect()->route('home')->with('success', 'Login berhasil!');
            }
        }

        // Jika gagal, kembalikan ke form dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan salah.',
        ])->withInput();
    }

    // --- LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}