 Sistem Informasi Keuangan FISIP UI
Sistem Informasi Keuangan FISIP UI adalah aplikasi berbasis web yang dikembangkan untuk mengotomatisasi proses pengajuan dana, pengelolaan anggaran, serta pelaporan pertanggungjawaban (SPJ) di lingkungan Fakultas Ilmu Sosial dan Ilmu Politik Universitas Indonesia.
🚀 Latar Belakang
Sebelum sistem ini dikembangkan, proses keuangan dilakukan secara manual menggunakan spreadsheet terpisah, yang menimbulkan beberapa permasalahan:

Risiko over-budgeting akibat keterlambatan update pagu
Proses verifikasi yang lambat karena berbasis dokumen fisik
Kurangnya transparansi status pengajuan dan pencairan dana

🎯 Tujuan
Membangun platform keuangan terpusat yang:

Mengotomatisasi pengajuan dana (D01 & D02)
Menyediakan pengecekan pagu anggaran secara real-time
Mendigitalisasi proses pelaporan SPJ
Meningkatkan transparansi, efisiensi, dan akuntabilitas keuangan


👥 Pengguna Sistem
Dipakai oleh ±150 pengguna dengan peran:

Pemegang Uang Muka (PUM)
Verifikator Anggaran
Koordinator PUM (Korpum)
Kasir
Manajer Keuangan
Junior Akuntan


🧩 Fitur Utama
1. Modul Pengajuan D01 (UMKO)

Pengajuan dana operasional
Validasi otomatis terhadap saldo anggaran
Persetujuan berjenjang

2. Modul Pengajuan D02 (Direct Payment)

Pengajuan pembayaran langsung
Alur verifikasi multi-level
Integrasi pencetakan invoice (Permintaan Pembayaran)

3. Modul Pertanggungjawaban (SPJ)

Validasi dokumen pertanggungjawaban
Pengelolaan revisi (with change & without change)
Pengembalian sisa dana

4. Budget Control System

Pengecekan pagu otomatis
Pencegahan over-budgeting
Pengalihan ke proses mutasi jika saldo tidak cukup


🛠️ Teknologi yang Digunakan

























TeknologiDeskripsiCodeIgniter 3 (PHP)Framework backend dengan arsitektur MVCMariaDBDatabase relasionalAJAX & jQueryInteraksi dinamis tanpa reload halamanLighttpdWeb server untuk pengujian lokal

🏗️ Arsitektur & Pendekatan
Pengembangan menggunakan pendekatan:
Iteratif Berbasis Komponen (3 Sprint):

Modul Pencatatan Pagu
Modul Transaksional (D01 & D02)
Modul SPJ (Pertanggungjawaban)


⚙️ Peran Pengembang
Sebagai Kontributor Utama, tanggung jawab utama meliputi:

Pengembangan logika validasi pagu anggaran
Perancangan skema database
Implementasi interface pengajuan berbasis AJAX
Pengelolaan status transaksi (draft → validasi → closed)
Setup dan konfigurasi environment testing


📈 Dampak Implementasi

























AspekSebelumSesudah⏱️ Waktu Proses3–4 hari< 1 hari (real-time)⚠️ Risiko Over-budgetingTinggi0% (terkontrol sistem)🔍 TransparansiManualReal-time di sistem

📦 Output & Deliverables

✅ Aplikasi Web Sistem Keuangan (aktif digunakan)
✅ Dokumentasi Database (DOC-DB-FIN-2025)
✅ SOP & Panduan Pengguna (SOP-FIN-FISIP-04)


🔄 Integrasi Sistem

Terintegrasi dengan proses verifikasi tingkat universitas
Menghasilkan dokumen Invoice Permintaan Pembayaran (PP)


🔧 Pemeliharaan

Maintenance database rutin setiap semester
Penyesuaian aturan pajak mengikuti regulasi terbaru
Sistem dikelola oleh tim operasional IT FISIP UI


📸 Dokumentasi
Dokumentasi mencakup:

Desain sistem dan database
SOP penggunaan
Laporan pengembangan
Screenshot aplikasi


📌 Status Proyek
✅ Selesai dan aktif digunakan (Production - Internal Faculty Server)

🤝 Kontribusi
Pengembangan dilakukan melalui kolaborasi antara:

Sub Unit IT & Digitalisasi OPF
Sub Bagian Keuangan FISIP UI
Verifikator tingkat universitas


📄 Lisensi
Proyek ini digunakan untuk kebutuhan internal institusi (FISIP UI).
