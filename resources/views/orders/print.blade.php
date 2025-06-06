<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Struk Pesanan #{{ $order->id }}</title>
  <style>
    /* Atur ukuran kertas thermal 80 mm */
    @page {
      size: 80mm auto;
      margin: 0;
    }
    body {
      width: 80mm;
      margin: 0 auto;
      padding: 5px 5px 0 5px;
      font-family: 'Courier New', Courier, monospace;
      font-size: 11px;
      line-height: 1.3;
    }
    .store-name {
      text-align: center;
      font-weight: bold;
      font-size: 13px;
      margin-bottom: 3px;
    }
    .separator {
      border-bottom: 1px dashed #000;
      margin: 4px 0;
    }
    .info, .footer {
      font-size: 10px;
      margin-bottom: 4px;
    }
    .info p {
      margin: 2px 0;
    }
    .items {
      width: 100%;
      border-collapse: collapse;
      font-size: 10px;
    }
    .items td, .items th {
      padding: 2px 0;
    }
    .items .col-name {
      width: 55%;
      text-align: left;
      vertical-align: top;
      white-space: nowrap;
      overflow: hidden;
    }
    .items .col-detail {
      width: 25%;
      text-align: center;
      vertical-align: top;
      white-space: nowrap;
    }
    .items .col-sub {
      width: 20%;
      text-align: right;
      vertical-align: top;
      white-space: nowrap;
    }
    .total-row {
      border-top: 1px dashed #000;
      padding-top: 3px;
      margin-top: 3px;
      font-weight: bold;
    }
    .footer {
      text-align: center;
      font-size: 9px;
    }
  </style>
</head>
<body onload="window.print()">
  <!-- Nama Toko -->
  <div class="store-name">
    Tehdesa<br>
    Sistem Pemesanan
  </div>

  <div class="separator"></div>

  <!-- Informasi Pesanan -->
  <div class="info">
    <p>No. Pesanan : {{ $order->id }}</p>
    <p>Tanggal     : {{ $order->created_at->format('d-m-Y H:i') }}</p>
    <p>Status      : {{ ucfirst($order->status) }}</p>
  </div>

  <div class="separator"></div>

  <!-- Daftar Item -->
  <table class="items">
    @foreach($order->details as $idx => $detail)
      @php
        // Potong nama produk maks 18 karakter agar tidak melebar
        $rawName = $detail->product->name;
        if(strlen($rawName) > 18) {
            $name = substr($rawName, 0, 15) . '...';
        } else {
            $name = $rawName;
        }
        $qty   = $detail->quantity;
        $price = number_format($detail->unit_price, 0, ',', '.');
        $sub   = number_format($detail->sub_total, 0, ',', '.');
        $detailText = $qty . '×' . $price;
      @endphp

      <tr>
        <td class="col-name">{{ $name }}</td>
        <td class="col-detail">{{ $detailText }}</td>
        <td class="col-sub">Rp {{ $sub }}</td>
      </tr>
    @endforeach

    <tr class="total-row">
      <td colspan="2">Total</td>
      <td class="col-sub">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
    </tr>
  </table>

  <div class="separator"></div>

  <!-- Footer -->
  <div class="footer">
    Terima kasih telah<br>
    berbelanja di Tehdesa!
  </div>
</body>
</html>
