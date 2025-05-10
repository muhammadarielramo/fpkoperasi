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
                                    <h6 class="font-weight-semibold text-lg mb-0">Daftar Kolektor</h6>
                                    <p class="text-sm">Kolektor Koperasi Kita</p>
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
                                    <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2">
                                        <a href="{{ route('kolektor.create') }}" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2">
                                            <span class="btn-inner--icon">
                                                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="d-block me-2">
                                                    <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"/>
                                                </svg>
                                            </span>
                                            <span class="btn-inner--text">Tambah Kolektor</span>
                                        </a>

                                    </button>
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
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.85rem;">
                                        @foreach ($collectors as $collector)
                                            @if($collector->status == 'Aktif')
                                            <tr>
                                                <td>{{ $collector->id }}</td>
                                                <td>{{ $collector->user->name ?? '-' }}</td>
                                                <td>{{ $collector->user->email ?? '-' }}</td>
                                                <td>{{ $collector->user->username ?? '-' }}</td>
                                                <td>{{ $collector->user->phone_number ?? '-' }}</td>
                                                <td>{{ $collector->status ?? '-' }}</td>
                                                <td>
                                                    <a href="/kolektor/edit/{{$collector->id}}" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="/kolektor/{{$collector->id}}/anggota" class="btn btn-warning btn-sm">Lihat Anggota</a>

                                                    <form action="{{ route('kolektor.destroy', $collector->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kolektor ini?')">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>

                                            @endif
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
            <x-app.footer />
        </div>
    </main>

</x-app-layout>

