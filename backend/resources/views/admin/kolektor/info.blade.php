@extends('layouts.admin.app', ['title' => 'Data Kolektor'])

@section('content')
<div class="section-body">
    <h2 class="section-title">Detail Kolektor</h2>
    <p class="section-lead">Informasi lengkap kolektor: {{ $collector->user->name }}</p>

    <div class="card">
        <div class="card-header">
            <h4>Profil Kolektor</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <tr>
                    <th class="bg-light text-dark" style="width: 180px;">ID</th>
                    <td>{{ $collector->id }}</td>
                </tr>
                <tr>
                    <th class="bg-light text-dark" style="width: 180px;">Nama</th>
                    <td>{{ $collector->user->name }}</td>
                </tr>
                <tr>
                    <th class="bg-light text-dark" style="width: 180px;">Email</th>
                    <td>{{ $collector->user->email }}</td>
                </tr>
                <tr>
                    <th class="bg-light text-dark" style="width: 180px;">No. HP</th>
                    <td>{{ $collector->user->phone_number }}</td>
                </tr>
                <tr>
                    <th class="bg-light text-dark" style="width: 180px;">Username</th>
                    <td>{{ $collector->user->username }}</td>
                </tr>
                <tr>
                    <th class="bg-light text-dark" style="width: 180px;">Status</th>
                    <td>{{ $collector->status}}</td>
                </tr>
                <tr>
                    <th class="bg-light text-dark">Anggota Binaan</th>
                    <td>
                        <ul class="mb-0">
                            <!-- Contoh statis -->
                            <li>Ahmad Fauzi (NIK: 1234567890)</li>
                            <li>Siti Aminah (NIK: 9876543210)</li>
                            <li>Budi Santoso (NIK: 1122334455)</li>
                        </ul>
                    </td>
                </tr>

            </table>

            <div class="d-flex justify-content-between mt-3">
                <!-- Tombol Kembali -->
                <form action="{{ route('admin.data-kolektor') }}" method="GET">
                    <button type="submit" class="btn btn-primary">← Kembali ke Daftar</button>
                </form>
                <form action="{{ route('kolektor.anggota', $collector->id) }}" method="GET">
                    <button type="submit" class="btn btn-secondary">Tambah Anggota</button>
                </form>
                <!-- Tombol Edit & Hapus -->
                <div class="d-flex gap-2">
                    <form action="{{ route('kolektor.edit', $collector->id) }}" method="GET">
                        <button type="submit" class="btn btn-warning">Edit</button>
                    </form>
                    <form action="{{ route('kolektor.hapus', $collector->id) }}" method="POST">
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
