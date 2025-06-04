<form action="{{route('kolektor.update', $kolektor->id)}}" method="POST">
    @method('PUT')

    @csrf
    <input type="text" name="name" value="{{$kolektor->name}}">
    <input type="email" name="email" value="{{$kolektor->email}}">
    <input type="text" name="username" value="{{$kolektor->username}}">
    {{-- <input type="password" name="password" value="{{$kolektor->password}}"> --}}
    <input type="text" name="phone" value="{{$kolektor->phone}}">
    <input type="text" name="status" value="{{$kolektor->status}}">

    @error('name')
    <div class="text-danger">{{ $message }}</div>
    @enderror
    @error('email')
    <div class="text-danger">{{ $message }}</div>
    @enderror
    @error('username')
    <div class="text-danger">{{ $message }}</div>
    @enderror
    @error('phone')
    <div class="text-danger">{{ $message }}</div>
    @enderror
    @error('status')
    <div class="text-danger">{{ $message }}</div>
    @enderror


    <input type="submit" value="simpan"/>
</form>
