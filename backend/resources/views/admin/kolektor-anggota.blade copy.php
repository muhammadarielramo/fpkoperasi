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
                                    <p class="text-sm">Dikelola oleh: <strong>{{ $name_collector }}</strong></p>
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
                                    <input type="text" class="form-control" placeholder="Search anggota...">
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">#</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Nama Anggota</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Tanggal Penugasan</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.85rem;">
                                        @forelse ($member as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->member_name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->tgl_penugasan)->format('d M Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">Tidak ada anggota yang ditemukan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="border-top py-3 px-3 d-flex align-items-center">
                                <a href="{{ route('tabel-kolektor') }}" class="btn btn-secondary btn-sm">← Kembali ke Daftar Kolektor</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>
</x-app-layout>
