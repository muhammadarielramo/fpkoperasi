@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Transaksi Kas</h4>

    <form action="{{ route('cash-flows.update', $cashFlow->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>Tanggal Transaksi</label>
                    <input type="date" name="transaction_date" class="form-control"
                        value="{{ old('transaction_date', $cashFlow->transaction_date->format('Y-m-d')) }}" required>
                </div>

                <div class="form-group">
                    <label>Nominal</label>
                    <input type="number" name="amount" class="form-control"
                        value="{{ old('amount', $cashFlow->amount) }}" required>
                </div>

                <div class="form-group">
                    <label>Jenis Kas</label>
                    <select name="is_cash_in" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="1" {{ old('is_cash_in', $cashFlow->is_cash_in) == 1 ? 'selected' : '' }}>Kas Masuk</option>
                        <option value="0" {{ old('is_cash_in', $cashFlow->is_cash_in) == 0 ? 'selected' : '' }}>Kas Keluar</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tipe Transaksi</label>
                    <select name="type" class="form-control" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="operational" {{ old('type', $cashFlow->type) == 'operational' ? 'selected' : '' }}>Operasional</option>
                        <option value="misc_income" {{ old('type', $cashFlow->type) == 'misc_income' ? 'selected' : '' }}>Pemasukan Lain-lain</option>
                        <option value="misc_expense" {{ old('type', $cashFlow->type) == 'misc_expense' ? 'selected' : '' }}>Pengeluaran Lain-lain</option>
                        <option value="transfer" {{ old('type', $cashFlow->type) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="adjustment" {{ old('type', $cashFlow->type) == 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <input type="text" name="description" class="form-control"
                        value="{{ old('description', $cashFlow->description) }}" required>
                </div>
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('cash-flows.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection
