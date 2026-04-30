<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Petani Baru</title>
    <style>
        body { font-family: sans-serif; margin: 50px; background: #f4f7f6; }
        .card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 400px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; }
        .error-box { background: #ffebee; color: #c62828; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; }
    </style>
</head>
<body>

<div class="card">
    <h2>Daftarkan Petani Baru</h2>

    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.store') }}" method="POST">
        @csrf
        <label>Nama Lengkap:</label>
        <input type="text" name="nama" value="{{ old('nama') }}" required>

        <label>Email:</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Simpan Petani</button>
        <p style="text-align: center;"><a href="{{ route('user.index') }}">Batal</a></p>
    </form>
</div>

</body>
</html>