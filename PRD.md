# PRODUCT REQUIREMENTS DOCUMENT (PRD)
## Sistem Tracking Pekerjaan Programmer IT (IT Programmer Work Tracker)

### 1. INFORMASI PROYEK
- Nama Proyek: IT Programmer Work Tracker
- Platform: Web Application (Laravel 11+ / PHP 8.3+)
- Database: MySQL
- Styling / UI: Tailwind CSS (Clean Dashboard, Anti-Slop, Ponytail minimalist)
- Pengguna: Tim IT Internal

---

### 2. TUJUAN SISTEM
Menyediakan sistem pelaporan dan pemantauan pekerjaan programmer dalam pengembangan aplikasi secara transparan, terstruktur, dan efisien tanpa birokrasi approval yang rumit. Sistem berfokus pada kemudahan input bukti pengerjaan, pencarian riwayat pembaruan sistem, dan rekapitulasi performa development.

---

### 3. USER ROLES & MATRIKS HAK AKSES
Sistem memiliki 3 role tanpa alur approval (laporan bersifat langsung terbit/log transparan):

1. Programer:
   - Autentikasi (Login / Logout).
   - Mengisi laporan pekerjaan harian/tugas pengembangan aplikasi.
   - Melihat dan mengedit laporan milik sendiri.
   - Mencari dan memfilter riwayat laporan sendiri maupun tim.

2. Staff IT:
   - Autentikasi (Login / Logout).
   - Read-only terhadap seluruh laporan programmer.
   - Melakukan pencarian (search) pekerjaan programmer.
   - Melihat dan mengunduh rekapitulasi data pekerjaan.

3. Kepala IT:
   - Autentikasi (Login / Logout).
   - Monitoring komprehensif seluruh aktivitas dan histori pekerjaan programmer.
   - Akses penuh pencarian (search) dan filter berdasarkan aplikasi, programmer, tipe, dan rentang tanggal.
   - Rekapitulasi laporan (ringkasan aktivitas, ekspor data untuk evaluasi kerja).

---

### 4. SPESIFIKASI DATA & FORM LAPORAN

Tiap entri laporan memiliki struktur data sebagai berikut:
1. ID / User: Relasi ke programmer yang bertugas (otomatis tersimpan dari sesi login).
2. Tanggal Pengerjaan: Timestamp / tanggal laporan dibuat.
3. Aplikasi yang Dikerjakan:
   Pilihan aplikasi (Dropdown / Select):
   - spontan
   - investasi
   - antrian-mpp
   - mpp
   - sadap
   - petapotensi
   - (Opsi tambahan: Lainnya / Aplikasi Baru jika kategori bikin baru aplikasi di luar daftar)
4. Tipe Pekerjaan (2 opsi):
   - Bikin Baru (Fitur baru / modul baru / sistem baru)
   - Pengubahan Fitur (Bugfix, refactor, pembaruan flow, styling update)
5. Judul: Nama ringkas pekerjaan/tugas (contoh: "Penambahan filter tanggal pada laporan antrian-mpp").
6. Deskripsi: Uraian detail apa yang dikerjakan, kendala teknis, atau alur yang diubah.
7. Screenshot Hasil (Wajib): Bukti visual screenshot antarmuka atau output setelah pengerjaan.
8. Screenshot Sebelumnya (Opsional): Bukti tampilan atau kondisi bug/tampilan awal sebelum perubahan dilakukan (before-after comparison).

---

### 5. FITUR UTAMA APLIKASI

#### A. Autentikasi & Otorisasi Sederhana
- Multi-role guard: Programer, Staff IT, Kepala IT.
- UI menyesuaikan role (tombol "+ Tambah Laporan" hanya muncul untuk role Programer).

#### B. Modul Laporan Pekerjaan (Programer)
- Form input cepat & responsif.
- Preview upload screenshot langsung di browser sebelum submit.
- Validasi file gambar (JPG, PNG, WebP, max 2MB).
- Modal / halaman detail untuk melihat komparasi screenshot before vs after secara jelas.

#### C. Modul Pencarian & Filter (Semua Role)
- Live / Form Search: Pencarian berbasis kata kunci pada judul, deskripsi, atau nama programmer.
- Filter terstruktur:
  * Filter berdasarkan Aplikasi (spontan, investasi, antrian-mpp, mpp, sadap, petapotensi).
  * Filter berdasarkan Tipe Pekerjaan (Bikin Baru vs Pengubahan Fitur).
  * Filter berdasarkan Programmer.
  * Filter rentang tanggal (Hari ini, Minggu ini, Bulan ini, Custom range).

#### D. Modul Rekapitulasi (Staff IT & Kepala IT)
- Ringkasan total task per aplikasi dan per programmer dalam periode tertentu.
- Komparasi jumlah task: Bikin Baru vs Pengubahan Fitur.
- Fitur Ekspor Data:
  * Rekap format PDF siap cetak untuk laporan manajerial.
  * Rekap format Excel / CSV untuk kebutuhan data mentah.

---

### 6. SKEMA DATABASE (MYSQL)

1. Tabel `users`:
   - id (BIGINT PK)
   - name (VARCHAR)
   - email (VARCHAR UNIQUE)
   - password (VARCHAR)
   - role (ENUM: 'programer', 'staff_it', 'kepala_it')
   - timestamps

2. Tabel `applications` (Master Aplikasi):
   - id (BIGINT PK)
   - slug (VARCHAR UNIQUE) -> 'spontan', 'investasi', 'antrian-mpp', 'mpp', 'sadap', 'petapotensi'
   - name (VARCHAR) -> Nama display aplikasi
   - description (TEXT, nullable)
   - timestamps

3. Tabel `work_reports` (Laporan Pekerjaan):
   - id (BIGINT PK)
   - user_id (BIGINT FK -> users.id)
   - application_id (BIGINT FK -> applications.id)
   - work_type (ENUM: 'bikin_baru', 'pengubahan_fitur')
   - title (VARCHAR)
   - description (TEXT)
   - image_after (VARCHAR) -> path screenshot hasil (wajib)
   - image_before (VARCHAR, nullable) -> path screenshot sebelumnya (opsional)
   - work_date (DATE)
   - timestamps

---

### 7. DESAIN & PRINSIP PENGEMBANGAN
- Filosofi: Ponytail (YAGNI, minim dependensi, tanpa approval workflow yang tidak dibutuhkan, kode bersih dan to-the-point).
- Desain Antarmuka: Bersih, teknis, modern, fungsional (palet netral gelap/slate dengan aksen biru/indigo yang jelas, kontras terbaca tinggi).
- Aksesibilitas: Gambar dapat di-zoom/di-preview dengan lightbox sederhana tanpa package pihak ketiga yang berat.
