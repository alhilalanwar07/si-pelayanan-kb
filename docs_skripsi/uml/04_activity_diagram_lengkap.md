# 4.4 Activity Diagram Lengkap (Seluruh Alur Sistem)

*Activity Diagram* memodelkan alur kerja (*workflow*) fungsional sistem, urutan aktivitas bisnis, titik percabangan keputusan (*decision*), dan interaksi antara pengguna dengan sistem informasi. Diagram ini menggunakan pembagian jalur kerja (*swimlane*) untuk memperjelas batas tanggung jawab masing-masing entitas.

Sub-bab ini menghimpun seluruh 6 (enam) diagram aktivitas utama pada Sistem Informasi Pelayanan KB DPPKB Kecamatan Wundulako:

1. **4.4.1 Activity Diagram: Registrasi Mandiri dan Pemesanan Antrean**
2. **4.4.2 Activity Diagram: Pelayanan KB Wizard 3 Langkah**
3. **4.4.3 Activity Diagram: Autentikasi Login dan Hak Akses Pengguna**
4. **4.4.4 Activity Diagram: Pengelolaan Jadwal Pelayanan**
5. **4.4.5 Activity Diagram: Cetak Laporan dan Monitoring Spasial GIS**
6. **4.4.6 Activity Diagram: Pengelolaan Inventaris Alokon**

---

Activity Diagram memodelkan alur kerja (*workflow*) fungsional sistem, urutan aktivitas bisnis, titik percabangan keputusan (*decision*), dan interaksi antara pengguna dengan sistem informasi. Diagram ini menggunakan pembagian jalur kerja (*swimlane*) untuk memperjelas batas tanggung jawab masing-masing entitas.


## 4.4.1 Activity Diagram: Registrasi Mandiri dan Pemesanan Antrean

Proses registrasi mandiri merupakan gerbang utama bagi masyarakat/calon akseptor untuk mendaftarkan diri secara online tanpa perlu datang dan mengantre secara manual di fasilitas kesehatan. Aktivitas ini mencakup pengecekan NIK, pemilahan akseptor lama vs baru, pemilihan sesi jadwal pelayanan yang aktif, validasi kuota harian, hingga penerbitan tiket antrean resmi dalam format digital (PDF).

Visualisasi alur aktivitas registrasi mandiri dan booking antrean disajikan pada Gambar 4.x berikut:


> **📷 [TEMPAT GAMBAR: Gambar 4.x Activity Diagram Registrasi Mandiri dan Pemesanan Antrean KB]**

**Gambar 4.x Activity Diagram Registrasi Mandiri dan Pemesanan Antrean KB**

Penjelasan rinci setiap tahapan alur aktivitas pendaftaran mandiri diuraikan pada Tabel 4.x berikut:


| No | Aktor Pelaksana | Aktivitas / Langkah | Deskripsi Rinci |
|:---:|:---:|---|---|
| 1 | Masyarakat | Mengakses Halaman Registrasi | Calon akseptor membuka menu pendaftaran mandiri pada situs web SI Pelayanan KB. |
| 2 | Sistem | Menampilkan Form Cek NIK | Sistem merender antarmuka verifikasi awal dengan kolom input Nomor Induk Kependudukan (NIK). |
| 3 | Masyarakat | Input NIK & Klik 'Cek NIK' | Memasukkan 16 digit NIK istri dan menekan tombol validasi. |
| 4 | Sistem | Validasi Format & Cek Database | Memvalidasi kelayakan NIK (16 digit angka) dan melakukan query ke tabel peserta_kbs. |
| 5 | Sistem | Percabangan NIK Terdaftar? | Jika NIK ditemukan (akseptor lama), sistem memuat data biodata dan menampilkan jadwal pelayanan aktif. Jika tidak ditemukan (akseptor baru), sistem membuka form pendaftaran lengkap. |
| 6a | Masyarakat | Pilih Jadwal (Akseptor Lama) | Melihat riwayat pelayanan terdahulu dan memilih salah satu jadwal pelayanan aktif mendatang. |
| 6b | Masyarakat | Isi Form & Pilih Jadwal (Baru) | Mengisi data diri (nama, suami, tgl lahir, alamat, wilayah desa/kelurahan, asuransi, anak) dan memilih jadwal pelayanan aktif. |
| 7 | Masyarakat | Klik 'Daftar / Booking Antrean' | Menekan tombol konfirmasi final pendaftaran. |
| 8 | Sistem | Cek Kuota Jadwal (isFull) | Memeriksa sisa kuota pada jadwal yang dipilih. Jika kuota telah penuh, tampil pesan error dan meminta memilih tanggal lain. |
| 9 | Sistem | Eksekusi Transaksi Database | Menjalankan DB::transaction: menyimpan/memperbarui data peserta KB, menghitung nomor antrean berikutnya (max + 1), dan mencatat transaksi antrean di antrian_jadwals. |
| 10 | Sistem | Tampilkan Halaman Sukses | Commit transaksi basis data dan merender halaman konfirmasi sukses beserta rincian tiket antrean. |
| 11 | Masyarakat | Klik 'Unduh Tiket PDF' | Menekan tombol untuk mengunduh bukti antrean fisik. |
| 12 | Sistem | Generate File PDF (DomPDF) | Merender view tiket-antrian ke format PDF ukuran A5 dan mengirimkan berkas unduhan ke browser. |
| 13 | Masyarakat | Menyimpan Berkas PDF | Calon akseptor menyimpan/mencetak tiket untuk dibawa saat hadir di fasilitas kesehatan. |



