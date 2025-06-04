@extends('layouts.admin.app', ['title' => 'Data Admin'])

@section('content')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Password</th>
            </tr>
            </thead>
            <tbody>
                {{-- @foreach ($members as $member) --}}
                    <tr>
                        <td>id</td>
                        <td>nama</td>
                        <td>nama</td>
                        <td>password</td>
                    </tr>
                {{-- @endforeach --}}
            </tbody>
    </table>

@endsection
