@extends('layouts.auth')

@section('content')
<body class="bg-gradient-primary">

  <div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center align-items-center vh-100">
      <div class="col-xl-8 col-lg-10 col-md-12">
        <div class="card o-hidden border-0 shadow-lg">
          <div class="row no-gutters">
            <!-- Left Side: Branding & Illustration (Desktop Only) -->
            <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center bg-primary text-white p-5">
              <div class="text-center mb-4">
                <i class="fas fa-store fa-3x mb-3"></i>
                <h2 class="h4">{{ config('app.name') }}</h2>
              </div>
              <p class="small">
                Selamat datang di sistem POS Tehdesa. Silakan daftarkan akun Anda untuk mulai mengelola produk dan melakukan transaksi.
              </p>
              <img src="{{ asset('img/undraw_posting_photo.svg') }}" alt="POS Illustration" class="img-fluid mt-auto" style="max-height: 200px;">
            </div>

            <!-- Right Side: Registration Form -->
            <div class="col-lg-6 bg-white p-5">
              <div class="text-center mb-4">
                <h1 class="h4 text-gray-900">Register POS</h1>
                <p class="small text-muted">Daftarkan akun baru untuk mulai menggunakan aplikasi</p>
              </div>
              <form method="POST" action="{{ route('register') }}" class="user">
                @csrf

                <div class="form-row">
                  <!-- Name -->
                  <div class="form-group col-md-6">
                    <label for="name" class="small font-weight-bold text-gray-600">Name</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-light">
                          <i class="fas fa-user text-gray-600"></i>
                        </span>
                      </div>
                      <input
                        id="name"
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autocomplete="name"
                        placeholder="Nama lengkap"
                        autofocus
                      >
                      @error('name')
                        <div class="invalid-feedback pl-3">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <!-- Email -->
                  <div class="form-group col-md-6">
                    <label for="email" class="small font-weight-bold text-gray-600">E-Mail Address</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-light">
                          <i class="fas fa-envelope text-gray-600"></i>
                        </span>
                      </div>
                      <input
                        id="email"
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="you@example.com"
                      >
                      @error('email')
                        <div class="invalid-feedback pl-3">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="form-row">
                  <!-- Password -->
                  <div class="form-group col-md-6">
                    <label for="password" class="small font-weight-bold text-gray-600">Password</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-light">
                          <i class="fas fa-lock text-gray-600"></i>
                        </span>
                      </div>
                      <input
                        id="password"
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="●●●●●●●●"
                      >
                      @error('password')
                        <div class="invalid-feedback pl-3">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <!-- Confirm Password -->
                  <div class="form-group col-md-6">
                    <label for="password-confirm" class="small font-weight-bold text-gray-600">Confirm Password</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-light">
                          <i class="fas fa-lock text-gray-600"></i>
                        </span>
                      </div>
                      <input
                        id="password-confirm"
                        type="password"
                        class="form-control"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="●●●●●●●●"
                      >
                    </div>
                  </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-user btn-block mt-3">
                  <i class="fas fa-user-plus mr-2"></i> Register
                </button>

                <!-- Quick Links -->
                <div class="mt-4 text-center">
                  <a class="small text-gray-600" href="{{ route('login') }}">
                    Already have an account? Login!
                  </a>
                </div>
              </form>
            </div>
            <!-- End Right Side -->
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
@endsection