---


## 4.4.2 Activity Diagram: Pelayanan KB Wizard 3 Langkah

Modul Pelayanan KB merupakan inti operasional (*core process*) medis pada sistem informasi ini. Untuk meminimalkan kelalaian prosedur klinis dan menjamin kepatuhan standar pelayanan kebidanan, alur tindakan dirancang dalam bentuk wizard 3 (tiga) tahapan sekuensial: (1) Pemeriksaan Skrining Medis dan Kelayakan Klinis, (2) Pengisian Lembar Persetujuan Tindakan Medis (*Informed Consent*), serta (3) Pencatatan Tindakan Pelayanan, Pemilihan Alokon, Pemotongan Saldo Logistik Otomatis, dan Penjadwalan Kontrol Ulang.

Visualisasi alur aktivitas pelayanan KB wizard 3 langkah disajikan pada Gambar 4.x berikut:


> **📷 [TEMPAT GAMBAR: Gambar 4.x Activity Diagram Pelayanan KB Wizard 3 Langkah]**

**Gambar 4.x Activity Diagram Pelayanan KB Wizard 3 Langkah**

Rincian narasi setiap langkah aktivitas pelayanan medis kebidanan diuraikan pada Tabel 4.x berikut:


| No | Aktor Pelaksana | Aktivitas / Langkah | Deskripsi Rinci |
|:---:|:---:|---|---|
| 1 | Bidan | Membuka Form Pelayanan | Bidan mengakses form pelayanan KB baru dan memilih akseptor yang akan dilayani (dapat dipilih langsung dari daftar antrean harian). |
| 2 | Sistem | Inisialisasi Data & Step 1 | Sistem memuat biodata demografi peserta KB, riwayat KB terakhir, dan menyiapkan form Langkah 1 (Skrining Medis). |
| 3 | Bidan | Pengisian Skrining Medis | Bidan memeriksa dan menginput anamnesis (HPHT, GPA, menyusui, tanda hamil), riwayat penyakit komorbid (kuning, pendarahan abnormal, keputihan, tumor), pemeriksaan fisik (tensi, BB, keadaan umum), serta pemeriksaan panggul dalam. |
| 4 | Bidan | Klik 'Lanjut ke Langkah 2' | Bidan mengirimkan data skrining medis untuk divalidasi oleh sistem. |
| 5 | Sistem | Evaluasi Kelayakan Klinis | Sistem mengeksekusi logika klinis checkKelayakanMedis(). Jika terdeteksi kontraindikasi (misal riwayat tumor, pendarahan rahim, hepatitis, atau kondisi fisik lemah), sistem memunculkan peringatan medis dan menghentikan proses pelayanan demi keselamatan pasien. |
| 6 | Sistem | Membuka Form Step 2 (Consent) | Jika pasien dinyatakan layak secara medis, sistem membuka formulir Langkah 2 (Informed Consent). |
| 7 | Bidan | Input Persetujuan Medis | Bidan mengonfirmasi persetujuan tindakan dari pihak akseptor (istri) dan pasangan (suami) serta menentukan jenis tindakan medis yang disepakati. |
| 8 | Sistem | Validasi Kelengkapan Consent | Sistem memvalidasi bahwa kedua pihak (klien dan pasangan) telah menyatakan persetujuan secara lengkap. Jika belum lengkap, langkah berikutnya ditolak. |
| 9 | Sistem | Membuka Form Step 3 (Tindakan) | Sistem membuka Langkah 3 untuk pencatatan tindakan medis kontrasepsi. |
| 10 | Bidan | Pencatatan Tindakan & Alokon | Bidan memilih komoditas alokon yang diberikan, mencatat tanggal tindakan, estimasi tanggal kunjungan ulang (kontrol), tanggal pencabutan (IUD/Implan), serta data penanggung jawab. |
| 11 | Sistem | Pemeriksaan Saldo Stok Alokon | Sistem mengecek ketersediaan fisik alokon di instansi melalui isStokTersedia(1). Jika stok kosong, sistem menolak transaksi dan menampilkan pesan peringatan. |
| 12 | Sistem | Eksekusi Transaksi Database | Jika stok mencukupi, sistem menjalankan DB::transaction: (a) simpan rekam skrining medis, (b) simpan lembar informed consent, (c) simpan riwayat pelayanan, (d) kurangi stok alokon secara otomatis, dan (e) perbarui status antrean peserta menjadi 'hadir'. |
| 13 | Sistem | Notifikasi Sukses & Redirect | Commit transaksi database, menampilkan notifikasi toast sukses, dan mengarahkan kembali ke daftar riwayat pelayanan. |
| 14 | Bidan | Opsi Cetak Kartu KB (K/IV/KB) | Bidan dapat mencetak Kartu Peserta KB resmi sebagai bukti tindakan dan pengingat jadwal kontrol bagi akseptor. |



