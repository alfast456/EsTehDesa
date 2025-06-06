@extends('layouts.app')

@section('content')
<style>
  /* Sesuaikan tinggi chart agar responsif */
  #salesChart {
    height: 250px;
  }
  /* Card Quick Action */
  .quick-action .card {
    cursor: pointer;
    transition: transform 0.1s;
  }
  .quick-action .card:hover {
    transform: scale(1.02);
  }
  /* Daftar transaksi mobile: kartu ringkas */
  .transaction-card {
    margin-bottom: 1rem;
  }
</style>

<div class="container-fluid">
  <!-- Judul Dashboard -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard POS</h1>
  </div>

  <!-- Sekarang: Quick Actions -->
  <div class="row quick-action mb-4">
    <div class="col-6 col-sm-4 col-md-3 mb-3">
      <div class="card shadow text-center" onclick="window.location.href='{{ route('orders.create') }}'">
        <div class="card-body py-4">
          <i class="fas fa-shopping-cart fa-2x text-primary"></i>
          <p class="mt-2 mb-0 font-weight-bold">Buat Pesanan</p>
        </div>
      </div>
    </div>
    <div class="col-6 col-sm-4 col-md-3 mb-3">
      <div class="card shadow text-center" onclick="window.location.href='{{ route('products.index') }}'">
        <div class="card-body py-4">
          <i class="fas fa-box-open fa-2x text-success"></i>
          <p class="mt-2 mb-0 font-weight-bold">Kelola Produk</p>
        </div>
      </div>
    </div>
    <div class="col-6 col-sm-4 col-md-3 mb-3">
      <div class="card shadow text-center" onclick="window.location.href='{{ route('categories.index') }}'">
        <div class="card-body py-4">
          <i class="fas fa-tags fa-2x text-warning"></i>
          <p class="mt-2 mb-0 font-weight-bold">Kelola Kategori</p>
        </div>
      </div>
    </div>
    <div class="col-6 col-sm-4 col-md-3 mb-3">
      <div class="card shadow text-center" onclick="window.location.href='{{ route('transactions.index') }}'">
        <div class="card-body py-4">
          <i class="fas fa-receipt fa-2x text-danger"></i>
          <p class="mt-2 mb-0 font-weight-bold">Lihat Transaksi</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Alert Selamat Datang -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="alert alert-warning alert-dismissible fade show shadow" role="alert">
        <strong>Selamat Datang!</strong> Anda login sebagai <strong>{{ Auth::user()->name }}</strong>.
        <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Card Ringkasan Utama -->
  <div class="row mb-4">
    <!-- Penjualan Hari Ini -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Penjualan Hari Ini</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                Rp {{ number_format($todaySales, 0, ',', '.') }}
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Total Produk -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Total Produk</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                {{ $totalProducts }}
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-boxes fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Produk Stok Kurang -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Produk Stok ≤ {{ $lowStockThreshold }}</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                {{ $lowStockCount }}
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Total Transaksi -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Total Transaksi</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                {{ $totalOrders }}
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Grafik Penjualan 7 Hari & Ringkasan Bulanan -->
  <div class="row mb-4">
    <!-- Grafik Penjualan 7 Hari -->
    <div class="col-lg-8 mb-4">
      <div class="card shadow h-100">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan 7 Hari Terakhir</h6>
        </div>
        <div class="card-body">
          <canvas id="salesChart"></canvas>
        </div>
      </div>
    </div>
    <!-- Ringkasan Bulanan (Ringkasan Bulan Berjalan & Rata2 per Hari) -->
    <div class="col-lg-4 mb-4">
      <div class="card shadow h-100">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Ringkasan Bulanan</h6>
        </div>
        <div class="card-body">
          <p class="mb-2"><strong>Penjualan Bulan Ini:</strong></p>
          <h4 class="text-success">Rp {{ number_format($monthSales, 0, ',', '.') }}</h4>
          <p class="mt-3 mb-2"><strong>Rata-Rata Per Hari:</strong></p>
          <h4 class="text-secondary">Rp {{ number_format($avgDailySales, 0, ',', '.') }}</h4>
        </div>
      </div>
    </div>
  </div>

  <!-- Top 5 Produk & Pesanan Terbaru -->
  <div class="row mb-4">
    <!-- Top 5 Produk Terlaris -->
    <div class="col-lg-6 mb-4">
      <div class="card shadow h-100">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Top 5 Produk Terlaris</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive d-none d-md-block">
            <table class="table table-bordered table-striped mb-0">
              <thead class="thead-light">
                <tr>
                  <th>No</th>
                  <th>Nama Produk</th>
                  <th class="text-right">Jumlah Terjual</th>
                </tr>
              </thead>
              <tbody>
                @foreach($topProducts as $index => $product)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td class="text-right">{{ $product->total_sold }}</td>
                  </tr>
                @endforeach
                @if($topProducts->isEmpty())
                  <tr>
                    <td colspan="3" class="text-center">Belum ada data penjualan.</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
          <!-- Card list untuk mobile -->
          <div class="d-block d-md-none">
            @foreach($topProducts as $index => $product)
              <div class="card mb-2 shadow-sm">
                <div class="card-body p-2">
                  <div class="d-flex justify-content-between">
                    <p class="mb-1"><strong>#{{ $index + 1 }}</strong> {{ $product->name }}</p>
                    <p class="mb-1 font-weight-bold">{{ $product->total_sold }}</p>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <!-- Pesanan Terbaru -->
    <div class="col-lg-6 mb-4">
      <div class="card shadow h-100">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Pesanan Terbaru</h6>
          <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body">
          <div class="table-responsive d-none d-md-block">
            <table class="table table-bordered table-hover mb-0">
              <thead class="thead-light">
                <tr>
                  <th>No. Pesanan</th>
                  <th class="text-right">Total (Rp)</th>
                  <th>Tanggal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($recentOrders as $order)
                  <tr>
                    <td>{{ $order->id }}</td>
                    <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                  </tr>
                @endforeach
                @if($recentOrders->isEmpty())
                  <tr>
                    <td colspan="3" class="text-center">Belum ada pesanan.</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
          <!-- Card list untuk mobile -->
          <div class="d-block d-md-none">
            @foreach($recentOrders as $order)
              <div class="card transaction-card shadow-sm">
                <div class="card-body p-2">
                  <div class="d-flex justify-content-between">
                    <p class="mb-1"><strong>Pesanan #{{ $order->id }}</strong></p>
                    <p class="mb-1 small text-muted">{{ $order->created_at->format('d-m-Y H:i') }}</p>
                  </div>
                  <div class="d-flex justify-content-between">
                    <p class="mb-0">Total:</p>
                    <p class="mb-0 font-weight-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- /.container-fluid -->

<!-- Script: Chart.js & jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Grafik Penjualan per Hari (Line Chart)
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: @json($labels),   // Contoh: ["28 May", "29 May", ...]
        datasets: [{
          label: 'Total Penjualan',
          data: @json($totals),   // Contoh: [150000, 250000, ...]
          fill: false,
          borderWidth: 2,
          borderColor: 'rgba(78, 115, 223, 1)',
          pointBackgroundColor: 'rgba(78, 115, 223, 1)',
          pointBorderColor: '#fff',
          pointRadius: 3
        }]
      },
      options: {
        scales: {
          x: { grid: { display: false } },
          y: {
            beginAtZero: true,
            ticks: {
              callback: value => 'Rp ' + value.toLocaleString('id-ID')
            }
          }
        },
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: ctx => {
                let v = ctx.parsed.y;
                return 'Rp ' + v.toLocaleString('id-ID');
              }
            }
          }
        },
        maintainAspectRatio: false
      }
    });
  });
</script>
@endsection
