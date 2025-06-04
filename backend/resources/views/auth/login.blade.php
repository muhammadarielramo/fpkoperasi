<x-guest-layout>

    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-md-6 d-flex flex-column mx-auto">
                            <div class="card card-plain mt-8">
                                <div class="card-header pb-0 text-left bg-transparent text-center">
                                    <h3 class="font-weight-black text-dark display-6">KOKITA</h3>
                                    <p class="mb-0">Selamat Datang</p>
                                    <p class="mb-0">Masuk sebagai admin</p>
                                </div>

                                {{-- untuk error message --}}
                                <div class="text-center">
                                    @if (session('status'))
                                        <div class="mb-4 font-medium text-sm text-green-600">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    @error('message')
                                        <div class="alert alert-danger text-sm" role="alert">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="card-body">
                                    {{-- route --}}
                                    <form role="form" class="text-start" action="{{route('login')}}" method="post">
                                        @csrf
                                        <label>Email Address</label>
                                        <div class="mb-3">
                                            <input type="email" id="email" name="email" class="form-control"
                                                placeholder="Enter your email address"
                                                aria-label="Email" aria-describedby="email-addon">
                                        </div>
                                        <label>Password</label>
                                        <div class="mb-3">
                                            <input type="password" id="password" name="password"
                                                class="form-control" placeholder="Enter password" aria-label="Password"
                                                aria-describedby="password-addon">
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-dark w-100 mt-4 mb-3">Log In</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

</x-guest-layout>
