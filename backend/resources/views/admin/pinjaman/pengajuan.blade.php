@extends('layouts.admin.app', ['title' => 'Anggota'])

<link rel="stylesheet" href="../node_modules/selectric/public/selectric.css">

@section('content')
        <section class="section">
          <div class="section-body">
            <h2 class="section-title">Pendaftaran Anggota</h2>

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
                        <tr>
                          <th>Nama</th>
                          <th>Tanggal Pengajuan</th>
                          <th>Nominal</th>
                          <th>Tenor</th>
                          <th>Status</th>
                          <th>Aksi</th>
                        </tr>
                        <tr>
                          <td>Laravel 5 Tutorial: Introduction
                            <div class="table-links">
                              <a href="#">View</a>
                            </div>
                          </td>
                          <td>
                            <p>17/5/2025</p>
                          </td>
                          <td>
                            <p>20.000.000</p>
                          </td>
                          <td>5 Bulan</td>
                          <td><div class="badge badge-warning">Diajukan</div></td>
                          <td>
                                <button class="btn btn-success btn-sm">Terima</button>
                                <button class="btn btn-danger btn-sm">Tolak</button>
                            </td>
                        </tr>
                      </table>
                    </div>
                    <div class="float-right">
                      <nav>
                        <ul class="pagination">
                          <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Previous">
                              <span aria-hidden="true">&laquo;</span>
                              <span class="sr-only">Previous</span>
                            </a>
                          </li>
                          <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                          </li>
                          <li class="page-item">
                            <a class="page-link" href="#">2</a>
                          </li>
                          <li class="page-item">
                            <a class="page-link" href="#">3</a>
                          </li>
                          <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                              <span aria-hidden="true">&raquo;</span>
                              <span class="sr-only">Next</span>
                            </a>
                          </li>
                        </ul>
                      </nav>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

  <script src="../node_modules/selectric/public/jquery.selectric.min.js"></script>

  <script src="../assets/js/page/features-posts.js"></script>


@endsection
