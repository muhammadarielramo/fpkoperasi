<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Akun KOKITA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            padding: 20px;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0px 0px 10px #ddd;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin-top: 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Halo, {{ $data['name'] }}</h2>
        <p>Terima kasih telah mendaftar di <strong>KOKITA</strong>.</p>
        <p>Untuk menyelesaikan proses pendaftaran, silakan klik tombol di bawah ini untuk memverifikasi akun Anda:</p>

        <a href="{{ $data['link'] }}" class="button">Verifikasi Akun</a>

        <p>Jika kamu tidak merasa mendaftar di KOKITA, kamu bisa mengabaikan email ini.</p>

        <p>Salam,<br><strong>Tim KOKITA</strong></p>

        <div class="footer">
            &copy; {{ date('Y') }} KOKITA. All rights reserved.
        </div>
    </div>
</body>
</html>

