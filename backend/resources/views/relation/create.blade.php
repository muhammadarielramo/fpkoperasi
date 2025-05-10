<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Relasi Kolektor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h3>Tambah Kolektor untuk Anggota</h3>

    <form action="{{ route('relasi.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="member" class="form-label">Nama Anggota</label>
            <input type="text" class="form-control" value="{{ $member->user->name }}" disabled>
            <input type="hidden" name="id_member" value="{{ $member->id }}">
        </div>

        <div class="mb-3">
            <label for="kolektor" class="form-label">Pilih Kolektor</label>
            <select name="id_collector" class="form-select" required>
                <option value="" disabled selected>-- Pilih Kolektor --</option>
                @foreach($kolektor as $k)
                    <option value="{{ $k->id }}">{{ $k->user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tgl_penugasan" class="form-label">Tanggal Penugasan</label>
            <input type="date" name="tgl_penugasan" class="form-control" required>
        </div>


        <button type="submit" class="btn btn-primary">Simpan Relasi</button>
        <a href="{{ route('tabel-anggota') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
