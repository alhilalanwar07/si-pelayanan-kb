# BAB V PENUTUP

## 5.1 Kesimpulan

Berdasarkan seluruh tahapan penelitian yang telah dilaksanakan, mulai dari analisis kebutuhan lapangan, perancangan diagram arsitektur berbasis objek (UML), konstruksi kode program, hingga pengujian fungsionalitas sistem pada Dinas Pengendalian Penduduk dan Keluarga Berencana (DPPKB) Kecamatan Wundulako, maka dapat ditarik beberapa kesimpulan sebagai berikut:

1. **Keberhasilan Rancang Bangun Sistem Menggunakan Metode Prototype**: Telah berhasil dirancang dan dibangun Sistem Informasi Pelayanan Keluarga Berencana berbasis web pada DPPKB Kecamatan Wundulako. Penerapan metode Prototype terbukti sangat efektif dalam menjembatani kebutuhan pengguna akhir secara iteratif, di mana umpan balik dari staf operator kecamatan dan Bidan faskes dapat diintegrasikan langsung ke dalam purwarupa sistem sebelum diimplementasikan secara utuh menggunakan kerangka kerja Laravel 11, Livewire 3, Tailwind CSS, dan basis data MySQL.

2. **Efisiensi Pendaftaran Mandiri dan Manajemen Antrean Daring**: Sistem berhasil mendigitalisasi prosedur registrasi akseptor melalui portal publik yang memungkinkan masyarakat mendaftar mandiri dari rumah. Sistem secara otomatis memvalidasi 16 digit NIK, memilah akseptor baru dan lama, menghitung ketersediaan sisa kuota pelayanan faskes secara real-time, serta menerbitkan lembar tiket antrean resmi berformat PDF ber-barcode. Hal ini berhasil mengeliminasi penumpukan antrean fisik dan memberikan estimasi waktu layanan yang terukur bagi akseptor.

3. **Standarisasi Pelayanan Klinis Melalui Wizard 3 Langkah Terstruktur**: Modul pelayanan kontrasepsi bagi Bidan telah terstandarisasi melalui alur wizard 3 langkah yang aman dan terpadu: Langkah 1 anamnesa klinis dengan evaluasi otomatis risiko kontraindikasi komorbid; Langkah 2 pencatatan informed consent persetujuan tindakan medis akseptor dan pasangan; serta Langkah 3 pencatatan tindakan medis yang langsung terhubung dengan pemotongan saldo stok alokon fisik faskes dan penerbitan Kartu Status Peserta KB (K/IV/KB) digital.

4. **Pengendalian Persediaan Stok Alokon yang Akurat dan Terintegrasi**: Sistem informasi telah berhasil mengintegrasikan transaksi pemakaian alat kontrasepsi di fasilitas kesehatan dengan pencatatan inventaris pada kantor DPPKB Kecamatan Wundulako. Pemotongan saldo alokon berlangsung secara otomatis saat pelayanan diselesaikan, disertai fitur pencatatan stok masuk (restock) dan indikator peringatan dini stok minimum (safety stock), sehingga meminimalisasi risiko terjadinya kehabisan stok alokon secara mendadak.

5. **Transparansi Monitoring Eksekutif Berbasis GIS dan Pelaporan Cepat**: Pimpinan DPPKB kini dapat memantau indikator capaian pelayanan secara real-time melalui Dashboard KPI dan visualisasi spasial peta tematik Geographic Information System (GIS) persebaran akseptor per desa/kelurahan. Selain itu, fitur rekapitulasi laporan otomatis mampu menghasilkan dokumen laporan resmi berformat PDF A4 Landscape secara instan lengkap dengan lembar pengesahan, menggantikan proses rekapitulasi manual dari tumpukan buku register kertas yang sebelumnya memakan waktu berminggu-minggu.

