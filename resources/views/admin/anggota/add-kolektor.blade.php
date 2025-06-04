@extends('layouts.admin.app', ['title' => 'Tambah Kolektor'])

@section('content')
<div class="container">
    <h4>Tambah Kolektor untuk Anggota: {{ $member->nama }}</h4>

    <form action="{{ route('anggota.simpan-kolektor', $member->id) }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Tambah Kolektor</label>
                <select name="collector_id" class="form-control select2">
                    @foreach ($kolektor as $k)
                    <option value="{{ $k->id }}">{{ $k->user->name }}</option>
                    @endforeach
                </select>
        </div>

        <div class="mb-3">
            <label for="tanggal_penugasan" class="form-label">Tanggal Penugasan</label>
            <input type="date" class="form-control" id="tgl_penugasan" name="tgl_penugasan" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Kolektor</button>
        <a href="{{ route('admin.data-anggota') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
