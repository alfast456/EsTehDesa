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
      <div class="row mb-2">
        <div class="col-md-4">
          <p><strong>ID Transaksi:</strong> {{ $transaction->id }}</p>
        </div>
        <div class="col-md-4">
          <p>
            <strong>No. Pesanan:</strong> {{ $transaction->order->id }}
          </p>
        </div>
        <div class="col-md-4">
          <p>
            <strong>Tanggal Bayar:</strong>
            {{ $transaction->paid_at 
                ? $transaction->paid_at->format('d-m-Y H:i') 
                : '-' 
            }}
          </p>
        </div>
      </div>
      <div class="row mb-2">
        <div class="col-md-4">
          <p>
            <strong>Total Pembayaran:</strong> 
            Rp {{ number_format($transaction->order->total_amount, 0, ',', '.') }}
          </p>
        </div>
        <div class="col-md-4">
          <p class="text-capitalize">
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
            @foreach($transaction->order->details as $idx => $detail)
              <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $detail->product->name }}</td>
                <td class="text-right">{{ $detail->quantity }}</td>
                <td class="text-right">
                  {{ number_format($detail->unit_price, 0, ',', '.') }}
                </td>
                <td class="text-right">
                  {{ number_format($detail->sub_total, 0, ',', '.') }}
                </td>
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
    </div>
  </div>
</div>
@endsection
