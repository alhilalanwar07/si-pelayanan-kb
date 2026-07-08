# 🏥 SI Pelayanan KB — Kecamatan Wundulako

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-v4-blue.svg)](https://livewire.laravel.com)
[![Flux UI](https://img.shields.io/badge/Flux%20UI-Premium-purple.svg)](https://fluxui.dev)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-v3-38bdf8.svg)](https://tailwindcss.com)

**SI Pelayanan KB** (Sistem Informasi Pelayanan Keluarga Berencana) adalah aplikasi berbasis web yang dirancang khusus untuk memodernisasi, mencatat, dan mengelola seluruh siklus pelayanan Keluarga Berencana di wilayah Kecamatan Wundulako. Sistem ini menggantikan proses pencatatan manual berbasis kertas dengan antarmuka digital yang aman, cepat, dan terintegrasi.

---

## 🌟 Fitur Utama (Core Features)

### 1. Dashboard Hybrid Dinamis
*   **Ringkasan Statistik**: Menampilkan metrik utama seperti total peserta KB aktif, total pelayanan bulan berjalan, antrean pendaftaran baru, dan penanda stok kritis.
*   **Visualisasi Grafik**: Grafik batang interaktif untuk memonitor tren pelayanan bulanan.
*   **Distribusi Wilayah**: Peringkat keaktifan peserta di tiap kelurahan/desa secara real-time.
*   **Stok Tracker**: Progress bar indikator untuk memantau sisa stok Alat/Obat Kontrasepsi (Alokon) secara instan.

### 2. Form Wizard Pelayanan KB (Bidan Interface)
*   **Alur 3-Langkah Medis**: Form pendaftaran pelayanan KB yang disusun terstruktur:
    1.  **Langkah 1 (Skrining Medis)**: Memilih peserta terdaftar, melengkapi profil sosial (Pendidikan, Pekerjaan, data anak), mencatat riwayat penyakit, serta melakukan pemeriksaan fisik dan internal.
        *   *Gate Logic*: Jika terdapat indikasi medis risiko tinggi (contoh: riwayat tumor, pendarahan abnormal, penyakit kuning, atau fisik lemah), sistem secara otomatis memblokir akses ke langkah berikutnya.
    2.  **Langkah 2 (Informed Consent)**: Draf penandatanganan persetujuan tindakan medis secara digital untuk klien dan pasangan resmi.
    3.  **Langkah 3 (Pemberian Alokon)**: Memasukkan jenis tindakan dan alokon yang diberikan. Sistem secara otomatis memotong stok alokon di gudang inventaris faskes.

### 3. Replikasi Formulir Fisik ke Cetak PDF A4
Menyediakan fitur **Cetak Formulir (PDF)** pada halaman detail pelayanan yang menghasilkan salinan digital dengan tata letak **100% persis** dengan kertas formulir resmi:
*   **Halaman 1**: Kartu Status Peserta KB (menyusun kotak NIK, riwayat skrining medis lengkap, dan tanda tangan bidan pemeriksa).
*   **Halaman 2**: Lembar Persetujuan Tindakan Medik / Informed Consent (pernyataan persetujuan klien, pasangan, saksi, dan bidan).
*   *Print-friendly*: Menggunakan CSS print media query sehingga saat dicetak (`Ctrl+P` atau tombol Simpan PDF), dokumen otomatis terbagi menjadi 2 halaman kertas terpisah dengan rapi.

### 4. Integrasi WhatsApp Pesan Jadwal Kehadiran
*   Bagi masyarakat yang melakukan registrasi mandiri, admin dapat memverifikasi akun di dashboard.
*   Begitu diverifikasi, sistem memunculkan tombol **Kirim Jadwal (WA)** yang terhubung langsung ke WhatsApp peserta dengan draf pesan otomatis berisi jadwal kontrol/kehadiran.

### 5. Peta Sebaran Kepadatan SVG
*   Peta wilayah interaktif berbasis file SVG untuk Kecamatan Wundulako.
*   Mengimplementasikan konsep **Choropleth Map** di mana gradasi warna kelurahan/desa akan otomatis berubah warna lebih pekat seiring tingginya jumlah kepesertaan aktif di wilayah tersebut.
*   Hover tooltip interaktif untuk menampilkan statistik peserta per kelurahan/desa secara langsung.

### 6. Laporan Rekapitulasi & Ekspor CSV
*   Laporan terbagi dalam 3 tab (Rekap Pelayanan, Data Peserta, Inventaris Alokon).
*   Penyaringan (filtering) berdasarkan wilayah, tanggal pelayanan, dan jenis kontrasepsi.
*   Ekspor data cepat ke format file CSV untuk diolah lebih lanjut.

### 7. Registrasi Mandiri Publik
*   Portal pendaftaran terbuka di `/registrasi` bagi calon peserta untuk mendaftarkan diri secara mandiri dari rumah menggunakan smartphone mereka dengan form yang responsif di segala ukuran device.

---

## 🛠️ Stack Teknologi (Tech Stack)

*   **Framework Utama**: Laravel PHP Framework (v11+)
*   **Reaktivitas Frontend**: Livewire v4 (Single Page Application feel dengan `wire:navigate`)
*   **Sistem Desain & Komponen**: Flux UI Premium
*   **Styling**: Tailwind CSS
*   **Peta Wilayah**: Inline Responsive SVG Vector Map
*   **Database**: MySQL / MariaDB

---

## 📁 Struktur Skema Database (Database Schema)

Sistem menggunakan 8 tabel utama yang saling berelasi:
1.  `users`: Data autentikasi staf instansi faskes (admin, bidan, pimpinan).
2.  `instansis`: Data instansi kesehatan/faskes tempat petugas bernaung.
3.  `wilayahs`: Data desa/kelurahan di Kecamatan Wundulako.
4.  `alokons`: Persediaan alat obat kontrasepsi beserta stok dinamis per instansi.
5.  `peserta_kbs`: Data lengkap profil klinis, pendidikan, pekerjaan, dan kontak WhatsApp peserta.
6.  `skrining_medis`: Hasil rekam medis anamnese dan pemeriksaan fisik awal peserta.
7.  `informed_consents`: Surat bukti persetujuan tindakan medis KB dari pasien dan pasangan.
8.  `pelayanans`: Catatan riwayat tindakan pelayanan KB, tanggal kunjungan ulang, dan penanggung jawab medis.

---

## 🔑 Akun Uji Coba Default (Credentials)

Seluruh akun simulasi di bawah ini menggunakan password: **`password`**

| Username | Role | Nama Pengguna | Tugas & Kewenangan Utama |
|---|---|---|---|
| `admin` | Admin / Operator | Admin Instansi | Memverifikasi akun mandiri, mengelola master data wilayah, alokon, dan user |
| `bidan` | Bidan / Petugas Medis | Bidan Pelaksana | Memasukkan rekam medis skrining medis, consent, & memberikan pelayanan KB |
| `pimpinan` | Pimpinan DPPKB | Pimpinan DPPKB | Memantau dashboard, menganalisis peta sebaran, & mencetak laporan rekapitulasi |

---

## 🚀 Panduan Instalasi (Installation Guide)

Ikuti langkah-langkah berikut untuk menjalankan project ini di lingkungan lokal Anda:

### 1. Clone Project
```bash
git clone https://github.com/alhilalanwar07/si-pelayanan-kb.git
cd si-pelayanan-kb
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Install Dependensi NPM & Aset Frontend
```bash
npm install
npm run build
```

### 4. Konfigurasi Environment File
Salin file `.env.example` menjadi `.env` dan sesuaikan pengaturan database Anda:
```bash
cp .env.example .env
```
*Pastikan parameter database pada `.env` sudah sesuai (contoh menggunakan MySQL):*
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=si_pelayanan_kb
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi & Database Seeder
Perintah ini akan membuat struktur tabel baru sekaligus mengisi data sampel awal (termasuk user uji coba di atas):
```bash
php artisan migrate:fresh --seed
```

### 7. Jalankan Local Server
Jalankan server Laravel untuk mulai mengakses aplikasi:
```bash
php artisan serve
```
Aplikasi sekarang dapat diakses secara lokal melalui browser di alamat [http://localhost:8000](http://localhost:8000).

---
*Dikembangkan dengan penuh dedikasi untuk efisiensi pelayanan kesehatan reproduksi Indonesia.*
