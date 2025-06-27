<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kokita - Aplikasi Manajemen Koperasi Simpan Pinjam</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat Alternates', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #9556FF; /* Purple color from the image */
            color: white;
            box-sizing: border-box;
            padding: 20px; /* Add some padding for smaller screens */
        }

        .container {
            text-align: center;
            background-color: #9556FF; /* Ensure container also has the purple background */
            padding: 30px;
            border-radius: 10px; /* Optional: slight rounding for the container */
            max-width: 500px; /* Limit the maximum width of the content */
            width: 100%; /* Make it responsive */
        }

        .logo img {
            width: 100px; /* Adjust logo size */
            height: auto;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: white; /* Ensure heading color is white */
        }

        p {
            font-size: 1.2em;
            margin-bottom: 30px;
            color: white; /* Ensure paragraph color is white */
        }

        .button-group a {
            display: block; /* Make buttons stack vertically */
            background-color: white;
            color: #6A0DAD; /* Purple text on white button */
            padding: 15px 30px;
            margin: 15px auto; /* Center buttons and add vertical spacing */
            text-decoration: none;
            border-radius: 50px; /* Make buttons rounded */
            font-size: 1.1em;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
            max-width: 250px; /* Limit button width */
            width: 100%; /* Make buttons responsive */
        }

        .button-group a:hover {
            background-color: #f0f0f0; /* Slightly darker white on hover */
            color: #5a0bae; /* Slightly darker purple on hover */
        }

        .footer {
            margin-top: auto;
            font-size: 0.9em;
            color: white;
            padding: 10px 0;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            h1 {
                font-size: 2em;
            }

            p {
                font-size: 1em;
            }

            .button-group a {
                padding: 12px 25px;
                font-size: 1em;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.8em;
            }

            p {
                font-size: 0.9em;
            }

            .button-group a {
                padding: 10px 20px;
                font-size: 0.9em;
            }
        }

        .logo img {
            width: 200px; /* Diperbesar ukuran logo di sini, dari 150px menjadi 200px */
            height: auto; /* Memastikan rasio aspek tetap terjaga */
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ asset('assets/img/LOGO APK FIX.png') }}" alt="Kokita Logo">
        </div>
        <h1>KOKITA</h1>
        <p>Aplikasi Manajemen Koperasi Simpan Pinjam</p>
        <div class="button-group">
            <a href="https://drive.google.com/file/d/1JN0eO4c4Q6KY7JQdQudh4_j7wfZTsAEe/view?usp=drive_link">Aplikasi Mobile</a>
            <a href="http://kokita.web.id">Aplikasi Web</a>
        </div>
        <div class="footer">
            <p>Kokita Team</p>
        </div>
    </div>
</body>
</html>
