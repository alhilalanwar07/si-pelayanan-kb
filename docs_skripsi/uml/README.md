# 📐 Dokumentasi Lengkap Diagram UML & Skripsi — SI Pelayanan KB
**Dinas Pengendalian Penduduk dan Keluarga Berencana (DPPKB) Kecamatan Wundulako**

Folder ini memuat seluruh diagram perancangan sistem (*software engineering design*) dalam format **Draw.io (`.drawio`)** serta draf bab/sub-bab skripsi siap pakai dalam format **Microsoft Word (`.docx`)** yang disesuaikan dengan standar penulisan karya ilmiah akademik (Font *Times New Roman* 12pt, Spasi 1.5, Margin 4-3-3-3 cm).

---

## 📂 Struktur Berkas Diagram & Skripsi (Lengkap)

| # | Modul Diagram | Berkas Draw.io (`.drawio`) | Berkas Skripsi (`.docx`) | Deskripsi & Ruang Lingkup |
|---|---------------|-----------------------------|--------------------------|---------------------------|
| 1 | **Use Case Diagram** | [`01_use_case_diagram.drawio`](01_use_case_diagram.drawio) | - *(sudah di bab perancangan)* | 15 Use Case + 4 Aktor (Masyarakat, Admin, Bidan, Pimpinan). |
| 2 | **Entity Relationship Diagram (ERD)** | [`02_erd.drawio`](02_erd.drawio) | [`02_erd.docx`](02_erd.docx) | 10 Entitas database, penanda PK/FK/UQ, kardinalitas Crow's foot (1:1, 1:N), dan 5 aturan bisnis relasional (*business rules*). |
| 3 | **Class Diagram** | [`03_class_diagram.drawio`](03_class_diagram.drawio) | [`03_class_diagram.docx`](03_class_diagram.docx) | 10 Model Eloquent Laravel (`«Authenticatable»` & `«Model»`), visibilitas (`+`, `#`, `-`), atribut tipe data, method relasi, scopes, dan helper. |
| 4 | **Activity Diagram: Registrasi Mandiri** | [`04_activity_registrasi_mandiri.drawio`](04_activity_registrasi_mandiri.drawio) | [`04_activity_registrasi_mandiri.docx`](04_activity_registrasi_mandiri.docx) | Swimlane (Masyarakat vs Sistem): Cek NIK (lama vs baru), pemilihan jadwal, validasi kuota, pembuatan nomor antrean urut, dan unduh PDF tiket antrean (A5). |
| 5 | **Activity Diagram: Pelayanan KB Wizard** | [`05_activity_pelayanan_wizard.drawio`](05_activity_pelayanan_wizard.drawio) | [`05_activity_pelayanan_wizard.docx`](05_activity_pelayanan_wizard.docx) | Swimlane (Bidan vs Sistem): Wizard 3 langkah (Skrining kelayakan klinis → Informed Consent ganda → Tindakan, potong stok otomatis, update antrean, & opsi cetak K/IV/KB). |
| 6 | **Activity Diagram: Login Sistem** | [`06_activity_login.drawio`](06_activity_login.drawio) | [`06_activity_login.docx`](06_activity_login.docx) | Swimlane (Pengguna vs Sistem): Autentikasi Fortify, verifikasi hash bcrypt, rate limiting / throttling, regenerasi session (anti-fixation), dan pembagian peran (admin, bidan, pimpinan). |
| 7 | **Activity Diagram: Pengelolaan Jadwal** | [`07_activity_kelola_jadwal.drawio`](07_activity_kelola_jadwal.drawio) | [`07_activity_kelola_jadwal.docx`](07_activity_kelola_jadwal.docx) | Swimlane (Admin vs Sistem): Pengaturan kalender operasional, penentuan kuota harian, rentang jam pelayanan, serta aktivasi status agenda. |
| 8 | **Activity Diagram: Laporan & Monitoring GIS** | [`08_activity_laporan_gis.drawio`](08_activity_laporan_gis.drawio) | [`08_activity_laporan_gis.docx`](08_activity_laporan_gis.docx) | Swimlane (Pimpinan/Admin vs Sistem): Filter data periode/desa, rekapitulasi data, cetak laporan PDF resmi A4 Landscape dengan lembar pengesahan, serta analisis spasial Leaflet GIS. |
| 9 | **Activity Diagram: Manajemen Stok Alokon** | [`09_activity_kelola_alokon.drawio`](09_activity_kelola_alokon.drawio) | [`09_activity_kelola_alokon.docx`](09_activity_kelola_alokon.docx) | Swimlane (Admin vs Sistem): Pencatatan komoditas alokon baru, penyesuaian/restock saldo kuantitas fisik, dan peringatan dini stok menipis (< 10 unit). |

