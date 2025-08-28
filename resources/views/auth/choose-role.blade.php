<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Role</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 100vh;">
    <h3 class="mb-4">Pilih Role untuk Login</h3>
    <div class="d-flex gap-4">
        {{-- Card otomatis tampil sesuai role --}}
        @foreach (Auth::user()->roles as $role)
            <div class="card text-center p-4" style="width: 200px;">
                <h5 class="card-title">Sebagai {{ ucfirst($role) }}</h5>
                    <form method="POST" action="{{ route('choose.role') }}">
                        @csrf
                        <input type="hidden" name="role" value="{{ $role }}">
                        <button type="submit" class="btn btn-primary mt-3">Masuk</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
