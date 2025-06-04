@extends('layouts.admin.app', ['title' => 'Data Kolektor'])
  <link rel="stylesheet" href="../node_modules/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="../node_modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="../node_modules/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="../node_modules/selectric/public/selectric.css">
  <link rel="stylesheet" href="../node_modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
  <link rel="stylesheet" href="../node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
@section('content')

<section class="section">
    <div class="section-body">
            <h2 class="section-title">Tambah data kolektor</h2>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('kolektor.simpan') }}">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Nama Kolektor</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="phone_number" class="form-control" required>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

    </div>
</section>

@endsection
