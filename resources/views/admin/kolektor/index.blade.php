@extends('layouts.admin.app', ['title' => 'Data Kolektor'])

@section('content')

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 w-100">
            {{-- Search form (kiri) --}}
            <form method="GET" action="{{ route('admin.data-kolektor') }}" class="d-flex" style="max-width: 400px;">
                <input type="text" name="search" id="searchInput" class="form-control" placeholder="🔍 Cari nama..." value="{{ request('search') }}">
                <button class="btn btn-primary ml-2"><i class="fas fa-search"></i></button>
            </form>

            {{-- Tambah Kolektor button (kanan) --}}
            <a href="{{ route('kolektor.tambah') }}" class="btn btn-primary">Tambah Kolektor</a>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nama</th>
                <th scope="col">No. HP</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($collectors as $collector)
                    <tr>
                     <th scope="row">{{ $collector->id }}</th>
                        <td>{{ $collector->user->name }}</td>
                        <td>{{ $collector->user->phone_number }}</td>
                        <td>{{ $collector->status }}</td>
                        <td>
                            <form action="{{ route('kolektor.info', $collector->id) }}" method="GET">
                                @csrf
                                <button type="submit" class="btn btn-outline-info btn-sm">Info</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $collectors->links() }}
    </div>
@endsection
