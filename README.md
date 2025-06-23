# 🧋 EsTehDesa

**EsTehDesa** adalah aplikasi kasir berbasis web yang dibangun menggunakan Laravel 10. Aplikasi ini cocok digunakan oleh UMKM atau warung minuman seperti es teh kekinian di pedesaan untuk mengelola produk, memproses pemesanan, dan mencatat transaksi dengan praktis dan efisien.

---

## 🚀 Fitur Utama

- 🧑‍💼 Autentikasi (Login, Register, Logout)
- 📊 Dashboard utama setelah login
- 📦 Manajemen Produk (CRUD) *(admin only)*
- 🗂️ Manajemen Kategori Produk *(admin only)*
- 💰 Pemesanan & Pembayaran (Kasir/Admin)
- 🖨️ Print nota pemesanan
- 💳 Pembayaran manual + integrasi QRCode (generate, bukan scan)
- 📜 Riwayat Transaksi (lihat detail)

---

## 🛠️ Teknologi yang Digunakan

| Kategori      | Stack                          |
|---------------|--------------------------------|
| Backend       | Laravel 10, PHP ^8.1           |
| Frontend      | Bootstrap 5, Blade, SASS       |
| Build Tools   | Vite, Laravel Vite Plugin      |
| Auth          | Laravel UI (Blade scaffolding) |
| Tambahan      | Axios                          |

---

## ⚙️ Instalasi

```bash
# 1. Clone repo
git clone https://github.com/alfast456/EsTehDesa.git
cd EsTehDesa

# 2. Install dependency backend
composer install

# 3. Install dependency frontend
npm install

# 4. Salin file .env dan generate key
cp .env.example .env
php artisan key:generate

# 5. Konfigurasi .env
# DB_DATABASE=estehdesa
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan migrasi
php artisan migrate

# 7. Jalankan server lokal
npm run dev
php artisan serve
```

---

## 🧪 Testing Akun (Opsional)

Jika kamu memiliki seeder atau user default:

```
Email: admin@example.com
Password: password
```

---

## 📦 Struktur Route

- `/` → Halaman login
- `/dashboard` → Dashboard utama (auth only)
- `/products`, `/categories` → Manajemen produk & kategori
- `/orders` → POS Kasir (create, store, show, destroy)
- `/orders/{id}/pay` → Proses pembayaran
- `/orders/{id}/print` → Cetak nota
- `/transactions` → Riwayat transaksi

---

## 🧰 Build Produksi

```bash
npm run build
```

---

## 📷 Screenshot (Opsional)

> Tambahkan screenshot tampilan halaman seperti dashboard, pemesanan, atau cetak QR code di sini.

---

## 🤝 Kontribusi

Pull request terbuka! Silakan fork repo, buat branch baru, dan ajukan PR.

---

## 📝 Lisensi

MIT © [Alfa Hardiyansyah](https://github.com/alfast456)