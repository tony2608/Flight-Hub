<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRD Dashboard - Flight Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f5f6fa; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="fas fa-user-shield me-2"></i> HRD Portal</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 small"><i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }} (HRD)</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-sign-out-alt"></i> Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        
        @if(session('success'))
            <div class="alert alert-success fw-bold shadow-sm"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>
        @endif

        {{-- FITUR SEARCH --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <form action="{{ route('hrd.dashboard') }}" method="GET" class="d-flex shadow-sm rounded">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0 form-control-lg" placeholder="Cari Kode Booking, Nama, atau Email User..." value="{{ $search ?? '' }}">
                        <button type="submit" class="btn btn-primary px-4 fw-bold">Cari Data</button>
                        
                        @if($search)
                            <a href="{{ route('hrd.dashboard') }}" class="btn btn-danger px-4 fw-bold d-flex align-items-center"><i class="fas fa-times me-2"></i> Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            {{-- KIRI: TABEL PEMBATALAN --}}
            <div class="col-md-7 mb-4">
                <div class="card card-custom">
                    <div class="card-header bg-white fw-bold py-3"><i class="fas fa-file-invoice text-danger me-2"></i> Permintaan Batal (Pending)</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Alasan User</th>
                                        <th>Total Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingCancels as $trx)
                                    <tr>
                                        <td class="fw-bold text-primary">{{ $trx->booking_code }}</td>
                                        <td class="small">{{ $trx->cancel_reason }}</td>
                                        <td class="fw-bold">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('hrd.approve', $trx->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-success fw-bold shadow-sm" onclick="return confirm('ACC pembatalan ini? Sistem akan otomatis memotong biaya admin 10% untuk refund.')">
                                                    <i class="fas fa-check me-1"></i> ACC
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-5 text-muted"><i class="fas fa-check-circle fa-2x mb-2 text-success d-block"></i> Semua permintaan pembatalan sudah diselesaikan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KANAN: TABEL MANAJEMEN ROLE --}}
            <div class="col-md-5">
                <div class="card card-custom">
                    <div class="card-header bg-white fw-bold py-3"><i class="fas fa-users-cog text-primary me-2"></i> Manajemen Jabatan Akun</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama & Email</th>
                                        <th>Jabatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            <div class="small text-muted">{{ $user->email }}</div>
                                        </td>
                                        <td>
                                            <form action="{{ route('hrd.updateRole', $user->id) }}" method="POST" class="d-flex gap-2">
                                                @csrf
                                                <select name="role" class="form-select form-select-sm bg-light">
                                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
                                                    <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Manager</option>
                                                    <option value="hrd" {{ $user->role == 'hrd' ? 'selected' : '' }}>HRD</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="2" class="text-center py-4 text-muted">Data akun tidak ditemukan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>