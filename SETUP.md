# Setup Perpustakaan Desa

## Langkah-langkah Install

### 1. Copy file ke project Laravel
Salin semua folder/file dari repo ini ke project Laravel kamu.

### 2. Konfigurasi `.env`
```env
APP_NAME="Perpustakaan Desa"
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpustakaan_desa
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Jalankan Migration + Seeder
```bash
php artisan migrate
php artisan db:seed
```

Ini akan membuat tabel `visitors`, `visits`, `admins` dan membuat akun admin default:
- **Username:** `admin`
- **Password:** `admin123`

> ⚠️ Ganti password admin setelah pertama kali login! Buka `database/seeders/AdminSeeder.php` dan ubah nilainya sebelum seeding.

### 4. Jalankan server
```bash
php artisan serve
```

---

## URL Aplikasi

| Halaman | URL |
|---|---|
| Login Pengunjung | `http://localhost:8000/` |
| Daftar Pengunjung | `http://localhost:8000/daftar` |
| Aktivitas (setelah login) | `http://localhost:8000/aktivitas` |
| Login Admin | `http://localhost:8000/admin/login` |
| Dashboard Admin | `http://localhost:8000/admin/dashboard` |

---

## Alur Pengunjung

```
1. Kunjungan pertama → /daftar → isi nama + alamat + HP → dapat ID (misal: faisal0001)
2. Kunjungan berikutnya → / → masukkan ID → pop up berhasil → lanjut ke /aktivitas
3. Pilih aktivitas (centang 1 atau lebih) → Selesai → balik ke halaman login
```

## Alur Admin

```
1. /admin/login → masuk dengan username & password
2. /admin/dashboard → lihat semua data kunjungan
3. Filter: Semua | Baca Buku | Pinjam Buku | Belajar Komputer
4. Search: cari berdasarkan nama atau ID pengunjung
```

---

## Struktur File

```
app/
  Models/
    Visitor.php         ← model pengunjung + generateVisitorId()
    Visit.php           ← model kunjungan
    Admin.php           ← model admin
  Http/Controllers/
    VisitorController.php ← register, login, aktivitas pengunjung
    AdminController.php   ← login & dashboard admin

database/migrations/
  ..._create_visitors_table.php
  ..._create_visits_table.php
  ..._create_admins_table.php

database/seeders/
  AdminSeeder.php       ← buat akun admin default
  DatabaseSeeder.php

resources/views/
  layouts/app.blade.php ← base layout pengunjung
  visitor/
    login.blade.php     ← halaman masuk pengunjung
    register.blade.php  ← halaman daftar pengunjung
    aktivitas.blade.php ← halaman pilih aktivitas
  admin/
    login.blade.php     ← halaman login admin
    dashboard.blade.php ← halaman data kunjungan

routes/
  web.php               ← semua route
```
