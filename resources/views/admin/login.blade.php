<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Perpustakaan Desa</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #1a3c2e; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: #fff; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); padding: 36px; width: 100%; max-width: 380px; }
        .card-title { font-size: 1.3rem; font-weight: bold; text-align: center; color: #2c7a3f; margin-bottom: 6px; }
        .card-subtitle { text-align: center; color: #888; font-size: 0.88rem; margin-bottom: 24px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.9rem; color: #333; }
        input { width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.95rem; outline: none; transition: border-color 0.2s; }
        input:focus { border-color: #2c7a3f; }
        .btn { width: 100%; padding: 12px; background: #2c7a3f; color: #fff; border: none; border-radius: 6px; font-size: 1rem; font-weight: bold; cursor: pointer; }
        .btn:hover { background: #235f31; }
        .alert-error { background: #fde8e8; border: 1px solid #f5c6c6; color: #c0392b; padding: 10px 14px; border-radius: 6px; font-size: 0.88rem; margin-bottom: 16px; }
    </style>
</head>
<body>
<div class="card">
    <div class="card-title">🔒 Admin Panel</div>
    <div class="card-subtitle">Perpustakaan Desa</div>

    @if($errors->has('login'))
        <div class="alert-error">{{ $errors->first('login') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username" required autofocus>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </div>
        <button class="btn" type="submit">Masuk</button>
    </form>
</div>
</body>
</html>
