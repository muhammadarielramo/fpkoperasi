@extends('layouts.admin.app', ['title' => 'Pengajuan Pinjaman'])

<link rel="stylesheet" href="../node_modules/selectric/public/selectric.css">

@section('content')
<section>

<div class="container my-4">

    <!-- Filter dan Search -->
    <form method="GET" action="{{ route('pinjaman.pengajuan') }}" class="row row-cols-lg-auto g-2 align-items-center mb-3">
        <div class="col-12">
            <label class="visually-hidden" for="statusSelect">Status</label>
            <select name="status" id="statusSelect" class="form-select">
                <option value="">🔽 Filter Status</option>
                <option value="Diajukan" {{ request('status') == 'Diajukan' ? 'selected' : '' }}>🕒 Diajukan</option>
                <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>✅ Diterima</option>
                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
            </select>
        </div>

        <div class="col-12">
            <input type="text" name="search" id="searchInput" class="form-control" placeholder="🔍 Cari nama..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">
                🔎 Tampilkan
            </button>
        </div>
    </form>

    <!-- Tabel -->
    <table class="table table-bordered table-striped table-sm align-middle">
        <thead class="table-secondary">
            <tr>
                <th>Nama</th>
                <th>Tanggal Pengajuan</th>
                <th>Nominal</th>
                <th>Tenor</th>
                <th>Terakhir diubah</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengajuan->sortByDesc('tgl_pengajuan') as $p)
                <tr>
                    <td>{{ $p->member->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tgl_pengajuan)->format('d M Y') }}</td>
                    <td>Rp {{ number_format($p->jumlah_pinjaman, 0, ',', '.') }}</td>
                    <td>{{ $p->tenor }} bulan</td>
                    <td>{{ \Carbon\Carbon::parse($p->updated_at)->diffForHumans() }}</td>
                    <td>
                        <span class="badge
                            @if($p->status == 'Diterima') bg-success
                            @elseif($p->status == 'Ditolak') bg-danger
                            @else bg-secondary
                            @endif">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td>
                        @if($p->status == 'Diajukan')
                        <form action="{{ route('pinjaman.updateStatus', $p->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Diterima">
                            <button type="submit" class="btn btn-success btn-sm">✔ Terima</button>
                        </form>
                        <form action="{{ route('pinjaman.updateStatus', $p->id) }}" method="POST" class="d-inline ms-1">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Ditolak">
                            <button type="submit" class="btn btn-danger btn-sm">✖ Tolak</button>
                        </form>
                        @else
                        <em>-</em>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Tidak ada data pengajuan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>


        {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $pengajuan->links() }}
    </div>
</div>

</section>
@endsection
