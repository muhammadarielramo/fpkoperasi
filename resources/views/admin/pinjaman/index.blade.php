@extends('layouts.admin.app', ['title' => 'Pinjaman'])

@section('content')

                <div class="card">
                  <div class="card-header">
                    <h4>Data Pinjaman Anggota</h4>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-striped table-md">
                        <tr>
                          <th>ID Pinjaman</th>
                          <th>Tanggal</th>
                          <th>ID Anggota</th>
                          <th>Nama Anggota</th>
                          <th>Tenor (Bulan)</th>
                          <th>Jumlah</th>
                          <th>Status</th>
                          <th>Aksi</th>
                        </tr>
                        @foreach ($loans as $l )
                        <tr>
                          <td>{{$l->id}}</td>
                          <td>{{$l->tgl_persetujuan}}</td>
                          <td>{{$l->member->id}}</td>
                          <td>{{$l->member->user->name}}</td>
                          <td>{{$l->tenor}}</td>
                          <td>{{$l->jumlah_pinjaman}}</td>
                          <td>{{$l->status}}</td>
                          <td><a href="{{route('pinjaman.detail', $l->id)}}" class="btn btn-primary">Detail</a></td>
                        </tr>
                        @endforeach
                      </table>
                    </div>
                  </div>
                </div>
@endsection
