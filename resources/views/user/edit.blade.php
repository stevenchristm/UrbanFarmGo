<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Petani - UrbanFarm</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 400px; }
        h1 { color: #2c3e50; font-size: 22px; margin-top: 0; margin-bottom: 10px; text-align: center; }
        .subtitle { text-align: center; color: #7f8c8d; font-size: 14px; margin-bottom: 25px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-weight: 600; margin-bottom: 8px; color: #34495e; font-size: 14px; }
        input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; transition: 0.3s; }
        input:focus { border-color: #f39c12; outline: none; box-shadow: 0 0 5px rgba(243, 156, 18, 0.2); }
        .alert-error { background: #fff5f5; color: #e74c3c; padding: 10px; border-radius: 8px; border: 1px solid #fed7d7; margin-bottom: 20px; font-size: 13px; text-align: center; }
        .section-confirm { background: #fff9f0; padding: 20px; border-radius: 10px; border: 1px dashed #f39c12; margin-top: 25px; }
        .btn-update { background: #f39c12; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 20px; font-size: 15px; transition: 0.3s; }
        .btn-update:hover { background: #e67e22; transform: translateY(-1px); }
        .btn-cancel { display: block; text-align: center; margin-top: 15px; color: #95a5a6; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Edit Profil Petani</h1>
        <p class="subtitle">Perbarui informasi identitas petani kota Anda.</p>

        @if(session('error'))
            <div class="alert-error">⚠️ {{ session('error') }}</div>
        @endif

        <form action="{{ route('user.update', $user->id_user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="nama">Nama Lengkap:</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required placeholder="Contoh: Andi Wijaya">
            </div>
            
            <div class="form-group">
                <label for="email">Alamat Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="email@contoh.com">
            </div>

            <div class="section-confirm">
                <label style="color: #d35400;">Konfirmasi Keamanan:</label>
                <p style="color: #e67e22; font-size: 11px; margin-bottom: 10px;">Wajib masukkan password akun Anda untuk menyimpan perubahan.</p>
                <input type="password" name="password_konfirmasi" required placeholder="Masukkan password Anda" style="border: 1px solid #f39c12;">
            </div>

            <button type="submit" class="btn-update">Simpan Perubahan</button>
            <a href="{{ route('user.index') }}" class="btn-cancel">Batal dan Kembali</a>
        </form>
    </div>
</body>
</html>