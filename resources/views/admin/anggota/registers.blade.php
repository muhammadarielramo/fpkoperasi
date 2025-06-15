@extends('layouts.admin.app', ['title' => 'Registrasi Anggota'])

<link rel="stylesheet" href="../node_modules/selectric/public/selectric.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
<section class="section">
    <div class="section-body">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3 w-100">
                {{-- Search form (kiri) --}}
                <form method="GET" action="{{ route('admin.pendaftaran-anggota') }}" class="d-flex" style="max-width: 400px;">
                    <input type="text" name="search" id="searchInput" class="form-control" placeholder="🔍 Cari nama..." value="{{ request('search') }}">
                    <button class="btn btn-primary ml-2"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No HP</th>
                                    <th>KTP</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registers as $p)
                                <tr>
                                    <td>{{ optional($p->created_at)->format('d-m-Y') }}</td>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->email }}</td>
                                    <td>{{ $p->phone_number }}</td>
                                    <td>
                                        @if($p->ktp)
                                            <a href="{{ asset('storage/' . $p->ktp) }}" target="_blank">Lihat KTP</a>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('register.terima', ['id' => $p->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="Diterima">
                                            <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                        </form>

                                         <form action="{{ route('register.tolak', ['id' => $p->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                            <script>
                            @if(session('success'))
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: '{{ session('success') }}',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            @elseif(session('error'))
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: '{{ session('error') }}',
                                    timer: 5000,
                                    showConfirmButton: false
                                });
                            @endif
                            </script>
                    </div>
    </div>
                <div class="d-flex justify-content-center">
                    {{ $registers->links() }}
                </div>
              </div>
            </div>
        </div>
    </div>
</section>

  <script src="../node_modules/selectric/public/jquery.selectric.min.js"></script>

  <script src="../assets/js/page/features-posts.js"></script>



@endsection
