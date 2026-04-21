<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - Flight Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Tambahan FontAwesome buat nampilin icon mata --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f5f6fa; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card-custom { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 400px; padding: 30px; background: white;}
        .btn-orange { background-color: #ff5e1f; color: white; font-weight: bold; padding: 12px; border-radius: 8px; width: 100%; }
        .btn-orange:hover { background-color: #e04a11; color: white; }
        /* Biar kursornya jadi tangan pas disorot ke icon mata */
        .toggle-password { cursor: pointer; } 
    </style>
</head>
<body>

    <div class="card-custom">
        <h3 class="text-center fw-bold mb-4 text-primary">Log In</h3>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control form-control-lg" required value="{{ old('email') }}">
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Password</label>
                {{-- Ini dibikin jadi input-group biar iconnya nempel di kanan --}}
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                    <span class="input-group-text bg-white toggle-password" id="togglePasswordBtn">
                        <i class="fas fa-eye text-muted" id="eyeIcon"></i>
                    </span>
                </div>
            </div>
            
            <button type="submit" class="btn btn-orange mb-3">Masuk</button>
        </form>
        
        <p class="text-center text-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar di sini</a></p>
    </div>

    {{-- Script JS buat buka-tutup mata password --}}
    <script>
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePasswordBtn.addEventListener('click', function () {
            // Cek tipe inputnya apa, kalau password ubah ke text, dan sebaliknya
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Ganti icon mata terbuka (fa-eye) / tertutup (fa-eye-slash)
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>