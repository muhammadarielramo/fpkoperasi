@extends('layouts.admin.app', ['title' => 'Edit Data Kolektor'])

@section('content')

<div class="section-body">
    <h2 class="section-title">Detail Kolektor</h2>
    <p class="section-lead">Edit informasi Kolektor: {{ $kolektor->user->name }}</p>

    <div class="card">
        <div class="card-header">
            <h4>Edit Profil Kolektor</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('kolektor.update', $kolektor->id) }}" method="POST">
                @csrf
                @method('PUT')

                <table class="table table-bordered table-sm">
                    <tr>
                        <th class="bg-light text-dark" style="width: 180px;">ID</th>
                        <td>
                            <input type="text" class="form-control" value="{{ $kolektor->id }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-dark">Nama</th>
                        <td>
                            <input type="text" name="name" class="form-control" value="{{ $kolektor->user->name }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-dark">Email</th>
                        <td>
                            <input type="email" name="email" class="form-control" value="{{ $kolektor->user->email }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-dark">No. HP</th>
                        <td>
                            <input type="text" name="phone_number" class="form-control" value="{{ $kolektor->user->phone_number }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-dark">Username</th>
                        <td>
                            <input type="text" name="username" class="form-control" value="{{ $kolektor->user->username }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-dark">Status</th>
                            <td>
                                <select name="status" class="form-control" required>
                                    <option value="aktif" {{ $kolektor->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ $kolektor->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="Cuti" {{ $kolektor->status == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                </select>
                            </td>
                    </tr>
                </table>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('admin.data-anggota', $kolektor->id) }}" class="btn btn-secondary">← Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

