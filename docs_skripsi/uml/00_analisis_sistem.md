# 4.1 Analisis Sistem

Analisis sistem merupakan tahapan awal yang sangat mendasar dalam siklus rekayasa perangkat lunak. Tahapan ini bertujuan untuk menguraikan sistem operasional yang sedang berjalan secara menyeluruh ke dalam komponen-komponen pembentuknya, mengidentifikasi dan mengevaluasi kelemahan maupun permasalahan yang dihadapi pengguna, serta mendefinisikan spesifikasi kebutuhan sistem baru yang tepat untuk mengatasi kendala-kendala tersebut. Melalui analisis sistem yang komprehensif, Sistem Informasi Pelayanan Keluarga Berencana (KB) pada DPPKB Kecamatan Wundulako dapat dirancang secara terarah, berdaya guna, dan relevan dengan kondisi operasional di lapangan.

## 4.1.1 Analisis Sistem yang Sedang Berjalan (Current System / As-Is)

Berdasarkan hasil observasi langsung dan wawancara dengan staf operasional serta Penyuluh Lapangan Keluarga Berencana (PLKB) di Kantor DPPKB Kecamatan Wundulako, proses pelayanan Keluarga Berencana saat ini masih diselenggarakan secara konvensional menggunakan media fisik (kertas dan buku register). Prosedur operasional yang sedang berjalan mencakup 4 tahapan:

1. **Prosedur Pendaftaran dan Antrean Akseptor**: Warga harus datang langsung ke faskes/puskesmas tanpa kepastian kuota, menulis buku tamu kertas, dan menunggu berjam-jam.

2. **Prosedur Anamnesa Klinis & Informed Consent**: Bidan mencatat anamnesa di kartu rekam medis manual dan lembar persetujuan fisik yang rentan rusak/hilang.

3. **Prosedur Pengelolaan Stok Alokon**: Pengecekan fisik lemari faskes secara manual tanpa peringatan stok minimum.

4. **Prosedur Rekapitulasi & Pelaporan Bulanan**: Berkas fisik dari desa dikumpulkan akhir bulan, lalu operator mengetik ulang data manual ke spreadsheet (butuh 1-2 minggu).

> **📷 [TEMPAT GAMBAR FLOWMAP SISTEM BERJALAN]**  
> *Petunjuk: Buka file `00_flowmap_sistem_berjalan.drawio` di draw.io, ekspor sebagai PNG, lalu sisipkan pada area ini.*

**Gambar 4.1** Flowmap Alur Pelayanan KB Konvensional (Sistem Berjalan)

### Evaluasi Kelemahan Sistem Berjalan (Metode PIECES)

| No | Dimensi PIECES | Kondisi dan Kelemahan pada Sistem Berjalan |
|:---:|---|---|
| 1 | **Performance** | Waktu tunggu antrean tinggi karena pencatatan manual buku tamu. |
| 2 | **Information** | Informasi kuota dan stok tidak transparan, laporan bulanan sering terlambat. |
| 3 | **Economy** | Pemborosan biaya kertas cetak buku register dan formulir fisik. |
| 4 | **Control** | Tidak ada otentikasi digital, rentan manipulasi data dan arsip hilang. |
| 5 | **Efficiency** | Terjadi duplikasi input kerja (catat di faskes lalu diketik ulang di kantor kecamatan). |
| 6 | **Service** | Akseptor kecewa jika kuota habis setelah mengantre lama. |

---

## 4.1.2 Analisis Sistem yang Diusulkan (Proposed System / To-Be)

Sistem baru yang diusulkan mengintegrasikan seluruh proses bisnis ke dalam aplikasi berbasis web:

- **Portal Registrasi Mandiri**: Pendaftaran online, cek NIK otomatis, kuota realtime, tiket PDF ber-barcode.

- **Alur Klinis Wizard 3 Langkah Bidan**: Skrining otomatis kontraindikasi, informed consent digital, pemotongan stok alokon otomatis, cetak kartu K/IV/KB.

- **Pengendalian Stok Alokon Terintegrasi**: Mutasi stok realtime dan indikator safety stock.

- **Monitoring GIS & Pelaporan Otomatis**: Peta sebaran spasial akseptor desa (Leaflet GIS) dan unduh laporan PDF resmi instan.

> **📷 [TEMPAT GAMBAR FLOWMAP SISTEM DIUSULKAN]**  
> *Petunjuk: Buka file `00_flowmap_sistem_diusulkan.drawio` di draw.io, ekspor sebagai PNG, lalu sisipkan pada area ini.*

**Gambar 4.2** Flowmap Alur Pelayanan KB Terintegrasi Berbasis Web (Sistem Diusulkan)

### Tabel Perbandingan Sistem Berjalan vs Diusulkan

| Parameter / Fitur | Sistem Berjalan (Konvensional) | Sistem yang Diusulkan (SI Pelayanan KB) |
|---|---|---|
| **Media Pendaftaran** | Formulir kertas & buku register saat hadir di faskes | Daring (online) mandiri melalui peramban web |
| **Informasi Kuota** | Tidak pasti, pasien harus datang langsung | Transparan dan terhitung real-time per sesi jadwal |
| **Bukti Antrean** | Kertas manual tanpa identitas terstruktur | Tiket PDF resmi ber-barcode dengan jam layanan pasti |
| **Rekam Medis** | Kertas fisik rentan tercecer | Database digital terpusat |
| **Skrining Medis** | Manual tanpa validasi | Algoritma evaluasi risiko kontraindikasi otomatis |
| **Stok Alokon** | Pengecekan manual lemari faskes | Pemotongan saldo otomatis saat tindakan selesai |
| **Pelaporan** | Kumpul buku register dan entri ulang manual (1-2 minggu) | Generate otomatis berkas PDF resmi (instan) |
| **Monitoring Pimpinan** | Rekapitulasi berkas fisik sering terlambat | Real-time via Dashboard KPI & Peta Spasial GIS |

---

## 4.1.3 Analisis Kebutuhan Sistem (System Requirements)

Mencakup 15 Kebutuhan Fungsional (SKF-01 s.d. SKF-15) dan 5 Kebutuhan Non-Fungsional (SKNF-01 s.d. SKNF-05).
