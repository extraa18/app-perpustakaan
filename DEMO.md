# Skenario Demo: Sistem Perpustakaan Digital Kampus

Urutan demo untuk UAS, dengan perkiraan waktu sekitar 10 menit. Kolom "Yang disampaikan" berisi poin yang bisa diucapkan sambil demo.

## Persiapan (lakukan sebelum demo dimulai)

- [ ] MySQL di XAMPP sudah berwarna hijau
- [ ] Database dalam kondisi bersih: `php artisan migrate:fresh --seed`
- [ ] Dua server menyala di dua terminal: `php artisan serve --port=8000` dan `php artisan serve --port=8011`
- [ ] Browser dalam keadaan **logout**, buka `http://127.0.0.1:8000/login`
- [ ] Postman terbuka dengan collection `app-perpustakaan`, variable ID (`category_id`, `book_id`, `member_id`, `user_id`) sudah diisi ID yang ada di database
- [ ] Siapkan ISBN baru yang belum pernah dipakai (kolom ISBN bersifat unik)

Akun: admin `admin@pens.ac.id` dan petugas `petugas1@pens.ac.id`, password keduanya `password`.

## Langkah Demo

| No  | Langkah                | Yang dilakukan                                                                                                                                    | Hasil yang diharapkan                                                                     | Yang disampaikan                                                                                              |
| --- | ---------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------- |
| 1   | Login                  | Buka `/login`, masuk sebagai `admin@pens.ac.id` / `password`                                                                                      | Redirect ke Dashboard, navbar menampilkan nama dan peran (Admin)                          | Autentikasi memakai `Auth::attempt`, session di-regenerate setelah login                                      |
| 2   | Dashboard              | Tunjukkan tiga kartu: Total Buku, Total Anggota, Peminjaman Aktif                                                                                 | Angka terisi (20 buku, 15 anggota)                                                        | Angka diambil dari `GET /api/stats` lewat `Http::get()`, bukan query langsung. Itulah kenapa butuh dua server |
| 3   | Tambah kategori        | Menu Kategori, klik Tambah, isi nama "Biografi" dan deskripsi, simpan                                                                             | Redirect ke daftar kategori dengan pesan sukses hijau                                     | Menu Kategori hanya muncul untuk admin (middleware `CheckAdminRole`)                                          |
| 4   | Tambah buku            | Menu Buku, klik Tambah, isi data, pilih kategori "Biografi" (kosongkan ISBN atau pakai ISBN baru), simpan                                         | Buku baru muncul di daftar dengan nama kategori tampil                                    | Validasi lewat Form Request, nama kategori tampil berkat relasi `belongsTo` dan eager loading                 |
| 5   | Tambah anggota         | Menu Anggota, klik Tambah, isi NIM dan email baru, simpan. Lalu coba sekali lagi dengan NIM yang sama                                             | Pertama sukses. Kedua ditolak: "NIM ini sudah terdaftar"                                  | Aturan `unique` pada validasi, pesan error berbahasa Indonesia                                                |
| 6   | Cari anggota           | Di daftar anggota, ketik sebagian nama di kolom cari                                                                                              | Tabel hanya menampilkan nama yang cocok                                                   | Pencarian pakai `where like`, parameter `?search=` terbawa saat pindah halaman pagination                     |
| 7   | Buat peminjaman        | Menu Peminjaman, klik Tambah, pilih anggota baru tadi, isi tanggal pinjam dan kembali, centang buku "Biografi" tadi, simpan                       | Baris baru muncul dengan badge kuning "Dipinjam", kolom Petugas berisi nama admin         | Petugas otomatis dari `auth()->id()`. Satu transaksi menulis ke dua tabel: `loans` dan `loan_items`           |
| 8   | Lihat peminjaman aktif | Di daftar peminjaman, tunjukkan baris berbadge "Dipinjam". Buka Dashboard dan refresh                                                             | Angka Peminjaman Aktif bertambah 1                                                        | Badge status berwarna per kondisi (dipinjam, dikembalikan, terlambat)                                         |
| 9   | Proses pengembalian    | Kembali ke daftar peminjaman, klik **Kembalikan** pada transaksi tadi                                                                             | Status berubah jadi "Dikembalikan" (badge hijau), tombol Kembalikan hilang dari baris itu | Tombol hanya muncul saat status `dipinjam`. Tanggal dikembalikan terisi otomatis                              |
| 10  | Laporan                | Menu Laporan                                                                                                                                      | Tabel transaksi lengkap: anggota, petugas, buku, status. Transaksi tadi muncul            | Halaman ini juga mengonsumsi API (`GET /api/loans`). Pagination dibuat manual dari `meta`                     |
| 11  | Demo API di Postman    | Jalankan berurutan: `GET /stats`, `GET /books?judul=...`, `POST /books` (data valid, ISBN baru), `POST /books` dengan body `{}`, `GET /books/999` | Berturut-turut: 200, 200, 201, 422 (daftar error per field), 404                          | API Resource menentukan field yang keluar (password tidak pernah bocor). 422 dan 404 otomatis berbentuk JSON  |

## Tambahan (jika waktu masih ada)

| Skenario              | Yang dilakukan                                                                           | Hasil yang diharapkan                                                                                             |
| --------------------- | ---------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------- |
| Pembatasan peran      | Logout, login sebagai `petugas1@pens.ac.id`, ketik `/categories` langsung di address bar | Menu Kategori tidak ada di navbar, URL langsung menghasilkan halaman 403                                          |
| Fallback API internal | Matikan server port 8011 (Ctrl+C), refresh Dashboard dan Laporan                         | Halaman tetap tampil: statistik 0 dan tabel laporan kosong, bukan error 500. Nyalakan lagi servernya setelah demo |

## Hal yang Dihindari Saat Demo

- **Jangan menghapus buku yang pernah dipinjam.** Tabel `loan_items` merujuk ke buku itu lewat foreign key, jadi penghapusan akan gagal dengan error. Kalau mau demo hapus, buat buku baru yang belum pernah dipinjam.
- **Jangan memakai ISBN yang sama dua kali** saat menambah buku, baik lewat form maupun Postman.
- Jangan lupa mengisi ulang ID di variable Postman kalau database baru direset.

## Kalau Terjadi Masalah

| Gejala                                         | Penyebab                                            | Perbaikan cepat                                                                              |
| ---------------------------------------------- | --------------------------------------------------- | -------------------------------------------------------------------------------------------- |
| Error 500 berkode 2002 di halaman mana pun     | MySQL mati                                          | Start MySQL di XAMPP, refresh                                                                |
| Dashboard atau Laporan menggantung lama        | Server port 8011 belum menyala                      | Jalankan `php artisan serve --port=8011` di terminal kedua                                   |
| Dashboard menampilkan angka 0 padahal data ada | Server port 8011 mati atau `INTERNAL_API_URL` salah | Cek `http://127.0.0.1:8011/api/stats` di browser, harus muncul JSON                          |
| Login ditolak                                  | Password sudah pernah diganti lewat halaman profil  | Jalankan `php artisan db:seed --class=UserSeeder` untuk mengembalikan password ke `password` |
