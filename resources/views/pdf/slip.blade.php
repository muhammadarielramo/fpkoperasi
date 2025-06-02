<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 20px; }
        .title { text-align: center; font-size: 20px; margin-bottom: 20px; }
        .box { border: 1px solid #000; padding: 15px; border-radius: 10px; }
        .label { font-weight: bold; width: 150px; display: inline-block; }
        .footer { margin-top: 30px; text-align: center; font-style: italic; font-size: 12px; }
    </style>
</head>
<body>
    <div class="title">Slip Pembayaran</div>

    <div class="box">
        <p><span class="label">Nama Anggota:</span> {{ $installment->loan->member->user->name }}</p>
        <p><span class="label">ID Pinjaman:</span> {{ $installment->id_loan ?? '-' }}</p>
        <p><span class="label">Tanggal Pembayaran:</span> {{ $installment->tgl_pembayaran->format('d-m-Y') }}</p>
        <p><span class="label">Nominal:</span> Rp {{ number_format($installment->besar_ciclan, 0, ',', '.') }}</p>
        <p><span class="label">Sisa Hutang:</span> Rp {{ number_format($remainingDebt, 0, ',', '.') }}</p>
    </div>

    <div class="footer">
        Terima kasih telah melakukan pembayaran.
    </div>
</body>
</html>
