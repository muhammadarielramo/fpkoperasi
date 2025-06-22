@extends('layouts.admin.app', ['title' => 'Cashflow'])

@section('content')
        @php
        $totalIn = 0;
        $totalOut = 0;
        @endphp
    <form action="{{ route('cashflow.daily') }}" method="GET" class="form-inline mb-3">
        <div class="form-group mr-2">
            <input type="text" name="date" class="form-control" placeholder="YYYY-MM-DD atau YYYY-MM" required>
        </div>

        <div class="btn-group" role="group">
            {{-- Tombol tampilkan data --}}
            <button type="submit" name="action" value="filter" class="btn btn-primary">
                <i class="fas fa-search"></i> Tampilkan Data
            </button>

            {{-- export --}}
            <button formaction="{{ route('cashflow.export') }}" type="submit" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>

            <a href="{{ route('history.create.show') }}" class="btn btn-info ml-2">
                <i class="fas fa-plus"></i> Tambah Transaksi Kas
            </a>
        </div>
    </form>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Tanggal</th>
                <th scope="col">Keterangan</th>
                <th scope="col">Jenis</th>
                <th scope="col">Masuk</th>
                <th scope="col">Keluar</th>
                <th scope="col">Aksi</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $t)
                    @php
                        if ($t->is_cash_in) {
                            $totalIn += $t->nominal;
                        } else {
                            $totalOut += $t->nominal;
                        }
                    @endphp
                    <tr>
                        <td>{{$t->transaction_date}}</td>
                        <td>{{$t->description}}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $t->type)) }}</td>
                        {{-- Logika kolom Masuk / Keluar --}}
                        <td>
                            @if ($t->is_cash_in)
                                Rp{{ number_format($t->nominal, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if (!$t->is_cash_in)
                                Rp{{ number_format($t->nominal, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>

                    </tr>
                @endforeach
                <tr class="font-weight-bold bg-light">
                <td colspan="3" class="text-right">Total</td>
                <td>Rp{{ number_format($totalIn, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($totalOut, 0, ',', '.') }}</td>
                <td></td>
            </tr>
            <tr class="font-weight-bold bg-light">
                <td colspan="3" class="text-right">Saldo Kas</td>
                <td colspan="2">
                    Rp{{ number_format($totalIn - $totalOut, 0, ',', '.') }}
                </td>
            </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $transactions->links() }}
        </div>

@endsection

@section('script')
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
            });
        </script>
    @endif

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
        });
    </script>
@endif
@endsection
