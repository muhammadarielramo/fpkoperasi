@extends('layouts.admin.app', ['title' => 'Tambah Simpanan'])
  <link rel="stylesheet" href="../node_modules/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="../node_modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="../node_modules/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="../node_modules/selectric/public/selectric.css">
  <link rel="stylesheet" href="../node_modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
  <link rel="stylesheet" href="../node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
@section('content')

<section class="section">

    <div class="card">
        <form method="POST" action="{{ route('simpanan.tambah') }}">
            @csrf
            <div class="card-body">

            <div class="form-group">
                <label>Nama Anggota</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $member->user->name ?? '') }}" readonly required>
            </div>

            <div class="form-group">
                <label>ID Anggota</label>
                <input type="number" name="id" class="form-control" value="{{ old('id', $member->id ?? '') }}" readonly required>
            </div>

            <div class="form-group">
                <label>Tanggal Transaksi</label>
                <input type="date" name="tgl_pembayaran" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Nominal</label>
                <input type="number" name="nominal" class="form-control" required>
            </div>


            <div class="form-group">
                <label>Jenis Simpanan</label>
                <select name="jenis" class="form-control" required>
                    <option value="">-- Jenis Simpanan--</option>
                    <option value="wajib">Simpanan Wajib</option>
                    <option value="pokok">Simpanan Pokok</option>
                    <option value="sukarela">Simpanan Sukarela</option>
                </select>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

    </div>
</section>

@endsection
