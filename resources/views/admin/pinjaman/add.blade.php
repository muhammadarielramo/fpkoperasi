@extends('layouts.admin.app', ['title' => 'Tambah Cicilan'])
  <link rel="stylesheet" href="../node_modules/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="../node_modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="../node_modules/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="../node_modules/selectric/public/selectric.css">
  <link rel="stylesheet" href="../node_modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
  <link rel="stylesheet" href="../node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
@section('content')

<section class="section">

    <div class="card">
        <form method="POST" action="{{ route('pinjaman.cicil', $data['id']) }}">
            @csrf
            <div class="card-body">

            <div class="form-group">
                <label>ID Pinjaman</label>
                <input type="number" name="id" class="form-control" value="{{ old('id', $data['id'] ?? '') }}" readonly required>
            </div>

            <div class="form-group">
                <label>Tanggal Transaksi</label>
                <input type="date" name="tgl_pembayaran" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Cicilan Ke-</label>
                <input type="number" name="cicilan_ke" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Nominal</label>
                <input type="number" name="besar_ciclan" class="form-control" required>
            </div>


            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="">-- Status --</option>
                    <option value="Tepat Waktu">Tepat Waktu</option>
                    <option value="Terlambat">Terlambat</option>
                </select>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

    </div>
</section>

@endsection
