<x-app-layout>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">

            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Daftar Anggota</h6>
                                    <p class="text-sm">Anggota Koperasi Kita</p>
                                </div>
                                <div class="input-group w-sm-25 ms-auto">
                                    <span class="input-group-text text-body">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z">
                                            </path>
                                        </svg>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Search">
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">ID</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Nama</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Email</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Username</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Nomor Telepon</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Alamat</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">NIK</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Tanggal Lahir</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Jenis Kelamin</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Foto KTP</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.85rem;">
                                        @foreach ($members as $member)
                                            <tr>
                                                <td>{{ $member->id }}</td>
                                                <td>{{ $member->user->name ?? '-' }}</td>
                                                <td>{{ $member->user->email ?? '-' }}</td>
                                                <td>{{ $member->user->username ?? '-' }}</td>
                                                <td>{{ $member->user->phone ?? '-' }}</td>
                                                <td>{{ $member->address }}</td>
                                                <td>{{ $member->nik }}</td>
                                                <td>{{ $member->bod }}</td>
                                                <td>{{ ucfirst($member->gender) }}</td>
                                                <td>
                                                    @if ($member->foto_ktp)
                                                        <a href="{{ asset('storage/ktp/' . $member->foto_ktp) }}" target="_blank" class="btn btn-sm btn-primary">
                                                            Lihat KTP
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="/anggota/edit/{{$member->id}}" class="btn btn-warning btn-sm">Edit</a>

                                                    <form action="{{ route('member.destroy', $member->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">Hapus</button>
                                                    </form>

                                                    <a href="{{ route('relasi.create', ['id' => $member->id]) }}" class="btn btn-primary btn-sm">Tambah Kolektor</a>

                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>


                            </div>
                            <div class="border-top py-3 px-3 d-flex align-items-center">
                                <p class="font-weight-semibold mb-0 text-dark text-sm">Page 1 of 10</p>
                                <div class="ms-auto">
                                    <button class="btn btn-sm btn-white mb-0">Previous</button>
                                    <button class="btn btn-sm btn-white mb-0">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</x-app-layout>

