@extends('layouts.app')

@section('content')
<style>
  /* Tinggi chart agar tidak menjorok terlalu besar */
  #salesChart {
    height: 300px;
  }
</style>

<div class="container-fluid">

  <!-- Judul Dashboard -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
  </div>

  <!-- Alert Selamat Datang -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="alert border-left-secondary shadow alert-warning alert-dismissible fade show" role="alert">
        <strong>Selamat Datang!</strong> Anda telah masuk sebagai <strong>{{ Auth::user()->name }}</strong>.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Card Ringkasan -->
  <div class="row mb-4">
    <!-- Penjualan Hari Ini -->
    <div class="col-xl-4 col-md-6 mb-4">
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
    <div class="col-xl-4 col-md-6 mb-4">
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
    <!-- Total Transaksi -->
    <div class="col-xl-4 col-md-12 mb-4">
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

  <!-- Grafik Penjualan Per Hari -->
  <div class="row mb-4">
    <div class="col-lg-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan 7 Hari Terakhir</h6>
        </div>
        <div class="card-body">
          <canvas id="salesChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Top 5 Produk Terlaris -->
  <div class="row mb-4">
    <div class="col-lg-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Top 5 Produk Terlaris</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead class="thead-light">
                <tr>
                  <th>No</th>
                  <th>Nama Produk</th>
                  <th>Jumlah Terjual</th>
                </tr>
              </thead>
              <tbody>
                @foreach($topProducts as $index => $product)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->total_sold }}</td>
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
        </div>
      </div>
    </div>
  </div>

</div> <!-- /.container-fluid -->

<!-- DataTables dan Chart.js (CDN) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // --- Grafik Penjualan per Hari (Line Chart) ---
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: @json($labels),       // e.g. ["28 May", "29 May", ...]
        datasets: [{
          label: 'Total Penjualan',
          data: @json($totals),       // e.g. [150000, 250000, ...]
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
          x: {
            grid: {
              display: false
            }
          },
          y: {
            beginAtZero: true,
            ticks: {
              // Format angka menjadi dengan ribuan (opsional)
              callback: function(value) {
                return 'Rp ' + value.toLocaleString('id-ID');
              }
            }
          }
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                let val = context.parsed.y;
                return 'Rp ' + val.toLocaleString('id-ID');
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