---

## 🛠️ Panduan Penggunaan untuk Skripsi

### 1. Membuka & Mengedit File `.drawio`
Anda dapat membuka dan mengedit diagram dengan salah satu cara berikut:
- **Draw.io Desktop**: Download aplikasi gratis di [jgraph/drawio-desktop](https://github.com/jgraph/drawio-desktop/releases), lalu buka file `.drawio`.
- **Draw.io Online (Browser)**: Buka [app.diagrams.net](https://app.diagrams.net), pilih **File → Open from → Device**, lalu pilih file yang diinginkan.
- **VS Code Extension**: Install ekstensi **Draw.io Integration** (*hediet.vscode-drawio*) di VS Code / Antigravity IDE.

### 2. Cara Ekspor Gambar untuk Skripsi
1. Buka diagram di draw.io.
2. Klik menu **File → Export as → PNG**.
3. Pada opsi ekspor:
   - Centang **Transparent Background** (atau biarkan putih).
   - Atur **Border Width**: `10` atau `20`.
   - Atur **Zoom / DPI**: `200%` atau `300 DPI` agar gambar tajam dan tidak pecah saat dicetak.
4. Klik **Export** dan simpan file gambar PNG.

### 3. Memasukkan Gambar ke Dokumen Word (`.docx`)
Setiap berkas `.docx` telah diformat sesuai standar skripsi (Font *Times New Roman* 12pt, Spasi 1.5, Margin 4-3-3-3 cm) dan memiliki **kotak penanda**:
> `[TEMPAT GAMBAR ...]`  
> *Petunjuk: Buka file ... di aplikasi draw.io, ekspor sebagai PNG, lalu sisipkan di sini.*

Tinggal hapus teks di dalam kotak tersebut, lalu klik menu **Insert → Pictures** di Microsoft Word dan pilih gambar PNG hasil ekspor Anda.

---

## 👥 Aktor Sistem Informasi Pelayanan KB

| No | Aktor | Deskripsi Peran |
|----|-------|-----------------|
| 1 | **Masyarakat / Calon Akseptor** | Pengguna publik yang mengakses beranda, melakukan registrasi mandiri, booking nomor antrean, dan mengunduh tiket antrean PDF tanpa login. |
| 2 | **Admin / Operator Kecamatan** | Pengguna internal dengan hak akses penuh (*superadmin*) untuk mengelola master wilayah desa/kelurahan, kalender jadwal pelayanan, akun petugas, inventaris stok alokon, dan verifikasi data akseptor. |
| 3 | **Bidan / Petugas Medis** | Tenaga kesehatan yang bertugas mengeksekusi wizard 3 langkah pelayanan kontrasepsi: anamnesis & skrining kelayakan klinis, penandatanganan informed consent ganda, pencatatan tindakan medis, serta pencetakan Kartu Pelayanan KB (K/IV/KB). |
| 4 | **Pimpinan DPPKB** | Pejabat pengambil keputusan yang memiliki akses monitoring eksekutif: dashboard statistik pelayanan, pemetaan spasial sebaran akseptor (GIS), dan rekapitulasi laporan berkala. |
