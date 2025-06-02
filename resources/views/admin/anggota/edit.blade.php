@extends('layouts.admin.app', ['title' => 'Anggota'])

@section('content')

<div class="section-body">
    <h2 class="section-title">Detail Anggota</h2>
    <p class="section-lead">Edit informasi anggota: {{ $member->user->name }}</p>

    <div class="card">
        <div class="card-header">
            <h4>Edit Profil Anggota</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('anggota.update', $member->id) }}" method="POST">
                @csrf
                @method('PUT')

                <table class="table table-bordered table-sm">
                    <tr>
                        <th class="bg-light text-dark" style="width: 180px;">ID</th>
                        <td>
                            <input type="text" class="form-control" value="{{ $member->id }}" disabled>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-dark">NIK</th>
                        <td>
                            <input type="text" name="nik" class="form-control" value="{{ $member->nik }}" disabled>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-dark">Nama</th>
                        <td>
                            <input type="text" name="name" class="form-control" value="{{ $member->user->name }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-dark">Email</th>
                        <td>
                            <input type="email" name="email" class="form-control" value="{{ $member->user->email }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-dark">No. HP</th>
                        <td>
                            <input type="text" name="phone_number" class="form-control" value="{{ $member->user->phone_number }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-dark">Alamat</th>
                        <td>
                            <textarea name="address" class="form-control" rows="2" required>{{ $member->address }}</textarea>
                        </td>
                    </tr>
                </table>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('admin.data-anggota', $member->id) }}" class="btn btn-secondary">← Batal</a>
                    <form action="{{ route('anggota.update', $member->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

