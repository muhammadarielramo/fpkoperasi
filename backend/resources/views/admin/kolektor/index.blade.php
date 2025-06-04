@extends('layouts.admin.app', ['title' => 'Data Kolektor'])

@section('content')

    <div class="float-right">
        <form class="d-flex">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                </div>
                <div class="input-group-append">
                    <a href="{{ route('kolektor.tambah') }}" class="btn btn-primary">Tambah Kolektor</a>
                </div>
            </div>
        </form>
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
