@extends('layouts.admin.app', ['title' => 'Data Kolektor'])
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
<section>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
        <!-- Tambah Kolektor -->
        <a href="{{ route('kolektor.edit', $collector->id) }}" class="btn btn-warning btn-sm me-1">✏️ Edit</a>

        <!-- Kembali -->
        <a href="{{ route('admin.data-kolektor') }}" class="btn btn-secondary btn-sm">⬅️ Kembali</a>
        </div>

        <form action="{{ route('kolektor.hapus', $collector->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm me-1" onclick="return confirm('Yakin ingin menghapus anggota ini?')">🗑️ Hapus</button>
        </form>
    </div>

    <div class="card mb-3">
    <div class="card-body">
      <dl class="row mb-0">
        <dt class="col-sm-4">ID Kolektor</dt>
        <dd class="col-sm-8">{{$collector->id}}</dd>

        <dt class="col-sm-4">Nama Lengkap</dt>
        <dd class="col-sm-8">{{$collector->user->name}}</dd>

        <dt class="col-sm-4">No HP</dt>
        <dd class="col-sm-8">{{$collector->user->phone_number}}</dd>

        <dt class="col-sm-4">Email</dt>
        <dd class="col-sm-8">{{$collector->user->email}}</dd>

        <dt class="col-sm-4">Username</dt>
        <dd class="col-sm-8">{{$collector->user->username}}</dd>

      </dl>
    </div>
  </div>

  <div class="card">
    <div class="card-header bg-info text-white">📄 Informasi Anggota</div>
    <div class="card-body">
      <h6 class="mt-4">Anggota Binaan</h6>
      <ul class="list-group">
        @forelse ($members as $member)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ $member->member->user->name }} (NIK: {{ $member->member->nik }})
            <a href="{{ route('anggota.info', $member->member->id) }}" class="btn btn-sm btn-primary">Lihat Profil Anggota</a>
          </li>
        @empty
          <li class="list-group-item">Belum ada anggota binaan.</li>
        @endforelse
      </ul>
    </div>
  </div>

</div>

</section>

@endsection
