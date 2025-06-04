@extends('layouts.admin.app', ['title' => 'Riwayat Transaksi'])

@section('content')
<section>

    <form action="{{ route('history.daily') }}" method="GET" class="form-inline mb-3">
        <div class="form-group mr-2">
            <input type="text" name="date" class="form-control" placeholder="YYYY-MM-DD atau YYYY-MM" required>
        </div>

        <div class="btn-group" role="group">
            {{-- Tombol tampilkan data --}}
            <button type="submit" name="action" value="filter" class="btn btn-primary">
                <i class="fas fa-search"></i> Tampilkan Data
            </button>

            {{-- export --}}
            <button formaction="{{ route('transactions.export') }}" type="submit" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
    </form>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Tipe</th>
                <th scope="col">Jenis</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Nominal</th>
                <th scope="col">Anggota</th>
                <th scope="col">Kolektor</th>

            </tr>
            </thead>
            <tbody>
                @foreach ($transactions->sortByDesc('tgl_transaksi') as $t)
                    <tr>
                        <td>{{$t->tipe_transaksi}}</td>
                        <td>
                            @if ($t->id_deposit !== null)
                                Simpanan
                            @elseif ($t->id_loan !== null)
                                Pinjaman
                            @elseif ($t->id_installment !== null)
                                Angsuran
                            @else
                                -
                            @endif
                        </td>
                        <td>{{$t->tgl_transaksi}}</td>
                        <td>{{$t->jumlah}}</td>
                        <td>{{$t->member->user->name}}</td>
                        <td>{{$t->collector->user->name}}</td>
                    </tr>
                @endforeach
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
