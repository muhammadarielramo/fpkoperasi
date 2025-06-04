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
                          <th>Jumlah Cicilan</th>
                          <th>Status</th>
                        </tr>
                        <tr>
                          <td>10</td>
                          <td>17/5/2025</td>
                          <td>20</td>
                          <td>Izumi</td>
                          <td>2</td>
                          <td>20.000.000</td>
                          <td>10.000.000</td>
                          <td>Berjalan</td>
                        </tr>
                        <tr>
                          <td>11</td>
                          <td>17/5/2025</td>
                          <td>20</td>
                          <td>Tsukasa Suou</td>
                          <td>2</td>
                          <td>20.000.000</td>
                          <td>10.000.000</td>
                          <td>Lunas</td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <div class="card-footer text-right">
                    <nav class="d-inline-block">
                      <ul class="pagination mb-0">
                        <li class="page-item disabled">
                          <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1 <span class="sr-only">(current)</span></a></li>
                        <li class="page-item">
                          <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                          <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                        </li>
                      </ul>
                    </nav>
                  </div>
                </div>
@endsection
