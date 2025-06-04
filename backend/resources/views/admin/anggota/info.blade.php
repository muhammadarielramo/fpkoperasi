@extends('layouts.admin.app', ['title' => 'Anggota'])

@section('content')
<div class="section-body">
    <h2 class="section-title">Detail Anggota</h2>
    <p class="section-lead">Informasi lengkap anggota: {{ $member->user->name }}</p>

    <div class="card">
        <div class="card-header">
            <h4>Profil Anggota</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <tr>
                    <th class="bg-light text-dark" style="width: 180px;">ID</th>
                    <td>{{ $member->id }}</td>
                </tr>
                <tr>
                    <th class="bg-light text-dark" style="width: 180px;">NIK</th>
                    <td>{{ $member->nik }}</td>
                </tr>
                <tr>
                    <th class="bg-light text-dark" style="width: 180px;">Nama</th>
                    <td>{{ $member->user->name }}</td>
                </tr>
                <tr>
                    <th class="bg-light text-dark" style="width: 180px;">Email</th>
                    <td>{{ $member->user->email }}</td>
                </tr>
                <tr>
                    <th class="bg-light text-dark" style="width: 180px;">No. HP</th>
                    <td>{{ $member->user->phone_number }}</td>
                </tr>
                <tr>
                    <th class="bg-light text-dark" style="width: 180px;">Alamat</th>
                    <td>{{ $member->address}}</td>
                </tr>
            </table>

            <div class="d-flex justify-content-between mt-3">
                <!-- Tombol Kembali -->
                <form action="{{ route('admin.data-anggota') }}" method="GET">
                    <button type="submit" class="btn btn-secondary">← Kembali ke Daftar</button>
                </form>

                <!-- Tombol Edit & Hapus -->
                <div class="d-flex gap-2">
                    <form action="{{ route('anggota.edit', $member->id) }}" method="GET">
                        <button type="submit" class="btn btn-warning">Edit</button>
                    </form>
                    <form action="{{ route('anggota.hapus', $member->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
