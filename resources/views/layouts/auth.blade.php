<!DOCTYPE html>
<html>

<head>
  <title> Masuk atau Daftar - {{ config('app.name') }}</title>
  <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
      <!-- CSS DataTable -->
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">

      <!-- FontAwesome untuk ikon -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body id="page-top">
  @yield('content')

<!-- JavaScript-->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script> -->
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
</body>

</html>