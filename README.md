# Determined Studio — Sistem Manajemen Produksi dan Gudang

Aplikasi web berbasis Laravel untuk mengelola bahan baku, vendor, barang jadi, permintaan material, perintah produksi, pencatatan hasil produksi, stok, serta laporan operasional. Akses dibedakan untuk tiga peran: **Admin**, **Gudang**, dan **Produksi**.

## Fitur Utama

### Administrator
- Dashboard ringkasan operasional.
- Manajemen vendor, material, produk, dan pengguna.
- Manajemen stok masuk dan keluar material.
- Pencatatan barang jadi yang diterima gudang.
- Manajemen perintah produksi dan status produksi.
- Pengaturan resep/BOM produk.
- Laporan stok, produksi, dan transaksi.

### Gudang
- Dashboard gudang.
- Mencatat pembelian/penerimaan bahan baku.
- Mengeluarkan material untuk produksi.
- Menerima hasil produksi dan menambah stok barang jadi.
- Memproses atau menolak permintaan material dari produksi.
- Memantau stok rendah dan riwayat transaksi.
- Mencetak bukti penerimaan atau hasil produksi.

### Produksi
- Dashboard progres produksi.
- Membuat dan memantau perintah produksi.
- Mengajukan permintaan material.
- Membatalkan atau menyelesaikan pekerjaan produksi.
- Mencatat hasil produksi.
- Melihat laporan progres, efisiensi, dan produksi harian.

## Teknologi

- PHP 8.2+
- Laravel 12
- Livewire 3 dan Volt
- Laravel Breeze
- Tailwind CSS
- Alpine.js
- Vite
- Pest untuk pengujian
- Database SQLite atau MySQL/MariaDB

## Persyaratan

- PHP 8.2 atau lebih baru
- Composer
- Node.js dan npm
- Laragon, XAMPP, atau server PHP lokal lain
- Ekstensi PHP umum untuk Laravel (misalnya `pdo`, `mbstring`, `openssl`, dan ekstensi PDO database)

## Instalasi di Laragon (Windows)

1. Clone atau buka project di folder Laragon:

   ```powershell
   cd C:\laragon\www
   git clone <URL_REPOSITORY> determined.studio
   cd determined.studio
   ```

2. Buat file konfigurasi lingkungan:

   ```powershell
   copy .env.example .env
   ```

3. Pasang dependensi PHP dan JavaScript:

   ```powershell
   composer install
   npm install
   ```

4. Generate application key:

   ```powershell
   php artisan key:generate
   ```

5. Siapkan database dan jalankan migrasi:

   ```powershell
   php artisan migrate
   ```

6. Jalankan seeder agar tersedia akun demonstrasi dan data awal:

   ```powershell
   php artisan db:seed
   ```

7. Jalankan aplikasi:

   ```powershell
   composer run dev
   ```

   Akses aplikasi melalui `http://localhost/determined.studio` atau URL virtual host Laragon.

Untuk setup otomatis bawaan project, gunakan:

```powershell
composer run setup
```

## Konfigurasi Database

Konfigurasi default `.env.example` menggunakan SQLite. Pastikan file SQLite tersedia:

```powershell
New-Item database\database.sqlite -ItemType File -Force
```

Kemudian atur `.env`:

```dotenv
DB_CONNECTION=sqlite
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

Untuk menggunakan MySQL/MariaDB:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=determined_studio
DB_USERNAME=root
DB_PASSWORD=
```

Setelah mengubah konfigurasi, bersihkan cache:

```powershell
php artisan optimize:clear
```

## Akun Demonstrasi

Akun berikut dibuat oleh `DatabaseSeeder`:

| Peran | Email | Password |
|---|---|---|
| Admin | `admin@determinedstudio.com` | `password123` |
| Gudang | `gudang@determinedstudio.com` | `password123` |
| Produksi | `produksi@determinedstudio.com` | `password123` |

> Ganti password akun demonstrasi sebelum aplikasi digunakan dalam lingkungan produksi.

## Alur Operasional

1. Admin membuat data vendor, material, produk, resep/BOM, dan perintah produksi.
2. Gudang mencatat penerimaan material dan memantau stok.
3. Produksi membuat permintaan material untuk perintah produksi.
4. Gudang memproses permintaan dan mengeluarkan material sesuai kebutuhan.
5. Produksi mengerjakan dan menyelesaikan perintah produksi.
6. Gudang mencatat barang jadi yang masuk ke gudang.
7. Admin dan pengguna terkait dapat melihat laporan stok dan produksi.

## Struktur Direktori

```text
app/
├── Http/
│   ├── Controllers/       # Controller per area Admin, Gudang, dan Produksi
│   └── Middleware/        # Otentikasi dan pembatasan peran
├── Livewire/              # Komponen/form Livewire
├── Models/                # Model Eloquent dan relasi data
resources/views/           # Blade template dan komponen UI
routes/web.php             # Route aplikasi
routes/auth.php            # Route autentikasi Breeze
database/migrations/       # Struktur database
database/seeders/          # Data awal dan akun demonstrasi
public/build/              # Asset hasil build Vite
tests/                     # Pengujian Pest
```

## Peran dan Akses

Pembatasan rute menggunakan middleware `role` pada `routes/web.php`:

- `/admin/*` — hanya `admin`
- `/gudang/*` — `gudang` dan `admin`
- `/produksi/*` — `produksi` dan `admin`

Akun tidak berwenang akan menerima respons HTTP `403`.

## Perintah Pengembangan

Menjalankan server Laravel, queue listener, log viewer, dan Vite sekaligus:

```powershell
composer run dev
```

Menjalankan pengujian:

```powershell
composer run test
```

Menjalankan build frontend untuk produksi:

```powershell
npm run build
```

Format kode PHP dengan Pint:

```powershell
vendor\bin\pint
```

## Pengujian

Project menggunakan Pest Framework:

```powershell
php artisan test
```

Tambahkan pengujian fitur pada `tests/Feature/` dan pengujian unit pada `tests/Unit/`.

## Pemecahan Masalah

**Halaman kosong atau konfigurasi lama**

```powershell
php artisan optimize:clear
php artisan view:clear
php artisan route:clear
```

**Aset frontend tidak berubah**

```powershell
npm run dev
```

Atau untuk hasil final:

```powershell
npm run build
```

**Gagal migrasi**

Periksa konfigurasi database pada `.env`, lalu jalankan:

```powershell
php artisan migrate:status
php artisan migrate
```

Untuk membuat ulang database lokal beserta data awal:

```powershell
php artisan migrate:fresh --seed
```

> Perintah `migrate:fresh` menghapus seluruh data pada database. Jangan jalankan pada database produksi.

## Lisensi

Project dikembangkan untuk kebutuhan pengelolaan operasional Determined Studio. Lisensi pihak framework Laravel menggunakan MIT License.
