@extends('layouts.admin.app', ['title' => 'Anggota'])

@section('content')

<div class="float-right">
                      <form>
                        <div class="input-group">
                          <input type="text" class="form-control" placeholder="Search" value="{{ request('search') }}">
                          <div class="input-group-append">
                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                          </div>
                        </div>
                      </form>
                    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">NIK</th>
                <th scope="col">Nama</th>
                <th scope="col">Tanggal Terdaftar</th>
                <th scope="col">Aksi</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($members as $member)
                    <tr>
                     <th scope="row">{{ $member->id }}</th>
                        <td>{{ $member->nik }}</td>
                        <td>{{ $member->user->name }}</td>
                        <td>{{ $member->user->email_verified_at }}</td>
                        <td>
                            <form action="{{ route('anggota.info', $member->id) }}" method="GET">
                                @csrf
                                <button type="submit" class="btn btn-outline-info btn-sm">Info</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
    </table>

        {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $members->appends(['search' => request('search')])->links() }}
    </div>

@endsection
