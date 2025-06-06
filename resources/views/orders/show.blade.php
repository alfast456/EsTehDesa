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
      @if($order->status === 'paid')
        <a href="{{ route('orders.print', $order->id) }}" class="btn btn-dark">
          <i class="fas fa-print"></i> Cetak Struk
        </a>
      @endif
    </div>
  </div>

  <!-- Notifikasi -->
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <!-- Informasi Pesanan -->
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
          <p>
            <strong>Total Pembayaran:</strong> 
            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
          </p>
        </div>
        <div class="col-md-4">
          <p class="text-capitalize">
            <strong>Status:</strong> {{ $order->status }}
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Rincian Barang -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="font-weight-bold text-primary mb-0">Rincian Pesanan</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="thead-light">
            <tr>
              <th>No</th>
              <th>Nama Produk</th>
              <th class="text-right">Qty</th>
              <th class="text-right">Harga Satuan (Rp)</th>
              <th class="text-right">Sub Total (Rp)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order->details as $idx => $detail)
              <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $detail->product->name }}</td>
                <td class="text-right">{{ $detail->quantity }}</td>
                <td class="text-right">{{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th colspan="4" class="text-right">Total:</th>
              <th class="text-right">
                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
              </th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>

  <!-- Bagian Pembayaran Manual -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="font-weight-bold text-primary mb-0">Pembayaran</h6>
    </div>
    <div class="card-body">
      @if($order->status === 'pending')
        <p class="mb-3">
          Silakan lakukan pembayaran menggunakan QRIS secara manual (scan kode QRIS di kasir Anda atau gunakan aplikasi dompet digital).
        </p>
        <form action="{{ route('orders.pay', $order->id) }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-success">
            <i class="fas fa-check-circle"></i> Tandai Sudah Dibayar
          </button>
        </form>
      @else
        <div class="alert alert-success">
          <p>
            Pembayaran <strong>berhasil</strong> pada
            {{ $order->transaction->paid_at->format('d-m-Y H:i') }}.
          </p>
          <a href="{{ route('orders.print', $order->id) }}" class="btn btn-dark">
            <i class="fas fa-print"></i> Cetak Struk
          </a>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
