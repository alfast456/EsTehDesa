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
      <form method="GET" action="{{ route('transactions.index') }}">
        <div class="form-row">
          <div class="form-group col-12 col-md-4">
            <label for="from">Dari:</label>
            <input type="date" name="from" id="from"
                   value="{{ old('from', $from) }}"
                   class="form-control">
          </div>
          <div class="form-group col-12 col-md-4">
            <label for="to">Sampai:</label>
            <input type="date" name="to" id="to"
                   value="{{ old('to', $to) }}"
                   class="form-control">
          </div>
          <div class="form-group col-12 col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary mr-2">
              <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
              <i class="fas fa-sync-alt"></i> Reset
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabel Transaksi (untuk desktop & tablet) -->
  @if($transactions->count())
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Semua Transaksi</h6>
      </div>
      <div class="card-body">
        <!-- DataTable responsive wrap -->
        <div class="table-responsive d-none d-sm-block">
          <table id="transactionsTable" class="table table-bordered table-hover" width="100%">
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
                  <td>Rp {{ number_format($trx->order->total_amount, 0, ',', '.') }}</td>
                  <td class="text-capitalize">{{ $trx->payment_status }}</td>
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

        <!-- Card list (untuk mobile) -->
        <div class="d-block d-sm-none">
          @foreach($transactions as $index => $trx)
            <div class="card mb-3 shadow-sm">
              <div class="card-body p-2">
                <div class="d-flex justify-content-between">
                  <div>
                    <p class="mb-1"><strong>#{{ $transactions->firstItem() + $index }} - Pesanan {{ $trx->order->id }}</strong></p>
                    <p class="mb-1 small text-muted">
                      {{ $trx->paid_at 
                          ? $trx->paid_at->format('d-m-Y H:i') 
                          : '-' 
                      }}
                    </p>
                  </div>
                  <div class="text-right">
                    <p class="mb-1 font-weight-bold">Rp {{ number_format($trx->order->total_amount, 0, ',', '.') }}</p>
                    <p class="mb-1 text-capitalize small">{{ $trx->payment_status }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <a href="{{ route('transactions.show', $trx->id) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i> Detail
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Paginasi (hanya untuk mobile, karena DataTables handle desktop) -->
        <div class="d-flex justify-content-end d-block d-sm-none">
          {{ $transactions->links() }}
        </div>
      </div>
    </div>
  @else
    <div class="alert alert-warning text-center">
      Belum ada transaksi pada rentang tanggal ini.
    </div>
  @endif
</div>
@endsection

@push('styles')
  <!-- DataTables CSS -->
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css" rel="stylesheet">
@endpush

@push('scripts')
  <!-- JQuery sudah ada di layout -->
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#transactionsTable').DataTable({
        paging:   false,      // non-aktifkan paging karena DataTables di desktop
        searching: true,
        ordering: true,
        info:     false,
        responsive: true,
        language: {
          url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
        }
      });
    });
  </script>
@endpush
