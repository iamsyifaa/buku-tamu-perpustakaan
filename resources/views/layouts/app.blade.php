<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan Desa')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); padding: 32px; width: 100%; max-width: 440px; }
        .card-title { font-size: 1.4rem; font-weight: bold; text-align: center; color: #2c7a3f; margin-bottom: 8px; }
        .card-subtitle { text-align: center; color: #666; font-size: 0.9rem; margin-bottom: 24px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.9rem; color: #333; }
        input[type="text"], input[type="password"], input[type="tel"] {
            width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.95rem;
            outline: none; transition: border-color 0.2s;
        }
        input:focus { border-color: #2c7a3f; }
        .btn { width: 100%; padding: 12px; background: #2c7a3f; color: #fff; border: none; border-radius: 6px; font-size: 1rem; font-weight: bold; cursor: pointer; transition: background 0.2s; }
        .btn:hover { background: #235f31; }
        .btn-outline { background: #fff; color: #2c7a3f; border: 2px solid #2c7a3f; }
        .btn-outline:hover { background: #f0f9f3; }
        .link-text { text-align: center; margin-top: 16px; font-size: 0.9rem; color: #666; }
        .link-text a { color: #2c7a3f; font-weight: bold; text-decoration: none; }
        .link-text a:hover { text-decoration: underline; }
        .error-msg { color: #c0392b; font-size: 0.85rem; margin-top: 4px; }
        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; align-items: center; justify-content: center; }
        .modal-overlay.active { display: flex; }
        .modal-box { background: #fff; border-radius: 10px; padding: 32px 28px; text-align: center; max-width: 360px; width: 90%; }
        .modal-icon { font-size: 3rem; margin-bottom: 12px; }
        .modal-title { font-size: 1.2rem; font-weight: bold; color: #2c7a3f; margin-bottom: 8px; }
        .modal-body { color: #444; font-size: 0.95rem; margin-bottom: 20px; line-height: 1.5; }
        .modal-id { font-size: 1.5rem; font-weight: bold; color: #2c7a3f; letter-spacing: 1px; background: #f0f9f3; padding: 10px 20px; border-radius: 6px; display: inline-block; margin: 10px 0; }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')

    @stack('modals')
    @stack('scripts')
</body>
</html>
