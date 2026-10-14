## KantongKu

Sistem informasi pengelolaan keuangan pribadi mahasiswa berbasis Laravel 12, PostgreSQL, dan Bootstrap 5. Antarmuka menggunakan CDN; proyek tidak memerlukan build Vite untuk dijalankan.

### Fitur

- Login manual dengan role `admin` dan `mahasiswa`.
- Mahasiswa dapat mendaftar sendiri; role pendaftaran selalu ditetapkan sebagai mahasiswa.
- CRUD pemasukan dan pengeluaran, filter berdasarkan kata kunci/tanggal, serta paginasi.
- Mahasiswa hanya mengakses transaksi miliknya; admin dapat mencatat untuk mahasiswa tertentu.
- Ringkasan bulanan, tren harian, perbandingan saldo dengan bulan sebelumnya, rasio tabungan, dan insight otomatis.
- Admin dapat mengelola akun mahasiswa. Akun yang masih memiliki transaksi tidak bisa dihapus agar riwayat tetap utuh.
- Halaman error khusus untuk akses ditolak, halaman hilang, sesi kedaluwarsa, batas permintaan, dan gangguan server.

### Kebutuhan

- PHP 8.2 atau lebih baru dengan ekstensi `pdo_pgsql` dan `pgsql` aktif.
- Composer dan PostgreSQL yang berjalan pada port `5432`.
- Database PostgreSQL bernama `kantongku`.

### Menjalankan aplikasi

Salin `.env.example` menjadi `.env`, lalu sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan instalasi PostgreSQL lokal. Isi `.env.example` sengaja menggunakan `APP_DEBUG=false`.

```powershell
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Buka `http://127.0.0.1:8000`. `migrate --seed` mempertahankan data yang sudah ada; gunakan `migrate:fresh --seed` hanya jika memang ingin menghapus dan membuat ulang semua tabel.

### Akun demo

| Role      | Email                      | Kata sandi    |
| --------- | -------------------------- | ------------- |
| Admin     | `admin@kantongku.test`     | `password123` |
| Mahasiswa | `mahasiswa@kantongku.test` | `password123` |

Ganti kata sandi demo sebelum aplikasi dipakai di luar lingkungan lokal.

### Pengujian

Tes memakai SQLite in-memory agar tidak mengubah database PostgreSQL lokal. Di Laragon Windows, aktifkan ekstensi `php_sqlite3.dll` dan `php_pdo_sqlite.dll` pada `php.ini`, atau jalankan PHPUnit hanya dengan ekstensi tersebut untuk proses ini:

```powershell
php -d extension=php_sqlite3.dll -d extension=php_pdo_sqlite.dll vendor/phpunit/phpunit/phpunit --testdox
```

### Catatan alur data

Jumlah uang disimpan sebagai decimal dua angka. Setiap catatan terkait dengan satu akun mahasiswa. Penghapusan akun mahasiswa yang memiliki pemasukan atau pengeluaran ditolak untuk mencegah hilangnya histori keuangan.
