<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Flight Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Tambahan FontAwesome buat nampilin icon mata --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f5f6fa; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card-custom { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 450px; padding: 30px; background: white;}
        .btn-orange { background-color: #ff5e1f; color: white; font-weight: bold; padding: 12px; border-radius: 8px; width: 100%; }
        .btn-orange:hover { background-color: #e04a11; color: white; }
        /* Biar kursornya jadi tangan pas disorot ke icon mata */
        .toggle-password { cursor: pointer; }
    </style>
</head>
<body>

    <div class="card-custom">
        <h3 class="text-center fw-bold mb-4 text-primary">Daftar Akun</h3>
        
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Alamat Email</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>
            
            {{-- Kolom Password Pertama --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <span class="input-group-text bg-white toggle-password" id="togglePasswordBtn">
                        <i class="fas fa-eye text-muted" id="eyeIcon"></i>
                    </span>
                </div>
            </div>
            
            {{-- Kolom Ulangi Password --}}
            <div class="mb-4">
                <label class="form-label fw-bold">Ulangi Password</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    <span class="input-group-text bg-white toggle-password" id="toggleConfirmPasswordBtn">
                        <i class="fas fa-eye text-muted" id="eyeIconConfirm"></i>
                    </span>
                </div>
            </div>
            
            <button type="submit" class="btn btn-orange mb-3">Daftar Sekarang</button>
        </form>
        
        <p class="text-center text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Log In</a></p>
    </div>

    {{-- Script JS buat buka-tutup mata password (dua-duanya) --}}
    <script>
        // Fitur mata untuk kolom Password Pertama
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePasswordBtn.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });

        // Fitur mata untuk kolom Ulangi Password
        const toggleConfirmPasswordBtn = document.getElementById('toggleConfirmPasswordBtn');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');

        toggleConfirmPasswordBtn.addEventListener('click', function () {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            eyeIconConfirm.classList.toggle('fa-eye');
            eyeIconConfirm.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>