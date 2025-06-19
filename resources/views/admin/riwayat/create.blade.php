@extends('layouts.admin.app', ['title' => 'Data Kolektor'])
  <link rel="stylesheet" href="../node_modules/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="../node_modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="../node_modules/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="../node_modules/selectric/public/selectric.css">
  <link rel="stylesheet" href="../node_modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
  <link rel="stylesheet" href="../node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
@section('content')

<section class="section">

    <div class="card">
        <form method="POST" action="{{ route('history.create') }}">
            @csrf
            <div class="card-body">
            <div class="form-group">
                <label>Tanggal Transaksi</label>
                <input type="date" name="transaction_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Nominal</label>
                <input type="number" name="nominal" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Jenis Kas</label>
                <select name="is_cash_in" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="1">Kas Masuk</option>
                    <option value="0">Kas Keluar</option>
                </select>
            </div>

            <div class="form-group">
                <label>Tipe Transaksi</label>
                <select name="type" class="form-control" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="operational">Operasional</option>
                    <option value="misc_income">Pemasukan Lain-lain</option>
                    <option value="misc_expense">Pengeluaran Lain-lain</option>
                    <option value="transfer">Transfer</option>
                    <option value="adjustment">Penyesuaian</option>
                </select>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <input type="text" name="description" class="form-control" required>
            </div>
        </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

    </div>
</section>

@endsection