---


## 4.4.3 Activity Diagram: Autentikasi Login dan Hak Akses Pengguna

Autentikasi merupakan mekanisme pengamanan gerbang masuk sistem untuk memverifikasi identitas pengguna internal (petugas administrasi, tenaga medis bidan, dan pimpinan instansi). Sistem ini menggunakan pustaka Laravel Fortify yang menerapkan algoritma enkripsi password satu arah (bcrypt) serta fitur perlindungan terhadap serangan brute-force (rate limiting / throttling) dan pembajakan sesi (session fixation protection). Berdasarkan atribut level_akses yang dimiliki akun terautentikasi, sistem secara dinamis mengatur ketersediaan menu dan modul operasional.

Visualisasi alur aktivitas autentikasi login dan pengaturan hak akses pengguna disajikan pada Gambar 4.x berikut:


> **📷 [TEMPAT GAMBAR: Gambar 4.x Activity Diagram Autentikasi Login dan Hak Akses Pengguna]**

**Gambar 4.x Activity Diagram Autentikasi Login dan Hak Akses Pengguna**

Penjelasan tahapan aktivitas proses autentikasi dan otorisasi pengguna dirangkum pada Tabel 4.x berikut:


| No | Aktor Pelaksana | Aktivitas / Langkah | Deskripsi Rinci |
|:---:|:---:|---|---|
| 1 | Pengguna | Akses Halaman Login | Petugas membuka URL halaman login internal aplikasi (/login). |
| 2 | Sistem | Tampilkan Form Login | Sistem merender antarmuka login yang memuat kolom username, password, checkbox remember me, dan tombol submit. |
| 3 | Pengguna | Input Kredensial Akun | Memasukkan nama pengguna (username) dan kata sandi (password), lalu menekan tombol 'Masuk'. |
| 4 | Sistem | Validasi Format & Throttling | Memvalidasi keberadaan data input serta memeriksa batas frekuensi percobaan login (mencegah brute-force). |
| 5 | Sistem | Verifikasi Hash Password | Mencari data pengguna pada tabel users berdasarkan username dan membandingkan password dengan hash bcrypt menggunakan Hash::check(). |
| 6 | Sistem | Percabangan Kredensial Valid? | Jika kredensial salah, sistem mencatat kegagalan, menambah hitungan throttle, dan memunculkan pesan error. Jika benar, sistem melanjutkan proses otorisasi. |
| 7 | Sistem | Regenerasi Sesi Pengguna | Mereset counter percobaan gagal dan meregenerasi Session ID baru guna menangkal kerentanan session hijacking. |
| 8 | Sistem | Identifikasi Level Akses | Mengevaluasi nilai kolom level_akses pada objek User (admin, bidan, atau pimpinan). |
| 9a | Sistem | Inisialisasi Hak Akses Admin | Memberikan hak akses penuh: master desa/wilayah, faskes, akun petugas, manajemen inventaris alokon, kuota jadwal, dan audit sistem. |
| 9b | Sistem | Inisialisasi Hak Akses Bidan | Memberikan hak akses medis: eksekusi wizard 3 langkah pelayanan KB, pengisian skrining klinis, informed consent, rekam tindakan, dan cetak kartu KB (K/IV/KB). |
| 9c | Sistem | Inisialisasi Hak Akses Pimpinan | Memberikan hak akses eksekutif: dashboard monitoring statistik, pemetaan spasial GIS sebaran akseptor, dan rekapitulasi laporan periode. |
| 10 | Sistem | Redirect ke Dashboard | Mengarahkan browser pengguna ke route dashboard (/dashboard). |
| 11 | Pengguna | Melihat Dashboard Kerja | Pengguna menerima antarmuka Dashboard yang telah dipersonalisasi sesuai hak akses dan siap menjalankan operasional sistem. |



