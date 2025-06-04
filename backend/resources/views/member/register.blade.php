<h1>halaman registrasi</h1>

<form action="{{}}" method="POST">
    @csrf
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
    </div>
    <div class="form-group" autocomplete="off">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
    </div>
    <div class="form-group">
        <label>No. Telepon</label>
        <input type="text" name="phone_number" class="form-control" required>
    </div>
    <div class="form-group" autocomplete="new-password">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="form-group" autocomplete="new-password">
        <label>Status</label>
        <input type="text" name="status" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Tambah Kolektor</button>
</form>

