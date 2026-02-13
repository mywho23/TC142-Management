<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>404 | Halaman Tidak Ditemukan</title>

    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background: url("{{ asset('template/assets/images/404.png') }}") no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .overlay {
            background: rgba(0, 0, 0, 0.45);
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: #fff;
        }

        .content h1 {
            font-size: 80px;
            margin-bottom: 10px;
        }

        .content p {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .content a {
            display: inline-block;
            padding: 12px 28px;
            background: #0d6efd;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 15px;
        }

        .content a:hover {
            background: #0b5ed7;
        }
    </style>
</head>

<body>

    <div class="overlay"></div>

    <div class="content">
        <h1>404</h1>
        <p>Halaman tidak ditemukan atau kamu tidak memiliki akses</p>
        <a href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
    </div>

</body>

</html>