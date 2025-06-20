@extends('layouts.admin.app', ['title' => 'Riwayat Pinjaman'])

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@section('content')
<section>

<div class="card">

    {{-- search --}}
    <div class="card-header d-flex justify-content-between align-items-center">
        <form method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nama..." value="{{ request('search') }}">
                    <div class="input-group-append">
                         <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
             </div>
         </form>
     </div>

     {{-- tabel --}}

    <div class="card-body p-0">
         <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID Pinjaman</th>
                        <th scope="col">Nama Anggota</th>
                        <th scope="col">Cicilan Ke-</th>
                        <th scope="col">Tanggal Transaksi</th>
                        <th scope="col">Nominal</th>
                        <th scope="col">Kolektor</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
            </table>
        </div>
    </div>

</section>

@endsection
