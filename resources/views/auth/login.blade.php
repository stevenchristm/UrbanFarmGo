<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - UrbanFarm</title>
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
        .login-card {
            background: rgba(255, 255, 255, 0.85); 
            padding: 3rem; 
            border-radius: 24px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            width: 100%; max-width: 420px; text-align: center;
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
        .form-group { text-align: left; margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 8px; font-weight: 700; color: #1E293B; font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase; }
        input {
            width: 100%; padding: 14px 18px; border: 1px solid rgba(226, 232, 240, 0.8);
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
        .btn-login {
            background: #10B981; color: white; border: none; width: 100%;
            padding: 16px; border-radius: 16px; font-weight: 700; font-size: 1rem;
            cursor: pointer; margin-top: 1rem; font-family: inherit;
            transition: all 0.3s ease; box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.25);
        }
        .btn-login:hover {
            transform: translateY(-3px);
            background: #059669;
            box-shadow: 0 15px 30px -5px rgba(16, 185, 129, 0.35);
        }
        .error-msg { 
            background: rgba(239, 68, 68, 0.05); color: #EF4444; padding: 12px; 
            border-radius: 12px; font-size: 0.85rem; margin-bottom: 1.5rem; 
            border: 1px solid rgba(239, 68, 68, 0.2); font-weight: 600;
        }
        .success-msg { 
            background: rgba(16, 185, 129, 0.05); color: #10B981; padding: 12px; 
            border-radius: 12px; font-size: 0.85rem; margin-bottom: 1.5rem; 
            border: 1px solid rgba(16, 185, 129, 0.2); font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">🌿 UrbanFarm</div>
        <h2>Masuk ke Akun Petani Anda</h2>

        @if(session('success'))
            <div class="success-msg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error-msg">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email Petani</label>
                <input type="email" name="email" placeholder="nama@email.com" required>
            </div>

            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="password" placeholder="Masukkan kata sandi" required>
            </div>

            <button type="submit" class="btn-login">Masuk Sekarang</button>
        </form>

        <p style="font-size: 13px; margin-top: 20px; color: #7f8c8d;">
            Belum punya akun? <a href="{{ route('register') }}" style="color: #27ae60; text-decoration: none; font-weight: bold;">Daftar di sini</a>
        </p>
    </div>
</body>
</html>