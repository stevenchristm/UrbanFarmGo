<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Petani - UrbanFarm</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F9FAFB;
            background-image: radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.05) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(217, 119, 87, 0.05) 0px, transparent 50%);
            display: flex; justify-content: center; align-items: center;
            height: 100vh; margin: 0;
        }
        .register-card {
            background: rgba(255, 255, 255, 0.85); 
            padding: 3rem; 
            border-radius: 24px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            width: 100%; max-width: 450px; text-align: center;
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .logo { 
            font-size: 2rem; font-weight: 800; 
            background: linear-gradient(135deg, #1E293B, #10B981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem; 
        }
        h2 { color: #64748B; margin-bottom: 2rem; font-size: 1.05rem; font-weight: 600; letter-spacing: -0.3px; }
        .form-group { text-align: left; margin-bottom: 1.2rem; }
        label { display: block; margin-bottom: 6px; font-weight: 700; color: #1E293B; font-size: 0.8rem; letter-spacing: 0.5px; text-transform: uppercase; }
        input {
            width: 100%; padding: 12px 16px; border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px; box-sizing: border-box; outline: none;
            background: #F9FAFB; font-family: inherit; font-size: 0.95rem; color: #1E293B;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }
        input:focus {
            border-color: #10B981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1), inset 0 2px 4px rgba(0,0,0,0.02);
            background: #fff;
        }
        .btn-regis {
            background: #10B981; color: white; border: none; width: 100%;
            padding: 16px; border-radius: 16px; font-weight: 700; font-size: 1rem;
            cursor: pointer; margin-top: 1rem; font-family: inherit;
            transition: all 0.3s ease; box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.25);
        }
        .btn-regis:hover {
            transform: translateY(-3px);
            background: #059669;
            box-shadow: 0 15px 30px -5px rgba(16, 185, 129, 0.35);
        }
        .footer-text { margin-top: 1.5rem; font-size: 0.9rem; color: #64748B; font-weight: 500; }
        .footer-text a { color: #10B981; text-decoration: none; font-weight: 700; transition: 0.3s; }
        .footer-text a:hover { color: #059669; }
        
        .error-msg { color: #EF4444; font-size: 0.8rem; margin-top: 6px; font-weight: 600; padding-left: 5px; }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="logo">🌿 UrbanFarm</div>
        <h2>Mulai Petualangan Bertani Anda</h2>
        
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
                @error('name') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Email Petani</label>
                <input type="email" name="email" placeholder="nama@email.com" value="{{ old('email') }}" required>
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="password" placeholder="Minimal 8 karakter" required>
                @error('password') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required>
            </div>

            <button type="submit" class="btn-regis">Daftar Akun</button>
        </form>

        <div class="footer-text">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>
</body>
</html>