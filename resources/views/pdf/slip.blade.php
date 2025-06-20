<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Pembayaran Cicilan Koperasi Kokita</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        .slip-container {
            width: 100%;
            max-width: 600px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 30px;
            box-sizing: border-box;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-left {
            display: flex;
            align-items: center;
        }
        .header-left img {
            height: 50px; /* Adjust as needed for your logo */
            margin-right: 15px;
        }
        .header-left h1 {
            font-size: 20px;
            color: #333;
            margin: 0;
            font-weight: 700;
        }
        .header-right {
            text-align: right;
        }
        .header-right .koperasi-name {
            font-size: 18px;
            font-weight: 700;
            color: #857AFE; /* Tema warna utama */
            margin: 0;
        }
        .header-right .bank-logo {
            height: 30px; /* Adjust as needed for bank logo */
            margin-top: 5px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #555;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .data-section p {
            display: flex;
            margin: 8px 0;
            line-height: 1.4;
        }
        .data-section .label {
            font-weight: 700;
            color: #555;
            width: 150px; /* Lebar tetap untuk label */
            flex-shrink: 0; /* Mencegah label menyusut */
        }
        .data-section span:not(.label) {
            flex-grow: 1;
            color: #333;
        }
        .verifikasi-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            text-align: center;
        }
        .verifikasi-section p {
            font-size: 14px;
            color: #777;
            margin-bottom: 20px;
        }
        .signature-area {
            display: flex;
            justify-content: flex-end; /* Posisikan tanda tangan di kanan */
            margin-top: 40px;
            text-align: center;
        }
        .signature-box {
            width: 200px; /* Lebar kotak tanda tangan */
        }
        .signature-box .signature-line {
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
            margin-top: 60px; /* Jarak untuk tanda tangan */
            font-weight: 700;
            color: #333;
        }
        .signature-box .signature-label {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
        }
        .text-bold {
            font-weight: 700;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .slip-container {
                padding: 20px;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            .header-right {
                margin-top: 10px;
                text-align: left;
            }
            .header-left img {
                height: 40px;
            }
            .header-left h1 {
                font-size: 18px;
            }
            .header-right .koperasi-name {
                font-size: 16px;
            }
            .data-section .label {
                width: 120px;
            }
            .signature-area {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="slip-container">
        <div class="header">
            <div class="header-left">
                {{-- Ganti path logo Anda di sini --}}
                <h1>SLIP PEMBAYARAN CICILAN</h1>
            </div>
            <div class="header-right">
                <p class="koperasi-name">KOPERASI KOKITA</p>
                {{-- Opsional: Tambahkan logo bank jika koperasi berafiliasi --}}
                {{-- <img src="https://via.placeholder.com/60x30/0000FF/FFFFFF?text=BNI" alt="Bank Logo" class="bank-logo"> --}}
            </div>
        </div>

        <div class="section-title">DATA PEMBAYAR</div>
        <div class="data-section">
            <p><span class="label">Nama Anggota:</span> <span>{{ $data['nama'] }}</span></p>
            <p><span class="label">ID Pinjaman:</span> <span>{{ $data['id_pinjaman'] ?? '-' }}</span></p>
            <p><span class="label">Tanggal Pembayaran:</span> <span>{{ \Carbon\Carbon::parse($data['tgl_pembayaran'])->format('d-m-Y') }}</span></p>
            <p><span class="label">Nominal Pembayaran:</span> <span class="text-bold">Rp {{ number_format((float) $data['jumlah'], 0, ',', '.') }}</span></p>
            <p><span class="label">Sisa Hutang:</span> <span>Rp {{ number_format((float) $data['sisa_hutang'], 0, ',', '.') }}</span></p>
            <p><span class="label">Dibayarkan kepada:</span> <span>{{ $data['nama_kolektor'] ?? 'N/A' }}</span></p>
            {{-- Tambahkan informasi tambahan jika diperlukan --}}
            {{-- <p><span class="label">Keterangan:</span> <span>Pembayaran cicilan ke-X</span></p> --}}
        </div>

        <div class="verifikasi-section">
            <p>Dengan ini saya menyatakan informasi yang saya isi dalam slip pembayaran ini adalah benar. Saya bersedia menerima sanksi pembatalan pembayaran apabila melanggar pernyataan ini.</p>
        </div>

        <div class="signature-area">
            <div class="signature-box">
                <p style="margin-bottom: 0;">Karawang, {{ \Carbon\Carbon::parse($data['tgl_pembayaran'])->format('d F Y') }}</p>
                <div class="signature-line">{{ strtoupper($data['nama']) }}</div>
                <div class="signature-label">Nama Anggota</div>
            </div>
        </div>

    </div>
</body>
</html>
