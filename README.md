[ZulfianCRM](http://44.198.188.92:8080/)
# Zulfian CRM — CRM Sederhana untuk Perusahaan Internet (ISP)

Zulfian CRM adalah aplikasi CRM sederhana berbasis Laravel 11 untuk membantu Internet Service Provider (ISP) mengelola proses dari lead hingga menjadi pelanggan. Aplikasi ini mendukung katalog produk (paket internet), pengajuan oleh calon pelanggan (lead), penugasan ke sales oleh manager, pembaruan status oleh sales, hingga persetujuan/penolakan oleh manager.

Aplikasi menggunakan Bootstrap 5 dan DataTables untuk tampilan tabel yang nyaman. Autentikasi menggunakan sesi (login/logout) dengan dua peran utama: manager dan sales.


## Fitur Utama
- Katalog Produk (paket internet) untuk ditampilkan di halaman utama.
- Formulir pembelian via modal untuk calon pelanggan (lead).
- Lead unik berdasarkan nomor telepon (nomor telepon diseragamkan ke digit saja). Jika lead dengan telepon yang sama sudah ada, sistem tidak membuat lead baru, melainkan menggunakan lead yang sudah ada.
- Proyek (Project) otomatis dibuat setelah lead mengajukan pembelian, status awal “waiting”.
- Manager dapat:
  - CRUD Produk.
  - Melihat semua Leads dan Projects (waiting, in_progress, waiting_for_approval, rejected).
  - Assign Project ke Sales dan mengubah status Project menjadi “in_progress”.
  - Menerima atau menolak Project yang berstatus “waiting_for_approval”.
  - CRUD User untuk role sales.
- Sales dapat:
  - Melihat Project yang ditugaskan kepadanya (in_progress, waiting_for_approval, rejected).
  - Mengubah status dari “in_progress” menjadi “waiting_for_approval”.
- Halaman Customers menampilkan Project yang berstatus “approved” (manager melihat semua, sales hanya miliknya).
- Tabel pada seluruh halaman menggunakan DataTables.


## Alur Bisnis (Langkah demi Langkah)
1. Halaman Utama (Publik)
   - Pengunjung melihat katalog produk paket internet.
   - Saat menekan tombol “Beli”, muncul modal formulir untuk mengisi data lead: nama, email, telepon, alamat.

2. Submit Form Lead
   - Sistem akan menormalkan nomor telepon menjadi hanya angka.
   - Jika sudah ada lead dengan nomor telepon tersebut, sistem menggunakan lead yang sudah ada.
   - Jika belum ada, sistem membuat lead baru dengan data yang diisi.
   - Sistem kemudian membuat Project baru dengan status “waiting” dan user_id (sales) masih kosong.

3. Manager: Assign ke Sales (Dashboard Login sebagai Manager)
   - Manager melihat daftar Project dengan status waiting/in_progress/waiting_for_approval/rejected.
   - Pada Project berstatus “waiting”, Manager dapat menugaskan (assign) ke salah satu Sales.
   - Setelah assign, status Project berubah menjadi “in_progress”.

4. Sales: Ajukan Persetujuan
   - Sales hanya melihat Project yang ditugaskan kepadanya, dengan status in_progress/waiting_for_approval/rejected.
   - Jika pekerjaan siap diajukan, Sales mengubah status dari “in_progress” ke “waiting_for_approval”.

5. Manager: Approve/Reject
   - Manager melihat Project berstatus “waiting_for_approval”.
   - Manager dapat menyetujui (approved) atau menolak (rejected). Jika disetujui, Project akan tampil di halaman Customers.

6. Customers
   - Menampilkan semua Project yang telah “approved”.
   - Manager melihat semua, Sales hanya yang menjadi tanggung jawabnya.


## Peran dan Hak Akses
- Manager
  - Kelola produk (CRUD), kelola user sales (CRUD), assign project, approve/reject project, melihat semua leads/projects/customers.
- Sales
  - Melihat project yang ditugaskan kepadanya, mengubah status ke waiting_for_approval, melihat customers yang dia tangani.


## Akun Default (Seeder)
Setelah seeding, tersedia akun berikut:
- Manager: manager@example.com / password
- Sales: sales@example.com / password


## Instalasi Cepat
1. Persiapan
   - PHP 8.2+, Composer, dan database (PostgreSQL).
2. Konfigurasi
   - Salin .env.example menjadi .env, lalu sesuaikan koneksi database.
3. Instal dependensi dan migrasi database
   - composer install
   - php artisan key:generate (jika belum otomatis)
   - php artisan migrate --seed
4. Jalankan aplikasi
   - php artisan serve
   - Buka http://localhost:8000
5. Login
   - Masuk sebagai Manager/Sales menggunakan kredensial di atas untuk mengakses dashboard.


## Catatan Teknis
- Tabel DataTables diinisialisasi sederhana: let table = new DataTable('#myTable');
- Nomor telepon lead dijadikan unik di level database (unique index) dan dinormalisasi (digits-only) saat disimpan.
- Status project yang digunakan: waiting, in_progress, waiting_for_approval, approved, rejected.


## Panduan Deploy di Lokal (Step-by-Step)
1. Prasyarat
   - PHP 8.2+ dan Composer terpasang.
   - Database (default: PostgreSQL).
   - Ekstensi PHP umum aktif (pdo, pdo_pgsql untuk PostgreSQL).

2. Clone repositori
   - git clone https://github.com/your-org/revan_crm.git
   - cd revan_crm

3. Salin dan konfigurasi environment
   - Salin file .env.example menjadi .env
   - Sesuaikan variabel berikut sesuai lingkungan lokal Anda:
     - APP_NAME=RevanCRM
     - APP_ENV=local
     - APP_DEBUG=true
     - APP_URL=http://localhost:8000
     - DB_CONNECTION=pgsql
     - DB_HOST=127.0.0.1
     - DB_PORT=5432
     - DB_DATABASE=revan_crm
     - DB_USERNAME=postgres
     - DB_PASSWORD=(password database Anda)
   - Buat database bernama revan_crm di PostgreSQL (atau gunakan nama lain sesuai .env).
     - Contoh (opsional, via CLI): createdb -U postgres revan_crm

   Opsi SQLite (jika tidak ingin memasang PostgreSQL):
   - Ubah .env:
     - DB_CONNECTION=sqlite
     - Hapus/abaikan pengaturan DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD
   - Buat file database/database.sqlite (file kosong sudah cukup).

4. Instal dependensi PHP
   - composer install

5. Generate application key (jika belum)
   - php artisan key:generate

6. Migrasi dan seeding database
   - php artisan migrate --seed
   - Perintah ini akan membuat tabel dan data awal, termasuk akun default:
     - Manager: manager@example.com / password
     - Sales: sales@example.com / password

7. Jalankan server lokal
   - php artisan serve
   - Akses aplikasi di browser: http://localhost:8000

8. Login ke dashboard
   - Gunakan kredensial di atas untuk masuk sebagai Manager atau Sales.

9. Catatan frontend
   - Aplikasi menggunakan Bootstrap 5 dan DataTables via CDN