---


## 4.4.4 Activity Diagram: Pengelolaan Jadwal Pelayanan

Modul pengelolaan jadwal pelayanan merupakan fitur vital yang dikelola oleh Administrator/Operator Kecamatan untuk mengatur kalender operasional layanan KB di fasilitas kesehatan. Melalui antarmuka kalender interaktif, admin dapat membuka sesi layanan pada tanggal tertentu, membatasi kuota harian maksimal akseptor yang dilayani, mengatur interval jam pelayanan (waktu mulai hingga selesai), serta mengaktifkan atau menonaktifkan agenda layanan. Data jadwal yang dibuat secara otomatis menjadi rujukan kuota pada modul registrasi mandiri masyarakat.

Visualisasi alur aktivitas pengelolaan jadwal pelayanan disajikan pada Gambar 4.x berikut:


> **📷 [TEMPAT GAMBAR: Gambar 4.x Activity Diagram Pengelolaan Jadwal Pelayanan KB]**

**Gambar 4.x Activity Diagram Pengelolaan Jadwal Pelayanan KB**

Tahapan operasional pengelolaan kalender jadwal dirangkum secara kronologis pada Tabel 4.x berikut:


| No | Aktor Pelaksana | Aktivitas / Langkah | Deskripsi Rinci |
|:---:|:---:|---|---|
| 1 | Admin | Membuka Menu Jadwal | Admin mengakses menu jadwal pelayanan pada panel navigasi (/jadwal). |
| 2 | Sistem | Merender Kalender Interaktif | Sistem memuat grid kalender bulanan dan menampilkan indikator badge jumlah jadwal yang aktif pada setiap tanggal. |
| 3 | Admin | Klik Tanggal pada Kalender | Admin mengklik salah satu sel tanggal yang hendak diatur agendanya. |
| 4 | Sistem | Cek Jadwal di Database | Sistem melakukan query ke tabel jadwal_pelayanans berdasarkan tanggal terpilih dan ID faskes. |
| 5 | Sistem | Percabangan Jadwal Tersedia? | Jika belum ada jadwal di tanggal tersebut, sistem langsung membuka modal form penambahan. Jika sudah ada, sistem membuka modal rincian sesi. |
| 6a | Admin | Pilih Opsi Tambah Sesi | Pada modal rincian jadwal, admin memilih opsi menambah sesi baru atau mengelola sesi yang telah terdaftar. |
| 6b | Sistem | Buka Form Tambah Jadwal | Sistem menyiapkan formulir input dengan tanggal terpilih yang telah terisi secara otomatis. |
| 7 | Admin | Input Konfigurasi Jadwal | Memasukkan jam mulai (misal 08:00), jam selesai (misal 12:00), batas kuota pelayanan (misal 20 akseptor), dan keterangan lokasi/sesi. |
| 8 | Admin | Klik 'Simpan Jadwal' | Menekan tombol konfirmasi simpan data jadwal. |
| 9 | Sistem | Validasi Aturan Bisnis Jadwal | Memverifikasi format waktu, memastikan waktu selesai lebih lambat daripada waktu mulai (after:waktu_mulai), serta kuota minimal 1. |
| 10 | Sistem | Simpan ke Database | Menyimpan rekaman baru pada tabel jadwal_pelayanans dengan status default aktif (is_aktif = true) dan mencatat ID admin pembuat. |
| 11 | Sistem | Kirim Toast & Refresh Kalender | Menutup modal formulir, menampilkan notifikasi sukses, dan memuat ulang tampilan kalender sehingga badge kuota muncul secara dinamis. |



