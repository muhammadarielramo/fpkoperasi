@extends('layouts.admin.app', ['title' => 'Riwayat Pinjaman'])

@section('content')
<section>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">ID Riwayat</th>
                <th scope="col">Nama Anggota</th>
                <th scope="col">ID Pinjaman</th>
                <th scope="col">Tanggal Transaksi</th>
                <th scope="col">Angsuran Ke-</th>
                <th scope="col">Jumlah Pembayaran</th>
                <th scope="col">Sisa Pinjaman</th>
                <th scope="col">Kolektor</th>
                <th scope="col">Aksi</th>
            </tr>
            </thead>
            <tbody>
                {{-- @foreach ($collectors as $collector) --}}
                    <tr>
                        <td>1</td>
                        <td>2</td>
                        <td>3</td>
                        <td>4</td>
                        <td>5</td>
                        <td>6</td>
                        <td>7</td>
                        <td>8</td>
                        <td>
                            <button class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Lihat
                            </button>
                            <button class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                {{-- @endforeach --}}
            </tbody>
    </table>
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
</section>

@endsection
