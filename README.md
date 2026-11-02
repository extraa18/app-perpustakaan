# Sistem Perpustakaan Digital Kampus

Aplikasi manajemen perpustakaan berbasis web untuk petugas dan admin kampus. Dibangun dengan Laravel 12 sebagai proyek mata kuliah Pemrograman Framework.

## Fitur

- Login dan logout dengan dua peran: **admin** dan **petugas** (menu Kategori hanya untuk admin)
- CRUD buku, kategori, anggota, dan transaksi peminjaman
- Pencarian anggota berdasarkan nama, dan pagination di semua daftar
- Proses pengembalian buku langsung dari daftar peminjaman
- Halaman profil dan ganti password
- Dashboard statistik dan Laporan Peminjaman yang mengonsumsi REST API internal
- REST API publik untuk buku, anggota, peminjaman, dan statistik (dokumentasi lengkap di [API.md](API.md))

## Prasyarat

- PHP 8.2 atau lebih baru
- Composer
- MySQL (misalnya lewat XAMPP) dan phpMyAdmin
- Git

## Cara Menjalankan Secara Lokal

1. Clone repository dan masuk ke foldernya:

    ```bash
    git clone https://github.com/extraa18/app-perpustakaan.git
    cd app-perpustakaan
    ```

2. Install dependency:

    ```bash
    composer install
    ```

3. Salin file environment dan buat application key:

    ```bash
    copy .env.example .env
    php artisan key:generate
    ```

    (Di macOS/Linux, ganti `copy` dengan `cp`.)

4. Buat database kosong bernama `db_perpustakaan` di MySQL.

5. Buka file `.env`, lalu sesuaikan bagian berikut:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=db_perpustakaan
    DB_USERNAME=root
    DB_PASSWORD=

    APP_FAKER_LOCALE=id_ID

    # Alamat server kedua untuk panggilan API internal (lihat bagian "Dua Server" di bawah)
    INTERNAL_API_URL=http://127.0.0.1:8011
    ```

6. Jalankan migration dan isi data contoh (20 buku, 15 anggota, 10 peminjaman, 3 akun):

    ```bash
    php artisan migrate --seed
    ```

7. Jalankan **dua** server di dua terminal berbeda (penjelasan di bawah):

    ```bash
    php artisan serve --port=8000
    ```

    ```bash
    php artisan serve --port=8011
    ```

8. Buka `http://127.0.0.1:8000` di browser dan login.

## Akun Uji

Semua akun memakai password `password`.

| Peran   | Email               |
| ------- | ------------------- |
| Admin   | admin@pens.ac.id    |
| Petugas | petugas1@pens.ac.id |
| Petugas | petugas2@pens.ac.id |

## Dua Server: Kenapa Perlu?

Halaman Dashboard dan Laporan Peminjaman mengambil datanya dengan memanggil API milik aplikasi ini sendiri lewat HTTP (`GET /api/stats` dan `GET /api/loans`). `php artisan serve` hanya memproses satu request pada satu waktu, jadi kalau request itu menunggu balasan dari server yang sama, keduanya saling menunggu (deadlock). Solusinya, server kedua di port `8011` melayani panggilan API internal, sementara port `8000` melayani browser.

Kalau server port `8011` tidak menyala, Dashboard dan Laporan tetap tampil dengan angka `0` dan tabel kosong, bukan error.

Di server produksi (Nginx + PHP-FPM) masalah ini tidak muncul, dan satu server sudah cukup.

## Dokumentasi API

Semua endpoint, contoh body, dan contoh response ada di [API.md](API.md).