---


## 4.4.5 Activity Diagram: Cetak Laporan dan Monitoring Spasial GIS

Modul Laporan dan Monitoring Spasial Geografis (GIS) dirancang khusus untuk mendukung pengambilan keputusan (*Decision Support*) bagi Pimpinan DPPKB dan evaluasi program oleh Administrator. Alur kerja pada modul ini memfasilitasi penarikan rekapitulasi data pelayanan KB, penyaringan berbasis kriteria rentang tanggal, pemilihan wilayah administratif desa/kelurahan, pencetakan dokumen formal PDF berstandar A4 Landscape dengan lembar pengesahan tanda tangan, serta visualisasi pemetaan sebaran akseptor secara interaktif menggunakan Leaflet GIS.

Visualisasi alur aktivitas pencetakan laporan dan monitoring GIS disajikan pada Gambar 4.x berikut:


> **📷 [TEMPAT GAMBAR: Gambar 4.x Activity Diagram Cetak Laporan dan Monitoring Spasial GIS]**

**Gambar 4.x Activity Diagram Cetak Laporan dan Monitoring Spasial GIS**

Tahapan operasional penarikan laporan dan analisis GIS dirangkum secara kronologis pada Tabel 4.x berikut:


| No | Aktor Pelaksana | Aktivitas / Langkah | Deskripsi Rinci |
|:---:|:---:|---|---|
| 1 | Pimpinan/Admin | Membuka Menu Laporan | Pengguna internal membuka halaman modul laporan eksekutif (/laporan). |
| 2 | Sistem | Menampilkan Filter Parameter | Sistem memuat antarmuka navigasi tab (Pelayanan, Peserta, Alokon), preset waktu (Bulan Ini, Tahun Ini), serta dropdown filter wilayah dan alokon. |
| 3 | Pimpinan/Admin | Menentukan Parameter Filter | Memilih tab kategori data, mengatur rentang tanggal awal dan akhir, serta menyaring berdasarkan desa tertentu jika diperlukan. |
| 4 | Sistem | Eksekusi Query Dinamis | Sistem menjalankan query Eloquent dengan eager loading relasi ke tabel peserta, wilayah, alokon, dan skrining medis. |
| 5 | Sistem | Tampilkan Pratinjau & KPI | Menyajikan ringkasan indikator kunci (total akseptor, metode kontrasepsi terbanyak) dan tabel data hasil saringan. |
| 6 | Pimpinan/Admin | Memilih Opsi Analisis | Memilih apakah hendak mencetak laporan resmi format PDF atau beralih ke analisis peta spasial GIS. |
| 7a | Pimpinan/Admin | Klik 'Cetak Laporan (PDF)' | Menekan tombol unduh laporan resmi untuk kebutuhan administrasi fisik. |
| 8a | Sistem | Compile PDF Landscape (DomPDF) | Merender view cetak laporan ukuran A4 mendatar (landscape), menyusun kop resmi dinas, tabel agregasi, dan kolom tanda tangan pimpinan. |
| 9a | Sistem | Stream Unduhan Berkas PDF | Mengirimkan aliran berkas PDF siap unduh ke peramban pengguna. |
| 10a | Pimpinan/Admin | Menerima Berkas Laporan | Mengunduh dan menyimpan berkas PDF hasil rekapitulasi data. |
| 7b | Pimpinan/Admin | Buka Tab Peta Sebaran (GIS) | Beralih ke antarmuka pemetaan spasial sebaran akseptor (/peta-sebaran). |
| 8b | Sistem | Render Peta Interaktif Leaflet | Memuat peta digital Kecamatan Wundulako dengan pewarnaan tematik (choropleth) berdasarkan rasio kepadatan akseptor per desa. |
| 9b | Pimpinan/Admin | Klik Poligon Desa | Memilih area desa tertentu pada peta untuk menginspeksi metrik lokal. |
| 10b | Sistem | Tampilkan Info Detail Wilayah | Memunculkan popup statistik berisi jumlah peserta KB aktif dan alokon dominan di desa tersebut. |



