<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Flight Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f6fa; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card-custom { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 450px; padding: 30px; background: white;}
        .btn-orange { background-color: #ff5e1f; color: white; font-weight: bold; padding: 12px; border-radius: 8px; width: 100%; }
        .btn-orange:hover { background-color: #e04a11; color: white; }
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
            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Ulangi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-orange mb-3">Daftar Sekarang</button>
        </form>
        
        <p class="text-center text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Log In</a></p>
    </div>

</body>
</html>