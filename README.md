# Sistem Perpustakaan Digital Kampus

Aplikasi manajemen perpustakaan berbasis web yang digunakan petugas/admin untuk mengelola data buku, anggota, dan transaksi peminjaman. Dibangun menggunakan Laravel 12 sebagai tugas mata kuliah OOP.

## Tujuan

Memudahkan pengelolaan perpustakaan kampus secara digital, mulai dari pencatatan koleksi buku, data anggota, hingga proses peminjaman dan pengembalian buku, menggantikan pencatatan manual yang rawan terjadi kesalahan input.

## Cara Menjalankan Project Secara Lokal

1. Clone repository ini dan masuk ke foldernya:

```bash
   git clone https://github.com/extraa18/app-perpustakaan.git
   cd app-perpustakaan
```

2. Install dependency lewat Composer:

```bash
   composer install
```

3. Copy file environment dan generate application key:

```bash
   copy .env.example .env
   php artisan key:generate
```

4. Buat database kosong bernama `db_perpustakaan` di MySQL, lalu sesuaikan kredensial database di file `.env` (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
5. Jalankan development server:

```bash
   php artisan serve
```

6. Buka `http://127.0.0.1:8000` di browser.

## Perbedaan Model, View, dan Controller

Model bertanggung jawab mengurus data dan bagiamana data tsb digunakan, misalnya bagaimana data buku atau anggota disimpan dan diambil dari database.

View bertanggung jawab murni menampilkan tampilan (HTML) yang dilihat pengguna, tanpa mengurus logika data.

Controller berperan sebagai penghubung, tugasnya menerima permintaan dari pengguna, meminta data ke Model, lalu mengirimkan data itu ke View agar bisa ditampilkan.
