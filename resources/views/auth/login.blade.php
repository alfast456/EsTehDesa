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
                Selamat datang di sistem POS Tehdesa. Silakan masuk untuk memulai transaksi dan mengelola produk Anda dengan mudah.
              </p>
              <img src="{{ asset('img/undraw_posting_photo.svg') }}" alt="POS Illustration" class="img-fluid mt-auto" style="max-height: 200px;">
            </div>

            <!-- Right Side: Login Form -->
            <div class="col-lg-6 bg-white p-5">
              <div class="text-center mb-4">
                <h1 class="h4 text-gray-900">Login POS</h1>
              </div>
              <form method="POST" action="{{ route('login') }}" class="user">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                  <label for="email" class="small font-weight-bold text-gray-600">E-Mail Address</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-light"><i class="fas fa-envelope text-gray-600"></i></span>
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
                      autofocus
                    >
                    @error('email')
                      <div class="invalid-feedback pl-3">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                  <label for="password" class="small font-weight-bold text-gray-600">Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-light"><i class="fas fa-lock text-gray-600"></i></span>
                    </div>
                    <input 
                      id="password" 
                      type="password" 
                      class="form-control @error('password') is-invalid @enderror" 
                      name="password" 
                      required 
                      autocomplete="current-password"
                      placeholder="●●●●●●●●"
                    >
                    @error('password')
                      <div class="invalid-feedback pl-3">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <!-- Remember Me -->
                <div class="form-group">
                  <div class="custom-control custom-checkbox small">
                    <input 
                      type="checkbox" 
                      class="custom-control-input" 
                      id="remember" 
                      name="remember" 
                      {{ old('remember') ? 'checked' : '' }}
                    >
                    <label class="custom-control-label text-gray-600" for="remember">Remember Me</label>
                  </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-user btn-block">
                  <i class="fas fa-sign-in-alt mr-2"></i> Login
                </button>

                <!-- Optional Links -->
                {{-- <div class="mt-3 text-center">
                  @if (Route::has('password.request'))
                    <a class="small text-gray-600" href="{{ route('password.request') }}">
                      Forgot Password?
                    </a>
                  @endif
                </div> --}}
                <div class="text-center mt-2">
                  <a class="small text-gray-600" href="{{ route('register') }}">
                    Create an Account!
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
