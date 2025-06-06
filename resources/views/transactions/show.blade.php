@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- Judul & Tombol -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800">Detail Transaksi #{{ $transaction->id }}</h1>
    <div>
      <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
      @if($transaction->payment_status === 'paid')
        <a href="{{ route('orders.print', $transaction->order->id) }}" class="btn btn-dark">
          <i class="fas fa-print"></i> Cetak Ulang Struk
        </a>
      @endif
    </div>
  </div>

  <!-- Ringkasan Transaksi -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="font-weight-bold text-primary mb-0">Informasi Transaksi</h6>
    </div>
    <div class="card-body">
      <div class="form-row">
        <div class="form-group col-12 col-md-4">
          <p class="mb-1"><strong>ID Transaksi:</strong> {{ $transaction->id }}</p>
        </div>
        <div class="form-group col-12 col-md-4">
          <p class="mb-1"><strong>No. Pesanan:</strong> {{ $transaction->order->id }}</p>
        </div>
        <div class="form-group col-12 col-md-4">
          <p class="mb-1">
            <strong>Tanggal Bayar:</strong>
            {{ $transaction->paid_at ? $transaction->paid_at->format('d-m-Y H:i') : '-' }}
          </p>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-12 col-md-4">
          <p class="mb-1">
            <strong>Total Pembayaran:</strong>
            Rp {{ number_format($transaction->order->total_amount, 0, ',', '.') }}
          </p>
        </div>
        <div class="form-group col-12 col-md-4">
          <p class="mb-1 text-capitalize">
            <strong>Status Pembayaran:</strong> {{ $transaction->payment_status }}
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Detail Order (Item) -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="font-weight-bold text-primary mb-0">Rincian Pesanan</h6>
    </div>
    <div class="card-body">
      <!-- Tabel responsif untuk desktop/tablet -->
      <div class="table-responsive d-none d-sm-block">
        <table class="table table-bordered table-hover">
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
            @foreach($transaction->order->details as $idx => $detail)
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
                Rp {{ number_format($transaction->order->total_amount, 0, ',', '.') }}
              </th>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- List card untuk mobile -->
      <div class="d-block d-sm-none">
        @foreach($transaction->order->details as $idx => $detail)
          <div class="card mb-2">
            <div class="card-body p-2">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="mb-1"><strong>#{{ $idx + 1 }} - {{ $detail->product->name }}</strong></p>
                </div>
                <div class="text-right">
                  <p class="mb-1"><strong>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</strong></p>
                </div>
              </div>
              <div class="d-flex justify-content-between">
                <p class="mb-0 small text-muted">Qty: {{ $detail->quantity }}</p>
                <p class="mb-0 small text-muted">Harga: Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</p>
              </div>
            </div>
          </div>
        @endforeach
        <div class="d-flex justify-content-between mt-3">
          <p class="mb-0 font-weight-bold">Total</p>
          <p class="mb-0 font-weight-bold">Rp {{ number_format($transaction->order->total_amount, 0, ',', '.') }}</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
