<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - Flight Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f6fa; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card-custom { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 400px; padding: 30px; background: white;}
        .btn-orange { background-color: #ff5e1f; color: white; font-weight: bold; padding: 12px; border-radius: 8px; width: 100%; }
        .btn-orange:hover { background-color: #e04a11; color: white; }
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
                <input type="password" name="password" class="form-control form-control-lg" required>
            </div>
            <button type="submit" class="btn btn-orange mb-3">Masuk</button>
        </form>
        
        <p class="text-center text-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar di sini</a></p>
    </div>

</body>
</html>