---


## 4.4.6 Activity Diagram: Pengelolaan Inventaris Alokon

Pengelolaan inventaris Alat dan Obat Kontrasepsi (Alokon) merupakan proses bisnis penunjang yang krusial untuk memastikan ketersediaan fisik sarana kontrasepsi pada fasilitas kesehatan. Alur kerja pada modul ini memfasilitasi pencatatan komoditas alokon baru (seperti Suntik 3 Bulan, IUD/Spiral, Implan/Susuk, Pil, dan Kondom), pengisian saldo stok fisik (*restock* logistik), pemantauan saldo sisa, serta pemberian peringatan dini (*stock threshold warning*) apabila kuantitas alokon berada di bawah ambang batas minimal (kurang dari 10 unit), sehingga pelayanan medis kepada masyarakat tidak terhambat akibat kekosongan logistik.

Visualisasi alur aktivitas pengelolaan inventaris alokon disajikan pada Gambar 4.x berikut:


> **📷 [TEMPAT GAMBAR: Gambar 4.x Activity Diagram Pengelolaan Inventaris Alokon]**

**Gambar 4.x Activity Diagram Pengelolaan Inventaris Alokon**

Tahapan operasional pengelolaan inventaris alat dan obat kontrasepsi dirangkum pada Tabel 4.x berikut:


| No | Aktor Pelaksana | Aktivitas / Langkah | Deskripsi Rinci |
|:---:|:---:|---|---|
| 1 | Admin | Membuka Menu Alokon | Admin logistik membuka menu inventaris alokon (/alokon) pada bilah navigasi sistem. |
| 2 | Sistem | Tampilkan Tabel Inventaris | Sistem melakukan query data ke tabel alokons berdasarkan instansi, menampilkan saldo stok fisik, serta menandai badge merah bagi alokon dengan stok menipis (< 10 unit). |
| 3 | Admin | Memilih Operasi Logistik | Admin menentukan apakah hendak mendaftarkan jenis komoditas kontrasepsi baru atau memperbarui (restock) saldo kuantitas alat yang sudah ada. |
| 4a | Admin | Klik 'Tambah Alokon' | Menekan tombol penambahan alokon baru. |
| 5a | Sistem | Buka Modal Tambah | Sistem membuka modal formulir kosong dan memilih faskes default. |
| 4b | Admin | Klik 'Edit / Restock' | Menekan tombol perbarui pada baris alokon yang saldo fisiknya ingin disesuaikan. |
| 5b | Sistem | Buka Modal Edit | Sistem memuat data lama nama alokon dan kuantitas stok yang sedang tercatat. |
| 6 | Admin | Input / Sesuaikan Stok | Mengisi nama metode/komoditas alokon dan memasukkan saldo stok fisik terkini (harus bilangan bulat >= 0). |
| 7 | Admin | Klik Tombol 'Simpan' | Menekan tombol simpan formulir modal. |
| 8 | Sistem | Validasi Format Data | Sistem memeriksa kelayakan data input (nama alokon wajib string dan stok minimal 0). |
| 9 | Sistem | Simpan ke Tabel alokons | Mengeksekusi penyimpanan data baru (create) atau pembaruan saldo (update) pada basis data. |
| 10 | Sistem | Kirim Toast & Refresh Data | Menutup modal form, menampilkan notifikasi toast keberhasilan, dan memuat ulang tabel data inventaris. |
| 11 | Admin | Melihat Saldo Stok Terkini | Admin memastikan saldo alokon telah diperbarui dan siap dialokasikan untuk tindakan pelayanan kebidanan. |



---
