@extends('layouts.admin.app', ['title' => 'Registrasi Anggota'])

<link rel="stylesheet" href="../node_modules/selectric/public/selectric.css">

@section('content')
<section class="section">
    <div class="section-body">

        <div class="row mt-4">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="float-right">
                      <form>
                        <div class="input-group">
                          <input type="text" class="form-control" placeholder="Search">
                          <div class="input-group-append">
                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                          </div>
                        </div>
                      </form>
                    </div>

                    <div class="clearfix mb-3"></div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No HP</th>
                                    <th>KTP</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registers as $p)
                                <tr>
                                    <td>{{ optional($p->created_at)->format('d-m-Y') }}</td>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->email }}</td>
                                    <td>{{ $p->phone_number }}</td>
                                    <td>
                                        @if($p->ktp)
                                            <a href="{{ asset('storage/' . $p->ktp) }}" target="_blank">Lihat KTP</a>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('register.terima', ['id' => $p->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="Diterima">
                                            <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                        </form>

                                         <form action="{{ route('register.tolak', ['id' => $p->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $registers->links() }}
                </div>
              </div>
            </div>
        </div>
    </div>
</section>

  <script src="../node_modules/selectric/public/jquery.selectric.min.js"></script>

  <script src="../assets/js/page/features-posts.js"></script>


@endsection
