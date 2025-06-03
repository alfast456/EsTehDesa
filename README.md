# Tehdesa

Sistem Pemesanan Berbasis Website untuk usaha Tehdesa.  
Dibangun menggunakan Laravel 10, dengan fitur:
- CRUD Produk & Kategori
- Input Pesanan Dinamis (pilih produk, qty, hitung subtotal & grand total)
- Simulasi Pembayaran QRIS (DANA) Offline menggunakan `simple-qrcode`
- Manajemen Transaksi (status pending/paid, filter tanggal)
- Cetak Struk (view khusus untuk thermal/print)
- Dashboard Ringkasan & Grafik Penjualan (7 hari terakhir)
- Top 5 Produk Terlaris
- Otentikasi Admin/Kasir

## Daftar Isi

1. [Prasyarat](#prasyarat)  
2. [Instalasi](#instalasi)  

---

## Prasyarat

Sebelum memulai, pastikan Anda sudah menginstal hal-hal berikut pada mesin pengembangan Anda:

- PHP ≥ 8.1  
- Composer  
- Node.js & NPM  
- MySQL / MariaDB (atau database lain yang kompatibel)  
- Git  

---

## Instalasi

1. **Clone repository**  
   ```bash
   git clone https://github.com/username/tehdesa-order-system.git
   cd EsTehDesa
   cp .env.example .env
   composer install
   php artisan key:generate
   // Atur koneksi DB di .env
   php artisan migrate
   npm install && npm run dev // jika ada asset front‐end