6. **Validitas dan Kelayakan Fungsionalitas Sistem (100% Valid)**: Berdasarkan hasil pengujian fungsionalitas menggunakan metode Black Box Testing terhadap 56 (lima puluh enam) kasus uji pada 10 halaman antarmuka utama, seluruh skenario pengujian memperoleh kesimpulan Valid dengan tingkat kelulusan 100%. Tidak ditemukan kesalahan pada tombol navigasi, validasi input formulir, maupun logika transaksi basis data, sehingga sistem informasi ini dinyatakan layak, andal, dan siap dioperasikan secara penuh untuk mendukung operasional pelayanan KB di Kecamatan Wundulako.


---

## 5.2 Saran

Meskipun Sistem Informasi Pelayanan KB yang dikembangkan telah berhasil mencapai seluruh tujuan penelitian dan menyelesaikan kendala operasional yang ada, sistem ini masih memiliki peluang untuk disempurnakan lebih lanjut. Berdasarkan pengalaman dan evaluasi selama penelitian, penulis memberikan beberapa saran konstruktif sebagai berikut:

1. **Integrasi Notifikasi Pengingat Otomatis (WhatsApp Gateway API)**: Disarankan untuk mengembangkan integrasi sistem dengan Application Programming Interface (API) WhatsApp Gateway. Fitur ini dapat dimanfaatkan untuk mengirimkan pesan notifikasi pengingat (reminder) otomatis kepada akseptor beberapa hari menjelang jadwal suntik ulang atau jadwal kontrol pasca-pemasangan kontrasepsi, guna meningkatkan angka kepatuhan ber-KB dan menekan laju putus pakai kontrasepsi (drop-out rate).

2. **Penerapan Analisis Spasial Lanjutan dan Prediksi Kebutuhan Alokon**: Pada modul pemetaan GIS, disarankan untuk menambahkan fitur analisis data spasial lanjutan dengan mengadopsi algoritma Machine Learning atau Data Mining (seperti K-Means Clustering atau Regresi Linier). Algoritma ini dapat digunakan untuk memetakan kluster wilayah dengan tingkat Pasangan Usia Subur (PUS) belum ber-KB (unmet need) yang tinggi, sekaligus memprediksi estimasi kebutuhan stok alokon per desa untuk periode masa mendatang secara presisi.

3. **Pengembangan Aplikasi Bergerak Berkemampuan Luar Jaringan (Offline-First PWA)**: Mengingat sebagian wilayah geografis pelosok desa di Kecamatan Wundulako berpotensi mengalami kendala jaringan internet (blank spot), disarankan untuk mengembangkan aplikasi bergerak berbasis Progressive Web App (PWA) yang mendukung konsep offline-first. Dengan fitur ini, Petugas Lapangan KB (PLKB) tetap dapat menginput data akseptor di daerah terpencil tanpa koneksi internet, dan data akan otomatis tersinkronisasi ke server pusat saat perangkat kembali mendapatkan sinyal.

4. **Interoperabilitas dan Integrasi dengan Basis Data Nasional (SatuSehat / SIGA BKKBN)**: Ke depan, disarankan adanya pengembangan integrasi sistem pertukaran data secara terpusat melalui standarisasi API FHIR dengan platform SatuSehat Kementerian Kesehatan Republik Indonesia maupun Sistem Informasi Keluarga (SIGA) BKKBN Pusat, guna mewujudkan interoperabilitas data kependudukan dan kesehatan keluarga yang terpadu secara nasional.

5. **Pelatihan Berkelanjutan dan Pemeliharaan Infrastruktur bagi Instansi**: Bagi pihak DPPKB Kecamatan Wundulako dan fasilitas kesehatan jejaring, disarankan untuk menyelenggarakan pelatihan teknis berkala bagi petugas dan Bidan baru, melakukan pencadangan basis data (database backup) secara rutin dan terjadwal, serta memastikan ketersediaan sarana perangkat komputer dan koneksi internet yang stabil di setiap fasilitas kesehatan rujukan.
