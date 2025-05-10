<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Anggota Kolektor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-4">
    <h2>Daftar Anggota</h2>
    <p>Dikelola oleh: <strong>{{ $name_collector }}</strong></p>

    @if($member->isEmpty())
        <div class="alert alert-warning">
            Tidak ada member yang ditemukan.
        </div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama anggota</th>
                    <th>Tanggal Penugasan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($member as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->member_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tgl_penugasan)->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('tabel-kolektor') }}" class="btn btn-secondary mt-3">← Kembali ke Daftar Kolektor</a>
</div>

</body>
</html>
