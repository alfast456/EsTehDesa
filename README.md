# Tehdesa Order System

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
3. [Konfigurasi `.env`](#konfigurasi-env)  
4. [Menjalankan Migrasi & Seeder](#menjalankan-migrasi--seeder)  
5. [Menjalankan Aplikasi](#menjalankan-aplikasi)  
6. [Fitur Utama](#fitur-utama)  
7. [Struktur Direktori](#struktur-direktori)  
8. [Catatan Tambahan](#catatan-tambahan)  

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
   cd tehdesa-order-system
   cp .env.example .env
   composer install
   php artisan key:generate
   // Atur koneksi DB di .env
   php artisan migrate
   npm install && npm run dev // jika ada asset front‐end
```