@extends('layouts.admin.app', ['title' => 'Anggota'])
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
<section>

    {{-- tombil dll --}}
    <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Detail Anggota</h4>
        <div>
        <!-- Edit -->
        <a href="{{ route('anggota.edit', $member->id) }}" class="btn btn-warning btn-sm me-1">✏️ Edit</a>

        <!-- Hapus -->
        <form action="{{ route('anggota.hapus', $member->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm me-1" onclick="return confirm('Yakin ingin menghapus anggota ini?')">🗑️ Hapus</button>
        </form>

        <!-- Tambah Kolektor -->
        <a href="{{ route('anggota.tambah-kolektor', $member->id) }}" class="btn btn-primary btn-sm me-1">➕ Tambah Kolektor</a>

        <!-- Kembali -->
        <a href="{{ route('admin.data-anggota') }}" class="btn btn-secondary btn-sm">⬅️ Kembali</a>
        </div>
    </div>
    </div>


{{-- detail --}}
<div class="card mb-3">
    <div class="card-body">
      <dl class="row mb-0">
        <dt class="col-sm-4">ID Anggota</dt>
        <dd class="col-sm-8">{{$member->id}}</dd>

        <dt class="col-sm-4">Nama Lengkap</dt>
        <dd class="col-sm-8">{{$member->user->name}}</dd>

        <dt class="col-sm-4">No HP</dt>
        <dd class="col-sm-8">{{$member->user->phone_number}}</dd>

        <dt class="col-sm-4">Email</dt>
        <dd class="col-sm-8">{{$member->user->email}}</dd>

        <dt class="col-sm-4">Username</dt>
        <dd class="col-sm-8">{{$member->user->username}}</dd>

        <dt class="col-sm-4">Alamat</dt>
        <dd class="col-sm-8">{{$member->address}}</dd>

        <dt class="col-sm-4">Kolektor</dt>
        <dd class="col-sm-8">{{$kolektor->collector->user->name ?? '-'}}</dd>
      </dl>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header bg-success text-white">💰 Rincian Simpanan</div>
    <div class="card-body">
      <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between">
          <span>Simpanan Wajib</span>
          <strong>Rp {{ number_format($simpananWajib, 0, ',', '.') }}</strong>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <span>Simpanan Pokok</span>
          <strong>Rp {{ number_format($simpananPokok, 0, ',', '.') }}</strong>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <span>Simpanan Sukarela</span>
          <strong>Rp {{ number_format($simpananSukarela, 0, ',', '.') }}</strong>
        </li>
        <li class="list-group-item d-flex justify-content-between bg-light">
          <strong>Total Simpanan</strong>
          <strong>Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</strong>
        </li>
      </ul>
    </div>
  </div>
{{-- detail end --}}

{{-- pinjaman --}}
<div class="card">
    <div class="card-header bg-info text-white">📄 Informasi Pinjaman</div>
    <div class="card-body">
      <h6>Pinjaman Sebelumnya</h6>
      <ul>
        @foreach ($pastLoans as $loan )
        <li>
            Rp {{ number_format($loan->jumlah_pinjaman, 0, ',', '.') }} - {{ $loan->status }} - Disetujui: {{ \Carbon\Carbon::parse($loan->tgl_persetujuan)->format('d M Y') }}
        </li>
        @endforeach
      </ul>

      <h6 class="mt-3">Pinjaman Saat Ini</h6>
      <p>
        <strong>Rp {{ number_format($loans->jumlah_pinjaman ?? 0, 0, ',', '.') }}</strong> - {{ $loans->status ?? '-' }} <br>
      </p>
    </div>
  </div>
{{-- pinjaman end --}}
</div>
</section>
@endsection
