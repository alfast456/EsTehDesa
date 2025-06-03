@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- Judul Halaman -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Transaksi</h1>
  </div>

  <!-- Form Filter Tanggal -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Filter Transaksi berdasarkan Tanggal Bayar</h6>
    </div>
    <div class="card-body">
      <form method="GET" action="{{ route('transactions.index') }}" class="form-inline">
        <div class="form-group mr-2">
          <label for="from" class="mr-2">Dari:</label>
          <input type="date" name="from" id="from"
                 value="{{ old('from', $from) }}"
                 class="form-control">
        </div>
        <div class="form-group mr-2">
          <label for="to" class="mr-2">Sampai:</label>
          <input type="date" name="to" id="to"
                 value="{{ old('to', $to) }}"
                 class="form-control">
        </div>
        <button type="submit" class="btn btn-primary mr-2">
          <i class="fas fa-filter"></i> Filter
        </button>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
          <i class="fas fa-sync-alt"></i> Reset
        </a>
      </form>
    </div>
  </div>

  <!-- Tabel Transaksi -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Semua Transaksi</h6>
    </div>
    <div class="card-body">
      @if($transactions->count())
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th>No</th>
                <th>No. Pesanan</th>
                <th>Tanggal Bayar</th>
                <th>Total (Rp)</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($transactions as $index => $trx)
                <tr>
                  <td>{{ $transactions->firstItem() + $index }}</td>
                  <td>{{ $trx->order->id }}</td>
                  <td>
                    {{ $trx->paid_at 
                        ? $trx->paid_at->format('d-m-Y H:i') 
                        : '-' 
                    }}
                  </td>
                  <td>
                    Rp {{ number_format($trx->order->total_amount, 0, ',', '.') }}
                  </td>
                  <td class="text-capitalize">
                    {{ $trx->payment_status }}
                  </td>
                  <td>
                    <a href="{{ route('transactions.show', $trx->id) }}" 
                       class="btn btn-sm btn-info">
                      <i class="fas fa-eye"></i> Detail
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Paginasi -->
        <div class="d-flex justify-content-end">
          {{ $transactions->links() }}
        </div>
      @else
        <div class="alert alert-warning text-center">
          Belum ada transaksi pada rentang tanggal ini.
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
