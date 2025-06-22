@extends('layouts.admin.app', ['title' => 'Pinjaman'])

@section('content')

                <div class="card">
                  <div class="card-header">
                    <h4>Data Pinjaman Anggota</h4>

                    <form method="GET" action="{{ route('pinjaman.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" id="searchInput" class="form-control" placeholder="🔍 Cari nama..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-striped table-md">
                        <tr>
                          <th>ID Pinjaman</th>
                          <th>Tanggal</th>
                          <th>ID Anggota</th>
                          <th>Nama Anggota</th>
                          <th>Tenor (Bulan)</th>
                          <th>Jumlah</th>
                          <th>Status</th>
                          <th>Aksi</th>
                        </tr>
                        @foreach ($loans as $l )
                        <tr>
                          <td>{{$l->id}}</td>
                          <td>{{$l->tgl_persetujuan}}</td>
                          <td>{{$l->member->id}}</td>
                          <td>{{$l->member->user->name}}</td>
                          <td>{{$l->tenor}}</td>
                          <td>Rp {{ number_format($l->jumlah_pinjaman, 0, ',', '.') }}</td>
                          <td>{{$l->status}}</td>
                          <td><a href="{{route('pinjaman.detail', $l->id)}}" class="btn btn-primary">Detail</a></td>
                        </tr>
                        @endforeach
                      </table>
                    </div>
                  </div>
                </div>
@endsection

@section('script')
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
            });
        </script>
    @endif

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
        });
    </script>
@endif
@endsection
