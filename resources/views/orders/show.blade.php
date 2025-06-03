@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- Judul & Tombol -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800">Detail Pesanan #{{ $order->id }}</h1>
    <div>
      <a href="{{ route('orders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat Pesanan Baru
      </a>
      <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-tachometer-alt"></i> Dashboard
      </a>
    </div>
  </div>

  <!-- Notifikasi Sukses/Error -->
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <!-- Ringkasan Order -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="font-weight-bold text-primary mb-0">Informasi Pesanan</h6>
    </div>
    <div class="card-body">
      <div class="row mb-2">
        <div class="col-md-4">
          <p><strong>ID Pesanan:</strong> {{ $order->id }}</p>
        </div>
        <div class="col-md-4">
          <p><strong>Tanggal:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</p>
        </div>
        <div class="col-md-4">
          <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
        </div>
      </div>

      <!-- Tabel Detail Produk -->
      <div class="table-responsive mb-3">
        <table class="table table-bordered">
          <thead class="thead-light">
            <tr>
              <th>No</th>
              <th>Nama Produk</th>
              <th>Qty</th>
              <th>Harga Satuan (Rp)</th>
              <th>Sub Total (Rp)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order->details as $idx => $detail)
              <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                <td>{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th colspan="4" class="text-right">Total:</th>
              <th>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- Bagian Pembayaran / Cetak Struk -->
      @if($order->status === 'pending')
        <div class="mb-4">
          <p class="font-weight-bold">Scan QR berikut untuk bayar (simulasi):</p>
          <div class="border p-3 d-inline-block">
            {!! QrCode::size(200)->generate($order->qr_code_data) !!}
          </div>
        </div>
        <form action="{{ route('orders.pay', $order->id) }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-success">
            <i class="fas fa-credit-card"></i> Bayar (Simulasi)
          </button>
        </form>
      @else
        <div class="alert alert-success">
          <p>Pembayaran <strong>sukses</strong> pada {{ $order->transaction->paid_at->format('d-m-Y H:i') }}</p>
        </div>
        <a href="{{ route('orders.print', $order->id) }}" class="btn btn-dark">
          <i class="fas fa-print"></i> Cetak Struk
        </a>
      @endif
    </div>
  </div>
</div>
@endsection
