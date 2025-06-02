@extends('layouts.admin.app', ['title' => 'Pengajuan Pinjaman'])

<link rel="stylesheet" href="../node_modules/selectric/public/selectric.css">

@section('content')
        <section class="section">
          <div class="section-body">
            <h2 class="section-title">Pengajuan Pinjaman</h2>

            <div class="row">
              <div class="col-12">
                <div class="card mb-0">
                  <div class="card-body">
                    <ul class="nav nav-pills">
                      <li class="nav-item">
                        <a class="nav-link active" href="#">All <span class="badge badge-white">5</span></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">Pending <span class="badge badge-primary">1</span></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">Diterima<span class="badge badge-primary">0</span></a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
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
                                    <th>Nama</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Nominal</th>
                                    <th>Tenor</th>
                                    <th>Terakhir diubah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengajuan as $p)
                                    <tr>
                                        <td>
                                            {{ $p->member->user->name }}
                                            <div class="table-links">
                                                <a href="#">View</a>
                                            </div>
                                        </td>
                                        <td>
                                            <p>{{ $p->tgl_pengajuan }}</p>
                                        </td>
                                        <td>
                                            <p>Rp {{ number_format($p->jumlah_pinjaman, 0, ',', '.') }}</p>
                                        </td>
                                        <td>{{ $p->tenor }} Bulan</td>
                                        <td>{{ $p->updated_at }}</td>
                                        <td>
                                            @if ($p->status == 'Diajukan')
                                                <div class="badge badge-warning">Diajukan</div>
                                            @elseif ($p->status == 'Diterima')
                                                <div class="badge badge-success">Diterima</div>
                                            @elseif ($p->status == 'Ditolak')
                                                <div class="badge badge-danger">Ditolak</div>
                                            @else
                                                <div class="badge badge-secondary">{{ $p->status }}</div>
                                            @endif
                                        </td>
                                    <td>
                                        <form action="{{ route('pinjaman.updateStatus', $p->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Diterima">
                                            <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                        </form>

                                        <form action="{{ route('pinjaman.updateStatus', $p->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
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
                    {{ $pengajuan->links() }}
                </div>
              </div>
            </div>
          </div>
        </section>

  <script src="../node_modules/selectric/public/jquery.selectric.min.js"></script>

  <script src="../assets/js/page/features-posts.js"></script>


@endsection
