<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Selamat Datang – {{ config('app.name') }}</title>
  <!-- Bootstrap 4 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- SB Admin 2 CSS (opsional) -->
  <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>
    body { background: linear-gradient(180deg,#4e73df 10%,#224abe 100%); }
    .role-card { cursor: pointer; transition: transform .1s; }
    .role-card:hover { transform: scale(1.02); }
    .role-icon { font-size: 3rem; }
  </style>
</head>
<body class="d-flex align-items-center vh-100">

  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-lg o-hidden border-0">
          <div class="row no-gutters">
            <!-- Branding -->
            <div class="col-md-5 d-none d-md-flex bg-primary text-white flex-column justify-content-center p-4">
              <i class="fas fa-store role-icon mb-3"></i>
              <h3>{{ config('app.name') }}</h3>
              <p class="small">Selamat datang! Pilih peranmu untuk melanjutkan.</p>
            </div>
            <!-- Role Selection -->
            <div class="col-md-7 bg-white p-5">
              <h4 class="text-gray-900 mb-4">Anda Masuk Sebagai</h4>
              <div class="row">
                <div class="col-6 mb-3">
                  <a href="{{ route('login') }}" class="text-decoration-none">
                    <div class="card role-card shadow-sm text-center p-2">
                      <i class="fas fa-user-tie text-primary role-icon mb-2"></i>
                      <h6 class="mb-0">Kasir</h6>
                    </div>
                  </a>
                </div>
                <div class="col-6 mb-3">
                  <a href="{{ route('pemesanan') }}" class="text-decoration-none">
                    <div class="card role-card shadow-sm text-center p-2">
                      <i class="fas fa-shopping-cart text-success role-icon mb-2"></i>
                      <h6 class="mb-0">Pelanggan</h6>
                    </div>
                  </a>
                </div>
              </div>
              <p class="small text-muted mt-4">
                Kasir perlu login untuk mengakses dashboard. Pelanggan cukup langsung melakukan pemesanan.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JS dependencies -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
