@extends('layouts.admin.app', ['title' => 'Detail Pinjaman'])
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@section('content')
<section>

<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Detail Pinjaman & Cicilan</h5>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">Nama Anggota</dt>
                <dd class="col-sm-8">{{$loan->member->user->name}}</dd>

                <dt class="col-sm-4">Tanggal Pengajuan</dt>
                <dd class="col-sm-8">{{ \Carbon\Carbon::parse($loan->tgl_pengajuan)->format('d M Y') }}</dd>

                <dt class="col-sm-4">Tanggal Persetujuan</dt>
                <dd class="col-sm-8">{{ \Carbon\Carbon::parse($loan->tgl_persetujuan)->format('d M Y') }}</dd>

                <dt class="col-sm-4">Nominal Pinjaman</dt>
                <dd class="col-sm-8">Rp {{ number_format($loan->jumlah_pinjaman, 0, ',', '.') }}</dd>
            </dl>

            <hr>

            <h6 class="mb-3">Detail Cicilan</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nominal Cicilan</th>
                            <th>Tanggal Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalCicilan = 0;
                        @endphp

                        @foreach ($loan->installments as $i => $installment)
                        <tr>
                            <td>Cicilan {{ $installment->cicilan_ke }}</td>
                            <td>Rp {{ number_format($installment->besar_ciclan, 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($installment->tgl_pembayaran)->format('d M Y') }}</td>
                        </tr>

                        @php
                            $totalCicilan += $installment->besar_ciclan;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>

            <dl class="row mt-3">
                <dt class="col-sm-4">Total Cicilan Dibayar</dt>
                <dd class="col-sm-8">Rp {{ number_format($totalCicilan, 0, ',', '.') }}</dd>

                <dt class="col-sm-4">Sisa Pinjaman</dt>
                <dd class="col-sm-8">Rp {{ number_format($loan->jumlah_pinjaman - $totalCicilan, 0, ',', '.') }}</dd>
            </dl>

            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('pinjaman.cicil-show', $loan->id) }}') }}" class="btn btn-primary">Tambah Cicilan</a>

                <form action="{{ route('pinjaman.lunas', $loan->id) }}" method="POST" onsubmit="return confirm('Tandai pinjaman ini sebagai lunas?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm">✔️ Lunas</button>
                </form>
            </div>

        </div>
    </div>

    <a href="javascript:history.back()" class="btn btn-secondary">
        &larr; Kembali
    </a>

</div>

</section>
@endsection
