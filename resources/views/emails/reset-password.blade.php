<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
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
        <h2>Reset Password Anda</h2>
        <p>Halo,</p>
        <p>Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah ini untuk mengatur ulang password Anda:</p>

        <a href="{{ $url }}" class="button">Reset Password</a>

        <p>Jika Anda tidak merasa meminta reset password, abaikan saja email ini.</p>

        <div class="footer">
            &copy; {{ date('Y') }} KOKITA. Semua Hak Dilindungi.
        </div>
    </div>
</body>
</html>
