# 🧋 EsTehDesa

**EsTehDesa** adalah aplikasi kasir berbasis Laravel yang juga dapat dijalankan sebagai aplikasi Android menggunakan **Apache Cordova**. Cocok digunakan untuk warung atau UMKM minuman kekinian di pedesaan.

---

## 🚀 Fitur Utama

- 🧑‍💼 Autentikasi (Login, Register, Logout)
- 📊 Dashboard utama setelah login
- 📦 Manajemen Produk (CRUD) *(admin only)*
- 🗂️ Manajemen Kategori Produk *(admin only)*
- 💰 Pemesanan & Pembayaran (Kasir/Admin)
- 🖨️ Print nota pemesanan
- 💳 Pembayaran manual + integrasi QRCode (generate, bukan scan)
- 📜 Riwayat Transaksi
- 📱 Bisa diakses via browser **ataupun Android app** via Cordova

---

## 🛠️ Teknologi yang Digunakan

| Kategori      | Stack                          |
|---------------|--------------------------------|
| Backend       | Laravel 10, PHP ^8.1           |
| Frontend      | Bootstrap 5, Blade, SASS       |
| Build Tools   | Vite, Laravel Vite Plugin      |
| Mobile        | Cordova + cordova-android      |
| Auth          | Laravel UI (Blade scaffolding) |
| Tambahan      | `simple-qrcode`, Axios         |

---

## ⚙️ Instalasi Laravel (Web)

```bash
# 1. Clone repo
git clone https://github.com/alfast456/EsTehDesa.git
cd EsTehDesa

# 2. Install dependency backend
composer install

# 3. Install dependency frontend
npm install

# 4. Setup env dan generate key
cp .env.example .env
php artisan key:generate

# 5. Konfigurasi database di .env
# DB_DATABASE=estehdesa
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan migrasi
php artisan migrate

# 7. Jalankan aplikasi
npm run dev
php artisan serve
```

---

## 📱 Build Android dengan Cordova

> Aplikasi Laravel berjalan secara lokal, dan dibungkus menjadi APK via Cordova WebView.

### 1. Instalasi Cordova

```bash
npm install -g cordova
```

### 2. Buat project Cordova

```bash
cordova create com.android.esteh “Esteh”
cd com.android.esteh
cordova platform add android
```

### 3. Integrasi Laravel ke Cordova

- Pastikan Laravel kamu bisa diakses di IP LAN (contoh `http://192.168.0.5:8000`)
- Ubah file `config.xml` dan `index.html` agar memuat URL Laravel via WebView
- Atur `AndroidManifest.xml` agar mengizinkan akses internet

### 4. Build APK

```bash
cordova build android
```

APK akan berada di folder `platforms/android/app/build/outputs/apk/debug/app-debug.apk`.

---

## 🔐 Login Dummy (Opsional)

```
Email: admin@example.com
Password: password
```

---

## 📦 Route Aplikasi

- `/` → Login
- `/dashboard` → Dashboard
- `/products`, `/categories`
- `/orders` → Pemesanan
- `/transactions`

---

## 📝 Lisensi

MIT © [Alfa Hardiyansyah](https://github.com/alfast456)