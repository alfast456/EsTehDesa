<!DOCTYPE html>
<html>

<head>
    <title>@yield('title', @config('app.name'))</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sb-admin-2.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <!-- CSS DataTable -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">


</head>

<body id="page-top">

    @include('layouts.partials.header')

    @yield('content')

    @include('layouts.partials.footer')
    {{-- jquery --}}
    

</body>

</html>