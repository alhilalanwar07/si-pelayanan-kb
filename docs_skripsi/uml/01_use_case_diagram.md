# 4.2.1 Use Case Diagram

Use Case Diagram merupakan pemodelan visual dari Unified Modeling Language (UML) yang digunakan untuk memetakan kebutuhan fungsional sistem, menggambarkan batasan ruang lingkup aplikasi (*system boundary*), serta memperlihatkan interaksi antara aktor eksternal dengan layanan-layanan yang disediakan oleh sistem informasi. Pemodelan ini berorientasi pada sudut pandang pengguna (*user-centric*), sehingga mempermudah pemahaman mengenai siapa yang berhak menggunakan sistem dan apa saja fungsi utama yang dapat mereka lakukan.

Pada Sistem Informasi Pelayanan Keluarga Berencana (KB) DPPKB Kecamatan Wundulako, Use Case Diagram dirancang untuk mencakup seluruh siklus operasional pelayanan, mulai dari penyebaran informasi dan pendaftaran antrean daring secara mandiri oleh masyarakat, pelaksanaan rekam medis dan pelayanan klinis kontrasepsi oleh Bidan, hingga pengelolaan data master, kalender jadwal, stok alat kontrasepsi (alokon), dan pemantauan statistik eksekutif melalui Geographic Information System (GIS) oleh Administrator dan Pimpinan. Batasan sistem informasi dipisahkan secara tegas antara area publik tanpa autentikasi dengan area internal yang terproteksi kredensial login.

Visualisasi hubungan interaksi antara para aktor dengan seluruh use case yang ada di dalam batasan Sistem Informasi Pelayanan KB disajikan pada Gambar 4.x berikut:

> **📷 [TEMPAT GAMBAR USE CASE DIAGRAM]**  
> *Petunjuk: Buka file `01_use_case_diagram.drawio` di draw.io, ekspor sebagai PNG, lalu sisipkan pada area ini.*

**Gambar 4.x** Use Case Diagram Sistem Informasi Pelayanan KB DPPKB Kecamatan Wundulako

---

## 1. Identifikasi dan Deskripsi Aktor

Aktor adalah entitas eksternal (manusia atau peranan tertentu) yang berinteraksi langsung dengan sistem informasi. Berdasarkan perancangan arsitektur sistem, terdapat 4 (empat) aktor utama:

| No | Nama Aktor | Peran / Hak Akses | Deskripsi Tanggung Jawab & Interaksi |
|:---:|---|---|---|
| 1 | **Masyarakat / Calon Akseptor** | Pengguna Publik (Tanpa Autentikasi) | Mengakses portal publik, mengecek NIK, melakukan registrasi mandiri, memilih jadwal kunjungan faskes, dan mengunduh tiket antrean PDF. |
| 2 | **Admin / Operator Kecamatan** | Superadministrator (Hak Akses Penuh) | Mengelola master wilayah, jadwal pelayanan, akun petugas, data kepesertaan, stok alokon, dan rekapitulasi laporan. |
| 3 | **Bidan / Petugas Medis** | Tenaga Kesehatan (Fasilitas Kesehatan) | Memanggil antrean, mengeksekusi wizard 3 langkah pelayanan (skrining, informed consent, tindakan/alokon), pemotongan stok otomatis, dan cetak kartu K/IV/KB. |
| 4 | **Pimpinan DPPKB** | Pejabat Eksekutif (Monitoring & Evaluasi) | Monitoring statistik eksekutif, analisis peta sebaran geografis GIS, serta validasi laporan berkala. |

---

## 2. Identifikasi dan Deskripsi Use Case

| No | Kode | Nama Use Case | Aktor Terlibat | Deskripsi Fungsionalitas |
|:---:|:---:|---|---|---|
| 1 | **UC01** | Melihat Informasi Beranda | Masyarakat | Mengakses landing page informasi KB, metode kontrasepsi, dan jadwal faskes. |
| 2 | **UC02** | Melakukan Registrasi Mandiri | Masyarakat | Mendaftar antrean pelayanan mandiri via online dengan validasi NIK. |
| 3 | **UC03** | Memilih Jadwal & Booking Antrean | Masyarakat | Memilih tanggal dan sesi jadwal layanan aktif sesuai sisa kuota. |
| 4 | **UC04** | Mengunduh Tiket Antrean (PDF) | Masyarakat | Mengunduh lembaran tiket antrean resmi berformat PDF ber-barcode. |
| 5 | **UC05** | Login Sistem | Admin, Bidan, Pimpinan | Otentikasi kredensial pengguna internal menuju dashboard terproteksi. |
| 6 | **UC06** | Mengelola Data Wilayah | Admin | Pengelolaan data desa/kelurahan, kode pos, dan titik koordinat geografis. |
| 7 | **UC07** | Mengelola Jadwal Pelayanan | Admin | Pengaturan kuota harian, jam buka faskes, dan status aktif agenda pelayanan. |
| 8 | **UC08** | Mengelola Data Peserta KB | Admin, Bidan | Pengelolaan data induk akseptor, status kesertaan, dan riwayat kunjungan. |
| 9 | **UC09** | Mengelola Inventaris Alokon | Admin | Manajemen stok masuk (restock), mutasi komoditas kontrasepsi, dan safety stock. |
| 10 | **UC10** | Mengelola Akun Pengguna | Admin | Pengelolaan akun petugas, penugasan faskes, level akses, dan reset sandi. |
| 11 | **UC11** | Melakukan Pelayanan KB | Bidan | Eksekusi wizard 3 langkah tindakan klinis terhadap pasien yang hadir. |
| 12 | **UC11a** | Mengisi Skrining Medis | Bidan | Anamnesis tensi darah dan evaluasi risiko kontraindikasi komorbid. |
| 13 | **UC11b** | Mengisi Informed Consent | Bidan | Verifikasi persetujuan tindakan medis akseptor dan persetujuan pasangan. |
| 14 | **UC11c** | Mencatat Tindakan & Alokon | Bidan | Pencatatan tindakan medis, pemotongan otomatis stok fisik alokon, dan tgl kontrol. |
| 15 | **UC12** | Mencetak Kartu Pelayanan KB | Bidan | Mencetak Kartu Status Peserta KB (K/IV/KB) sebagai bukti rekam medis fisik. |
| 16 | **UC13** | Melihat Dashboard Statistik | Admin, Bidan, Pimpinan | Analisis visual tren peserta baru/ulang, distribusi metode KB, dan stok alokon. |
| 17 | **UC14** | Melihat Peta Sebaran Akseptor | Admin, Bidan, Pimpinan | Visualisasi peta tematik GIS spasial densitas akseptor per desa/kelurahan. |
| 18 | **UC15** | Mencetak Laporan Pelayanan | Admin, Bidan, Pimpinan | Ekspor rekapitulasi data akseptor dan tindakan ke format PDF / Excel. |

---

## 3. Karakteristik dan Relasi Ketergantungan (Include)

- **UC02 include UC03 dan UC04**: Registrasi mandiri mewajibkan pemilihan jadwal faskes aktif dan menghasilkan bukti tiket fisik antrean.

- **UC11 include UC11a, UC11b, dan UC11c**: Pelayanan kontrasepsi mewajibkan alur 3 langkah berurutan: skrining kelayakan medis -> persetujuan informed consent -> eksekusi tindakan dan pemotongan stok alokon.
