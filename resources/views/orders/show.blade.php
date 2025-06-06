@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- Judul & Tombol -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800">Detail Pesanan #{{ $order->id }}</h1>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> Buat Pesanan Baru
    </a>
  </div>

  <!-- Notifikasi -->
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <!-- Informasi Pesanan -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4">
          <p><strong>ID Pesanan:</strong> {{ $order->id }}</p>
        </div>
        <div class="col-md-4">
          <p><strong>Tanggal:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</p>
        </div>
        <div class="col-md-4">
          <p><strong>Status:</strong> 
            <span class="text-capitalize">{{ $order->status }}</span>
          </p>
        </div>
      </div>

      <!-- Daftar Item (POS-style) -->
      <div class="row">
        @foreach($order->details as $detail)
          @php
            $prod = $detail->product;
            $imgUrl = ($prod->image && Storage::exists('public/'.$prod->image))
                      ? asset('storage/'.$prod->image)
                      : asset('images/no-image.png');
          @endphp
          <div class="col-6 col-sm-4 col-md-3 mb-3">
            <div class="card h-100">
              <img src="{{ $imgUrl }}" class="card-img-top product-img" alt="{{ $prod->name }}">
              <div class="card-body p-2">
                <h6 class="card-title mb-1 text-truncate">{{ $prod->name }}</h6>
                <p class="mb-1 small text-muted">Qty: {{ $detail->quantity }}</p>
                <p class="mb-0 small text-success">
                  Rp {{ number_format($detail->unit_price, 0, ',', '.') }} × {{ $detail->quantity }}
                </p>
                <p class="mb-0 font-weight-bold">
                  Rp {{ number_format($detail->sub_total, 0, ',', '.') }}
                </p>
              </div>
            </div>
          </div>
        @endforeach

        @if($order->details->isEmpty())
          <div class="col-12 text-center">
            <p class="text-muted">Tidak ada item pada pesanan ini.</p>
          </div>
        @endif
      </div>

      <!-- Ringkasan Total -->
      <div class="d-flex justify-content-end">
        <h5>Total: <span class="text-success">
          Rp {{ number_format($order->total_amount, 0, ',', '.') }}
        </span></h5>
      </div>
    </div>
  </div>

  <!-- Bagian Pembayaran / Cetak Struk -->
  @if($order->status === 'pending')
    <div class="card shadow mb-4">
      <div class="card-body">
        <p class="font-weight-bold">Scan QR berikut untuk bayar (simulasi):</p>
        <div class="border p-3 mb-3 d-inline-block">
          {!! QrCode::size(200)->generate($order->qr_code_data) !!}
        </div>
        <form action="{{ route('orders.pay', $order->id) }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-success">
            <i class="fas fa-credit-card"></i> Bayar (Simulasi)
          </button>
        </form>
      </div>
    </div>
  @else
    <div class="alert alert-success shadow mb-4">
      <p>Pembayaran <strong>sukses</strong> pada 
        {{ $order->transaction->paid_at->format('d-m-Y H:i') }}
      </p>
      <a href="{{ route('orders.print', $order->id) }}" class="btn btn-dark">
        <i class="fas fa-print"></i> Cetak Struk
      </a>
    </div>
  @endif
</div>
@endsection
