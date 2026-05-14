# Sistem Informasi Keuanggan

Deskripsi singkat

Aplikasi ini adalah sistem sederhana untuk pengajuan, persetujuan, dan pemantauan anggaran dalam lingkungan instansi/unit kerja. Dibangun dengan CodeIgniter (versi 3.x), aplikasi menyediakan fitur:

- Form pengajuan dana (pengisian rincian proyek, akun, jumlah)
- Autocomplete untuk DPSJ, Project (RKA), dan Akun
- Validasi sisa anggaran berdasarkan data RKA
- Alur persetujuan: diajukan -> disetujui/ditolak oleh admin/keuangan
- Daftar unit, laporan ringkas, dan monitoring pengajuan

Struktur proyek (highlight)

- `application/controllers/Anggaran.php` - Controller utama untuk pengajuan anggaran, autocomplete, pengecekan anggaran, dan daftar unit.
- `application/models/Anggaran_model.php` - Model untuk operasi CRUD pengajuan.
- `application/models/Rka_model.php` - Model untuk mengakses RKA dan menghitung sisa anggaran.
- `application/views/` - Koleksi tampilan (form, list, detail, template header/footer).
- `application/config/` - Konfigurasi CodeIgniter (database, autoload, routes, dll).

Persyaratan

- PHP 5.6+ (direkomendasikan PHP 7.x untuk performa dan keamanan)
- CodeIgniter 3.x
- MySQL / MariaDB
- Webserver yang mendukung PHP (mis. UniServerZ, XAMPP, WampServer, IIS)

Instalasi cepat (Windows + UniServerZ)

1. Salin folder proyek ke folder web server Anda (contoh: `F:\\UniServerZ\\www\\keuangan`).
2. Pastikan folder `application` dan `system` milik framework CodeIgniter tersedia di dalam folder proyek.
3. Buat database MySQL baru (misal `keuangan_db`) dan import skema/tabel yang diperlukan. (Catatan: repository ini tidak menyertakan dump SQL; lihat bagian "Database" di bawah.)
4. Edit konfigurasi database di `application/config/database.php` dan isi host, username, password, database.
5. (Opsional) Edit base_url di `application/config/config.php` menjadi `http://localhost/keuangan` atau sesuai pemasangan Anda.
6. Buka browser dan akses aplikasi: `http://localhost/keuangan` (atau path set di langkah 5).

Database

- Tabel penting:
  - `pengajuan` / `pengajuan_pemohon` (atau nama serupa) — menyimpan header pengajuan (nomor, tanggal, unit, pemohon, status).
  - `pengajuan_rincian` — menyimpan rincian masing-masing item pengajuan (project_costing, akun, jumlah, keterangan).
  - `rka` — daftar RKA (kode_kegiatan, kode_akun, deskripsi_akun, anggaran, kode_dana, kode_dpsj).
  - `unit_kerja` — data unit (kode_dpsj, deskripsi_dpsj, nama_unit, kode_bidang).
  - `monitoring` — catatan monitoring pengajuan.

Catatan: struktur tabel dan nama kolom di atas dipetakan dari penggunaan di controller. Pastikan skema kolom mengandung kolom yang disebutkan di controller (mis. `kode_kegiatan`, `kode_akun`, `anggaran`, `kode_dana`, `kode_dpsj`, dsb.). Jika Anda memerlukan dump SQL, saya bisa membantu membuatkan skema dasar.

Routing dan akses

- Controller utama: `application/controllers/Anggaran.php`
  - `index()` — daftar pengajuan
  - `pengajuan()` — form pengajuan (GET/POST)
  - `detail($id)` — detail pengajuan
  - `approve($id)`, `reject($id)` — aksi persetujuan (hanya untuk role admin/keuangan)
  - `search_dpsj()`, `search_project()`, `search_akun()` — endpoints AJAX untuk autocomplete (mengembalikan HTML tabel)
  - `check_anggaran()` — endpoint AJAX yang mengembalikan JSON (validasi sisa anggaran)
  - `generate_nomor_pengajuan($kode_unit)` — return nomor pengajuan (JSON)

Autentikasi dan session

- Aplikasi menggunakan session CodeIgniter untuk otentikasi dan penyimpanan user. Controller `Anggaran::__construct()` memeriksa session key `logged_anggaran`; jika tidak ada, pengguna diarahkan ke `auth/login`.
- Pastikan tabel/users dan mekanisme login tersedia (controller `Auth.php` terlihat ada di folder `controllers`).

Pengembangan & debugging

- Aktifkan debug di `application/config/config.php` jika ingin menampilkan error PHP/CodeIgniter.
- Gunakan `log_message()` atau CodeIgniter profiler untuk menelusuri query dan performa.
- Beberapa method di controller membangun HTML tabel langsung (untuk autocomplete). Ini bekerja via AJAX yang menerima HTML; jika ingin mengubahnya ke JSON, perlu menyesuaikan frontend JavaScript.

Keamanan & perbaikan yang direkomendasikan

- Hindari menyusun kueri SQL dengan menyisipkan variabel langsung (SQL injection). Gunakan query binding atau Active Record (mis. `$this->db->query($sql, array($param))` atau `$this->db->like()`). Beberapa method saat ini menyisipkan variabel langsung ke string SQL.
- Sanitasi input pengguna (XSS) saat menampilkan di view.
- Gunakan prepared statements dan validasi sisi server untuk semua endpoint.

Contributor notes

- Coding style mengikuti CodeIgniter 3.x konvensi controller/model/view.
- Untuk menambahkan fitur baru, buat method di controller dan model, lalu buat view di `application/views/anggaran/`.


Follow-up yang disarankan

- Tambah SQL schema / sample data (dump .sql)
- Ubah autocomplete agar mereturn JSON (lebih mudah bagi frontend modern)
- Tambah unit tests untuk model utama (`Anggaran_model`, `Rka_model`)
- Tambah dokumentasi API singkat jika frontend berbicara ke endpoint AJAX

---
