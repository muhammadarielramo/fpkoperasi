@extends('layouts.admin.app', ['title' => 'Registrasi Anggota'])

<link rel="stylesheet" href="{{ asset('assets/css/selectric.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- 1. Load jQuery dulu -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- 2. Lalu baru plugin atau file JS lain yang butuh jQuery -->
<script src="{{ asset('assets/js/jquery.selectric.min.js') }}"></script>
<script src="{{ asset('assets/js/page/features-posts.js') }}"></script>


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
                                        @if($p->member && $p->member->foto_ktp)
                                            <!-- Tombol untuk membuka modal -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#ktpModal{{ $p->member->id }}">Lihat KTP</a>

                                            <!-- Modal -->
                                            <div class="modal fade" id="ktpModal{{ $p->member->id }}" tabindex="-1" aria-labelledby="ktpModalLabel{{ $p->member->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ktpModalLabel{{ $p->member->id }}">Foto KTP</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="{{ asset('storage/' . $p->member->foto_ktp) }}" alt="KTP {{ $p->name ?? '' }}" class="img-fluid rounded">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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

  <script src="{{ asset('assets/js/jquery.selectric.min.js') }}"></script>

  <script src="../assets/js/page/features-posts.js"></script>



@endsection
