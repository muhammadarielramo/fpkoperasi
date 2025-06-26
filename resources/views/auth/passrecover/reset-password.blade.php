<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Anggota Kokita</title> <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #8B5CF6; /* Example primary color from the image */
            --light-purple: #EDE9FE;   /* Example lighter purple for background elements */
            --text-color: #333;
            --border-color: #ccc;
            --input-bg: #f9f9f9;
            --button-gradient-start: #9D74E3;
            --button-gradient-end: #8B5CF6;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #F8F8F8; /* Light grey background */
            position: relative;
            overflow: hidden; /* Hide overflow from background circles */
        }

        /* Background circles - simplified for web */
        .circle-bg {
            position: absolute;
            background-color: var(--light-purple);
            border-radius: 50%;
            opacity: 0.7;
            filter: blur(50px); /* Soften the circles */
        }

        .circle-bg.top-left {
            width: 300px;
            height: 300px;
            top: -100px;
            left: -100px;
        }

        .circle-bg.bottom-right {
            width: 400px;
            height: 400px;
            bottom: -150px;
            right: -150px;
        }

        /* Login Container */
        .login-container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 25px var(--shadow-color);
            padding: 40px;
            width: 100%;
            max-width: 400px; /* Max width for web */
            text-align: center;
            position: relative; /* For z-index to be above circles */
            z-index: 10;
        }

        .login-container h1 {
            font-size: 28px;
            color: var(--text-color);
            margin-bottom: 30px;
            font-weight: 600;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 15px;
            color: var(--text-color);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-group input {
            width: calc(100% - 20px); /* Adjust for padding */
            padding: 12px 10px;
            border: none;
            border-bottom: 1px solid var(--border-color);
            outline: none;
            font-size: 16px;
            color: var(--text-color);
            background-color: var(--input-bg); /* Light background for input */
            border-radius: 5px 5px 0 0; /* Match the design's rounded top, flat bottom */
        }

        .form-group input:focus {
            border-bottom-color: var(--primary-purple);
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle input {
            width: calc(100% - 60px); /* Adjust for icon */
            padding-right: 40px;
        }

        .password-toggle .toggle-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--border-color);
            font-size: 20px;
            user-select: none; /* Mencegah teks terpilih saat klik icon */
        }

        .password-toggle .toggle-icon:hover {
            color: var(--primary-purple);
        }

        /* Forgot Password Link - tidak relevan untuk reset password, bisa dihapus atau diabaikan */
        .forgot-password {
            text-align: right;
            margin-top: -15px; /* Pull up a bit to be closer to password field */
            margin-bottom: 25px;
        }

        .forgot-password a {
            color: var(--primary-purple);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 15px;
            background-image: linear-gradient(to right, var(--button-gradient-start), var(--button-gradient-end));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        /* Back Arrow for Mobile */
        .back-arrow {
            position: absolute;
            top: 40px;
            left: 40px;
            font-size: 30px;
            color: var(--text-color);
            text-decoration: none;
            display: none; /* Hidden by default on web */
            z-index: 20; /* Ensure it's above everything */
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            body {
                align-items: flex-start; /* Start from top for mobile */
                padding-top: 60px; /* Add some top padding */
            }

            .back-arrow {
                display: block; /* Show back arrow on mobile */
                top: 20px;
                left: 20px;
            }

            .login-container {
                margin-top: 20px;
                max-width: 90%; /* Adjust max width for mobile */
                padding: 30px 20px; /* Adjust padding for mobile */
                box-shadow: none; /* Remove box shadow on mobile for a cleaner look */
                border-radius: 0; /* Remove border radius to span full width */
                min-height: 100vh; /* Make it take full height on mobile */
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
            }

            .login-container h1 {
                font-size: 24px;
                margin-bottom: 25px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-group label {
                font-size: 14px;
            }

            .form-group input {
                padding: 10px 10px;
                font-size: 15px;
            }

            .forgot-password {
                margin-top: -10px;
                margin-bottom: 20px;
            }

            .btn-submit {
                padding: 12px;
                font-size: 16px;
            }

            /* Adjust background circles for mobile */
            .circle-bg.top-left {
                width: 200px;
                height: 200px;
                top: -50px;
                left: -50px;
                filter: blur(30px);
            }

            .circle-bg.bottom-right {
                width: 250px;
                height: 250px;
                bottom: -80px;
                right: -80px;
                filter: blur(30px);
            }
        }
    </style>
</head>
<body>
    <div class="circle-bg top-left"></div>
    <div class="circle-bg bottom-right"></div>

    <a href="#" class="back-arrow">&#8592;</a>
    <div class="login-container">
        <h1>Reset Password</h1> <form method="POST" action="{{ route('password.update', ['token' => $token]) }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group password-toggle">
                <label for="password">Password Baru:</label>
                <input type="password" id="password" name="password" required>
                <span class="toggle-icon" onclick="togglePasswordVisibility('password')">👁️</span>
            </div>

            <div class="form-group password-toggle">
                <label for="password_confirmation">Konfirmasi Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                <span class="toggle-icon" onclick="togglePasswordVisibility('password_confirmation')">👁️</span>
            </div>

            <button type="submit" class="btn-submit">Reset Password</button>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function togglePasswordVisibility(id) {
                const input = document.getElementById(id);
                const icon = input.nextElementSibling; // Asumsi icon adalah elemen setelah input

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = '👁️‍🗨️'; // Ubah ikon menjadi mata terbuka
                } else {
                    input.type = 'password';
                    icon.textContent = '👁️'; // Ubah ikon menjadi mata tertutup
                }
            }

            // Script SweetAlert untuk notifikasi sukses/error
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
            });
            @endif

            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
            });
            @endif

            // Menampilkan error validasi Laravel (jika ada)
            @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                html: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            });
            @endif
        </script>
    </div>
</body>
</html>
