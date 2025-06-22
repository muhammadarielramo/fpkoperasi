@extends('layouts.admin.app', ['title' => 'Data Simpanan'])

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')

                <div class="card">
                     <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Data Simpanan Anggota</h4>
                        <form method="GET" action="{{ route('simpanan.index') }}">
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
                            <thead>
                                <tr>
                                    <th>Nama Anggota</th>
                                    <th>Simpanan Wajib</th>
                                    <th>Simpanan Pokok</th>
                                    <th>Simpanan Sukarela</th>
                                    <th>Jumlah Simpanan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                <tr>
                                    <td>{{ $data->name}}</td>
                                    <td>Rp {{ number_format($data->total_wajib, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($data->total_pokok, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($data->total_sukarela, 0, ',', '.') }}</td>
                                    <td><strong>Rp {{ number_format($data->total_simpanan, 0, ',', '.') }}</strong></td>
                                    <td><a href="{{route('simpanan.tambah-show', $data->id_member)}}" class="btn btn-primary">Tambah</a></td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                  </div>
                    <div class="d-flex justify-content-center">
                        {{ $datas->links() }}
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
