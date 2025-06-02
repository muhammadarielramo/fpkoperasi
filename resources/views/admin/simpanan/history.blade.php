@extends('layouts.admin.app', ['title' => 'Riwayat Simpanan'])
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


@section('content')
<div class="card">
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
    <div class="card-body p-0">
         <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID Riwayat</th>
                        <th scope="col">Nama Anggota</th>
                        <th scope="col">Jenis Simpanan</th>
                        <th scope="col">Tanggal Transaksi</th>
                        <th scope="col">Jumlah Pembayaran</th>
                        {{-- <th scope="col">Saldo</th> --}}
                        <th scope="col">Kolektor</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($histories as $history)
                            <tr>
                                <td>{{$history->id}}</td>
                                <td>{{$history->member->user->name}}</td>
                                <td>{{$history->deposit->jenis_simpanan}}</td>
                                <td>{{$history->tgl_transaksi}}</td>
                                <td>{{$history->jumlah}}</td>
                                {{-- <td>{{$history->jumlah}}</td> --}}
                                <td>{{$history->collector->user->name}}</td>
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
    </div>

    {{-- pagination --}}
    <div class="card-footer">
        {{-- {{ $histories->links() }} --}}
    </div>
</div>
@endsection
