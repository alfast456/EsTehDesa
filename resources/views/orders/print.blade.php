<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Struk Pesanan #{{ $order->id }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      margin: 20px;
    }
    .store-name {
      text-align: center;
      font-weight: bold;
      font-size: 16px;
      margin-bottom: 10px;
    }
    .info, .items, .footer {
      width: 100%;
      margin-bottom: 10px;
    }
    .items th, .items td {
      padding: 4px 0;
    }
    .items th {
      border-bottom: 1px dashed #000;
    }
    .items td {
      border-bottom: 1px dotted #ccc;
    }
    .text-right {
      text-align: right;
    }
    .total-row {
      font-weight: bold;
      border-top: 1px solid #000;
      margin-top: 5px;
      padding-top: 5px;
    }
    .footer {
      margin-top: 20px;
      text-align: center;
      font-size: 10px;
    }
  </style>
</head>
<body onload="window.print()">
  <div class="store-name">
    Tehdesa<br>
    Sistem Pemesanan
  </div>

  <div class="info">
    <p>
      <span><strong>No. Pesanan:</strong> {{ $order->id }}</span><br>
      <span><strong>Tanggal:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</span><br>
      <span><strong>Status:</strong> {{ ucfirst($order->status) }}</span>
    </p>
  </div>

  <table class="items">
    <thead>
      <tr>
        <th>No</th>
        <th>Produk</th>
        <th class="text-right">Qty</th>
        <th class="text-right">Harga (Rp)</th>
        <th class="text-right">Sub (Rp)</th>
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
      <tr class="total-row">
        <td colspan="4" class="text-right">Total:</td>
        <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
      </tr>
    </tbody>
  </table>

  <div class="footer">
    <p>Terima kasih telah berbelanja di Tehdesa!</p>
    <p>www.tehdesa.local</p>
  </div>
</body>
</html>
