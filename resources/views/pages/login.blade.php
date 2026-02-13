<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | TC 142</title>
    @vite(['resources/js/app.js'])
    @vite(['resources/css/login.css', 'resources/js/animasilogin.js'])

    <style>
        /* Kita panggil pake fungsi asset() Laravel biar jalannya ke server Laravel, bukan Vite */
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                url("{{ asset('template/assets/images/Image_fx.png') }}") no-repeat center center fixed !important;
            background-size: cover !important;
            min-height: 100vh;
            /* Pake min-height biar aman kalau layar pendek */
            margin: 0;
            display: flex;
            align-items: center;
            /* Tetap buat nengahin secara vertikal */
            justify-content: center;
            /* Tetap buat nengahin secara horizontal */
        }

        .login-brand-section {
            background: linear-gradient(rgba(30, 58, 138, 0.85), rgba(30, 58, 138, 0.85)),
                url("{{ asset('template/assets/images/Image_fx.png') }}") !important;
            background-size: cover !important;
            background-position: center !important;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body>
    <div class="d-flex flex-column align-items-center">

        <div style="width: 50%; max-width: 800px; padding: 0 15px;"> <img
                src="{{ asset('template/assets/images/SUNROSE.png') }}" alt="Brand Title"
                style="width: 100%; height: auto; display: block; margin: 0 auto 20px auto; filter: drop-shadow(0px 4px 8px rgba(0,0,0,0.5));">
        </div>

        <div class="login-container">
            <div class="login-form-section">
                <h2>Login</h2>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form id="formLogin" method="POST" action="{{ route('auth.login.process') }}">
                    @csrf
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Enter username" required autofocus>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Password" autocomplete="new-password"
                            required>
                    </div>

                    <button type="submit" class="btn-login">SIGN IN</button>
                </form>
            </div>

            <div class="login-brand-section">
                <div class="brand-title">
                    Training<br>Center<br>142<br>System
                </div>
                <div class="brand-line"></div>
                <div class="copyright">
                    Copyright © 2024 SunRose Project
                </div>
            </div>
        </div>
    </div>
</body>

</html>
