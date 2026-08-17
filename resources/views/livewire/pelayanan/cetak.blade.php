<div class="min-h-screen bg-zinc-300/80 p-4 sm:p-6 dark:bg-zinc-950 font-sans text-black">
    <!-- Float Action Bar (Hidden on Print) -->
    <div class="no-print max-w-[215mm] mx-auto mb-5 flex flex-wrap justify-between items-center bg-white dark:bg-zinc-900 p-3.5 rounded-xl shadow-md border border-zinc-200 dark:border-zinc-800 gap-3">
        <div class="flex items-center gap-3">
            <flux:button variant="outline" size="sm" href="{{ route('pelayanan.show', $pelayanan->id) }}" icon="arrow-left" wire:navigate>
                Kembali ke Detail
            </flux:button>
            <div class="text-xs text-zinc-600 dark:text-zinc-400">
                Ukuran Kertas: <span class="font-bold text-blue-600 dark:text-blue-400">F4 / Folio (215 x 330 mm) Portrait — Tepat 2 Halaman</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <flux:button variant="primary" size="sm" onclick="window.print()" icon="printer" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4">
                Cetak Formulir (2 Halaman)
            </flux:button>
        </div>
    </div>

    @php
        // Helper Mappings for BKKBN Official Codes (K/IV/KB/15)
        $getPendidikanCode = function($val) {
            if (!$val) return '';
            $v = strtolower($val);
            if (str_contains($v, 'tidak tamat sd')) return '1';
            if (str_contains($v, 'tamat sd') || $v === 'sd') return '2';
            if (str_contains($v, 'sltp') || str_contains($v, 'smp') || str_contains($v, 'mts')) return '3';
            if (str_contains($v, 'slta') || str_contains($v, 'sma') || str_contains($v, 'smk') || str_contains($v, 'ma')) return '4';
            if (str_contains($v, 'pt') || str_contains($v, 'akademi') || str_contains($v, 'diploma') || str_contains($v, 'sarjana') || str_contains($v, 's1') || str_contains($v, 's2')) return '5';
            if (str_contains($v, 'tidak sekolah')) return '6';
            return '';
        };

        $getPekerjaanCode = function($val) {
            if (!$val) return '';
            $v = strtolower($val);
            if (str_contains($v, 'petani')) return '1';
            if (str_contains($v, 'nelayan')) return '2';
            if (str_contains($v, 'pedagang')) return '3';
            if (str_contains($v, 'pns') || str_contains($v, 'tni') || str_contains($v, 'polri') || str_contains($v, 'asn')) return '4';
            if (str_contains($v, 'swasta') || str_contains($v, 'karyawan')) return '5';
            if (str_contains($v, 'wiraswasta') || str_contains($v, 'usaha')) return '6';
            if (str_contains($v, 'pensiun')) return '7';
            if (str_contains($v, 'lepas') || str_contains($v, 'buruh') || str_contains($v, 'freelance')) return '8';
            if (str_contains($v, 'tidak bekerja') || str_contains($v, 'irt') || str_contains($v, 'rumah tangga')) return '10';
            return '9';
        };

        $getAsuransiCode = function($val) {
            if (!$val) return '3';
            $v = strtolower($val);
            if (str_contains($v, 'bpjs') || str_contains($v, 'kis')) return '1';
            if ($v === 'umum' || $v === 'tidak' || $v === 'mandiri') return '3';
            return '2';
        };

        $getStatusKbCode = function($val) {
            if (!$val) return '1';
            $v = strtolower($val);
            if ($v === 'baru' || str_contains($v, 'baru')) return '1';
            if (str_contains($v, 'bersalin') || str_contains($v, 'keguguran') || str_contains($v, 'pasca')) return '2';
            if ($v === 'ganti_cara' || str_contains($v, 'ganti') || str_contains($v, 'pernah')) return '3';
            if ($v === 'ulangan' || str_contains($v, 'sedang')) return '4';
            return '1';
        };

        $getAlokonCode = function($val) {
            if (!$val) return '';
            $v = strtolower($val);
            if (str_contains($v, '1 bulan') || str_contains($v, '1 bulanan')) return '1';
            if (str_contains($v, '3 bulan') || str_contains($v, '3 bulanan') || str_contains($v, 'progestin') || str_contains($v, 'kombinasi')) {
                if (str_contains($v, '1 bulan')) return '1';
                if (str_contains($v, 'pil')) return '3';
                return '2';
            }
            if (str_contains($v, 'pil')) return '3';
            if (str_contains($v, 'kondom')) return '4';
            if (str_contains($v, 'implan 1') || str_contains($v, 'implant 1')) return '5';
            if (str_contains($v, 'implan 2') || str_contains($v, 'implant 2') || str_contains($v, 'implan') || str_contains($v, 'susuk')) return '6';
            if (str_contains($v, 'cut 380a') || str_contains($v, 'iud cut') || str_contains($v, 'akdr cut')) return '7';
            if (str_contains($v, 'iud') || str_contains($v, 'akdr')) return '8';
            if (str_contains($v, 'tubektomi') || str_contains($v, 'mow')) return '9';
            if (str_contains($v, 'vasektomi') || str_contains($v, 'mop')) return '10';
            return '';
        };

        $getKeadaanUmumCode = function($val) {
            if (!$val) return '1';
            $v = strtolower($val);
            if (str_contains($v, 'sedang')) return '2';
            if (str_contains($v, 'kurang') || str_contains($v, 'buruk')) return '3';
            return '1';
        };

        $getPosisiRahimCode = function($val) {
            if (!$val) return '';
            $v = strtolower($val);
            if (str_contains($v, 'retro')) return '1';
            if (str_contains($v, 'ante')) return '2';
            return '';
        };

        $peserta = $pelayanan->pesertaKb;
        $skrining = $pelayanan->skriningMedis;
        $alokon = $pelayanan->alokon;

        // DOB & Umur (2 digit each)
        $dobDay = $peserta->tanggal_lahir_istri ? $peserta->tanggal_lahir_istri->format('d') : '';
        $dobMonth = $peserta->tanggal_lahir_istri ? $peserta->tanggal_lahir_istri->format('m') : '';
        $dobYear = $peserta->tanggal_lahir_istri ? $peserta->tanggal_lahir_istri->format('y') : '';
        $age = $peserta->tanggal_lahir_istri ? str_pad($peserta->tanggal_lahir_istri->age, 2, '0', STR_PAD_LEFT) : '';

        // Anak Terakhir
        $childYears = $peserta->umur_anak_terakhir !== null ? str_pad(floor($peserta->umur_anak_terakhir / 12), 2, '0', STR_PAD_LEFT) : '';
        $childMonths = $peserta->umur_anak_terakhir !== null ? str_pad($peserta->umur_anak_terakhir % 12, 2, '0', STR_PAD_LEFT) : '';

        // Skrining Haid
        $haidDay = $skrining && $skrining->haid_terakhir ? $skrining->haid_terakhir->format('d') : '';
        $haidMonth = $skrining && $skrining->haid_terakhir ? $skrining->haid_terakhir->format('m') : '';
        $haidYear = $skrining && $skrining->haid_terakhir ? $skrining->haid_terakhir->format('y') : '';

        // Skrining GPA (2 digit each)
        $gpaG = ''; $gpaP = ''; $gpaA = '';
        if ($skrining && $skrining->gravida_partus_abortus) {
            $gpaParts = explode('-', $skrining->gravida_partus_abortus);
            $gpaG = str_pad($gpaParts[0] ?? '', 2, '0', STR_PAD_LEFT);
            $gpaP = str_pad($gpaParts[1] ?? '', 2, '0', STR_PAD_LEFT);
            $gpaA = str_pad($gpaParts[2] ?? '', 2, '0', STR_PAD_LEFT);
        }

        // Boleh digunakan array
        $bolehArr = ($skrining && $skrining->alat_kontrasepsi_boleh_digunakan) 
            ? (json_decode($skrining->alat_kontrasepsi_boleh_digunakan, true) ?? []) 
            : [];

        // Dates (2 digit each)
        $tglDilayaniD = $pelayanan->tanggal_pelayanan ? $pelayanan->tanggal_pelayanan->format('d') : '';
        $tglDilayaniM = $pelayanan->tanggal_pelayanan ? $pelayanan->tanggal_pelayanan->format('m') : '';
        $tglDilayaniY = $pelayanan->tanggal_pelayanan ? $pelayanan->tanggal_pelayanan->format('y') : '';

        $tglUlangD = $pelayanan->tanggal_kunjungan_ulang ? $pelayanan->tanggal_kunjungan_ulang->format('d') : '';
        $tglUlangM = $pelayanan->tanggal_kunjungan_ulang ? $pelayanan->tanggal_kunjungan_ulang->format('m') : '';
        $tglUlangY = $pelayanan->tanggal_kunjungan_ulang ? $pelayanan->tanggal_kunjungan_ulang->format('y') : '';

        $tglCabutD = $pelayanan->tanggal_dicabut ? $pelayanan->tanggal_dicabut->format('d') : '';
        $tglCabutM = $pelayanan->tanggal_dicabut ? $pelayanan->tanggal_dicabut->format('m') : '';
        $tglCabutY = $pelayanan->tanggal_dicabut ? $pelayanan->tanggal_dicabut->format('y') : '';
    @endphp

    <!-- Container Form -->
    <div id="print-area" class="max-w-[215mm] mx-auto space-y-8 print:space-y-0 print:m-0">
        
        <!-- ========================================================================= -->
        <!-- ==================== HALAMAN 1: KARTU STATUS PESERTA KB ================= -->
        <!-- ========================================================================= -->
        <div class="page-sheet bg-white border-2 border-black text-black leading-tight select-none shadow-xl print:shadow-none" style="padding: 4mm 5mm;">
            
            <!-- ====== HEADER: I, II & JUDUL ====== -->
            <div class="border-b border-black pb-1 mb-1">
                <!-- K/IV/KB/15 label -->
                <div class="flex justify-end mb-0.5">
                    <div class="border-2 border-black px-2 py-0.5 font-bold text-[10px] tracking-tight">K/IV/KB/15</div>
                </div>

                <!-- Row: I. Kode Faskes (left) & II. Kode Keluarga (right) -->
                <div class="flex justify-between items-start text-[8px] gap-2">
                    <!-- I. Kode Faskes KB/Jaringan/Jejaring -->
                    <div class="flex items-start gap-1">
                        <div class="font-bold leading-tight shrink-0">
                            <span>I.</span>&nbsp;&nbsp;
                            <span>Kode Faskes KB/Jaringan/<br>&nbsp;&nbsp;&nbsp;&nbsp;Jejaring</span>
                        </div>
                        <span class="pt-0.5">:</span>
                        <div class="flex gap-2 pt-0.5">
                            <div class="flex flex-col items-center">
                                <div class="flex"><div class="cb"></div><div class="cb -ml-px"></div></div>
                                <span class="text-[5px] text-center mt-0.5 leading-none">Kode Provinsi</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="flex"><div class="cb"></div><div class="cb -ml-px"></div></div>
                                <span class="text-[5px] text-center mt-0.5 leading-none">Kode Kabupaten/K</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="flex"><div class="cb"></div><div class="cb -ml-px"></div><div class="cb -ml-px"></div></div>
                                <span class="text-[5px] text-center mt-0.5 leading-none">No Register Faskes KB</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="flex"><div class="cb"></div><div class="cb -ml-px"></div></div>
                                <span class="text-[5px] text-center mt-0.5 leading-none">No Jaringan/Jejaring<br>Faskes KB</span>
                            </div>
                        </div>
                    </div>

                    <!-- II. Kode Keluarga Indonesia -->
                    <div class="flex items-start gap-1">
                        <span class="font-bold whitespace-nowrap pt-0.5">II. Kode Keluarga Indonesia :</span>
                        <div class="flex gap-1 pt-0.5">
                            <div class="flex flex-col items-center">
                                <div class="flex"><div class="cb">{{ substr($peserta->nik ?? '', 0, 1) }}</div><div class="cb -ml-px">{{ substr($peserta->nik ?? '', 1, 1) }}</div></div>
                                <span class="text-[5px] mt-0.5 leading-none">Kode Provinsi</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="flex"><div class="cb">{{ substr($peserta->nik ?? '', 2, 1) }}</div><div class="cb -ml-px">{{ substr($peserta->nik ?? '', 3, 1) }}</div></div>
                                <span class="text-[5px] mt-0.5 leading-none">Kode Kabupaten</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="flex"><div class="cb">{{ substr($peserta->nik ?? '', 4, 1) }}</div><div class="cb -ml-px">{{ substr($peserta->nik ?? '', 5, 1) }}</div></div>
                                <span class="text-[5px] mt-0.5 leading-none">Kode Kecamatan</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="flex"><div class="cb">{{ substr($peserta->nik ?? '', 6, 1) }}</div><div class="cb -ml-px">{{ substr($peserta->nik ?? '', 7, 1) }}</div><div class="cb -ml-px">{{ substr($peserta->nik ?? '', 8, 1) }}</div></div>
                                <span class="text-[5px] mt-0.5 leading-none">Kode Kelurahan/Desa</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="flex"><div class="cb">{{ substr($peserta->nik ?? '', 9, 1) }}</div><div class="cb -ml-px">{{ substr($peserta->nik ?? '', 10, 1) }}</div><div class="cb -ml-px">{{ substr($peserta->nik ?? '', 11, 1) }}</div><div class="cb -ml-px">{{ substr($peserta->nik ?? '', 12, 1) }}</div></div>
                                <span class="text-[5px] mt-0.5 leading-none">No urut Keluarga</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Judul Tengah -->
                <div class="text-center mt-2 mb-0.5">
                    <h1 class="text-[13px] font-black tracking-widest uppercase">KARTU STATUS PESERTA KB</h1>
                </div>
            </div>

            <!-- ====== SECTION III - IX: IDENTITAS (2 KOLOM) ====== -->
            <div class="flex text-[8px] border-b border-black pb-1 mb-1">
                <!-- Kolom Kiri: III, V, VII, IX -->
                <div class="w-[48%] space-y-0.5 pr-1">
                    <div class="flex">
                        <span class="font-bold w-[110px] shrink-0">III.&nbsp;&nbsp;&nbsp;Nama Peserta KB</span>
                        <span>: <b>{{ strtoupper($peserta->nama_lengkap) }}</b></span>
                    </div>
                    <div class="flex">
                        <span class="font-bold w-[110px] shrink-0">V.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama Suami/Istri</span>
                        <span>: {{ strtoupper($peserta->nama_suami_istri) }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-bold w-[110px] shrink-0">VII.&nbsp;&nbsp;&nbsp;Alamat Peserta KB</span>
                        <span>: {{ $peserta->alamat_lengkap }}</span>
                    </div>
                    <div class="flex items-start">
                        <span class="font-bold w-[110px] shrink-0">IX.&nbsp;&nbsp;&nbsp;&nbsp;Penggunaan Asuransi</span>
                        <span>:</span>
                        <div class="flex items-start gap-1 ml-1">
                            <div class="cb-lg mt-0.5">{{ $getAsuransiCode($peserta->penggunaan_asuransi) }}</div>
                            <div class="text-[7.5px] leading-tight">
                                <div>1)&nbsp;&nbsp;BPJS Kesehatan</div>
                                <div>2)&nbsp;&nbsp;Lainnya</div>
                                <div>3)&nbsp;&nbsp;Tidak</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: IV, VI, VIII -->
                <div class="w-[52%] space-y-0.5 pl-1">
                    <!-- IV. Tgl Lahir/Umur Istri -->
                    <div class="flex items-center">
                        <span class="font-bold shrink-0">IV. Tgl Lahir/Umur Istri</span>
                        <div class="flex items-center gap-0.5 ml-auto">
                            <div class="cb">{{ substr($dobDay, 0, 1) }}</div><div class="cb -ml-px">{{ substr($dobDay, 1, 1) }}</div>
                            <div class="cb ml-1">{{ substr($dobMonth, 0, 1) }}</div><div class="cb -ml-px">{{ substr($dobMonth, 1, 1) }}</div>
                            <div class="cb ml-1">{{ substr($dobYear, 0, 1) }}</div><div class="cb -ml-px">{{ substr($dobYear, 1, 1) }}</div>
                            <span class="font-bold mx-1">/</span>
                            <div class="cb">{{ substr($age, 0, 1) }}</div><div class="cb -ml-px">{{ substr($age, 1, 1) }}</div>
                        </div>
                    </div>

                    <!-- VI. Pendidikan Suami dan Istri -->
                    <div>
                        <div class="flex items-start">
                            <span class="font-bold shrink-0">VI. Pendidikan Suami dan Istri</span>
                            <span class="ml-1">:</span>
                        </div>
                        <div class="flex items-start justify-between mt-0.5 pl-4">
                            <div class="grid grid-cols-3 gap-x-2 text-[7px] leading-tight">
                                <div>1)&nbsp;&nbsp;Tidak Tamat SD/MI</div>
                                <div>2)&nbsp;&nbsp;Tamat SD/MI</div>
                                <div>3)&nbsp;&nbsp;Tamat SLTP/MTSN</div>
                                <div>4)&nbsp;&nbsp;Tamat SLTA/MA</div>
                                <div>5)&nbsp;&nbsp;Tamat PT</div>
                                <div>6)&nbsp;&nbsp;Tidak Sekolah</div>
                            </div>
                            <div class="flex gap-2 shrink-0 ml-1">
                                <div class="text-center">
                                    <span class="text-[6px] block italic">Suami</span>
                                    <div class="cb-lg">{{ $getPendidikanCode($peserta->pendidikan_suami) }}</div>
                                </div>
                                <div class="text-center">
                                    <span class="text-[6px] block italic">Istri</span>
                                    <div class="cb-lg">{{ $getPendidikanCode($peserta->pendidikan_istri) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- VIII. Pekerjaan Suami dan Istri -->
                    <div>
                        <div class="flex items-start">
                            <span class="font-bold shrink-0">VIII. Pekerjaan Suami dan Istri</span>
                            <span class="ml-1">:</span>
                        </div>
                        <div class="flex items-start justify-between mt-0.5 pl-4">
                            <div class="grid grid-cols-2 gap-x-3 text-[7px] leading-tight">
                                <div>1)&nbsp;&nbsp;Petani</div>
                                <div>5)&nbsp;&nbsp;Pegawai Swasta</div>
                                <div>2)&nbsp;&nbsp;Nelayan</div>
                                <div>6)&nbsp;&nbsp;Wiraswasta</div>
                                <div>3)&nbsp;&nbsp;Pedagang</div>
                                <div>7)&nbsp;&nbsp;Pensiunan</div>
                                <div>4)&nbsp;&nbsp;PNS/TNI/POLRI</div>
                                <div>8)&nbsp;&nbsp;Pekerja Lepas</div>
                            </div>
                            <div class="text-[7px] leading-tight shrink-0 ml-1">
                                <div>9)&nbsp;&nbsp;Lainnya</div>
                                <div class="border-b border-black w-16 mt-0.5"></div>
                                <div>10) Tidak Bekerja</div>
                            </div>
                            <div class="flex gap-2 shrink-0 ml-1">
                                <div class="text-center">
                                    <span class="text-[6px] block italic">Suami</span>
                                    <div class="cb-lg">{{ $getPekerjaanCode($peserta->pekerjaan_suami) }}</div>
                                </div>
                                <div class="text-center">
                                    <span class="text-[6px] block italic">Istri</span>
                                    <div class="cb-lg">{{ $getPekerjaanCode($peserta->pekerjaan_istri) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ====== SECTION X - XIII ====== -->
            <div class="flex text-[8px] border-b border-black pb-1 mb-1">
                <!-- Kolom Kiri: X, XII -->
                <div class="w-[48%] space-y-0.5 pr-1">
                    <!-- X. Jumlah anak hidup -->
                    <div class="flex items-center">
                        <span class="font-bold w-[110px] shrink-0">X.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jumlah anak hidup</span>
                        <span>:</span>
                        <div class="flex items-center gap-3 ml-2">
                            <div class="flex items-center gap-1">
                                <div class="cb-lg">{{ $peserta->jumlah_anak_laki ?? 0 }}</div>
                                <span class="text-[7px]">Laki-laki</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="cb-lg">{{ $peserta->jumlah_anak_perempuan ?? 0 }}</div>
                                <span class="text-[7px]">Perempuan</span>
                            </div>
                        </div>
                    </div>

                    <!-- XII. Status Peserta KB -->
                    <div class="flex items-start">
                        <span class="font-bold w-[110px] shrink-0">XII.&nbsp;&nbsp;&nbsp;Status Peserta KB</span>
                        <span>:</span>
                        <div class="flex items-start gap-1 ml-1">
                            <div class="cb-lg mt-0.5 shrink-0">{{ $getStatusKbCode($peserta->status_kepesertaan) }}</div>
                        </div>
                    </div>
                    <div class="pl-6 text-[7px] leading-tight">
                        <div>1)&nbsp;&nbsp;Baru Pertama kali</div>
                        <div>2)&nbsp;&nbsp;Pernah pakai alat KB berhenti sesudah bersalin/keguguran</div>
                        <div>3)&nbsp;&nbsp;Pernah pakai alat KB</div>
                        <div>4)&nbsp;&nbsp;Sedang ber-KB</div>
                    </div>
                </div>

                <!-- Kolom Kanan: XI, XIII -->
                <div class="w-[52%] space-y-0.5 pl-1">
                    <!-- XI. Umur anak terakhir -->
                    <div class="flex items-center">
                        <span class="font-bold shrink-0">XI.&nbsp;&nbsp;&nbsp;Umur anak terakhir yang masih<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;hidup</span>
                        <div class="flex items-center gap-2 ml-auto">
                            <span>:</span>
                            <div class="text-center">
                                <span class="text-[6px] block italic">Tahun</span>
                                <div class="flex"><div class="cb">{{ substr($childYears, 0, 1) }}</div><div class="cb -ml-px">{{ substr($childYears, 1, 1) }}</div></div>
                            </div>
                            <div class="text-center">
                                <span class="text-[6px] block italic">Bulan</span>
                                <div class="flex"><div class="cb">{{ substr($childMonths, 0, 1) }}</div><div class="cb -ml-px">{{ substr($childMonths, 1, 1) }}</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- XIII. Alat/Obat/Cara KB terakhir -->
                    <div class="flex items-start gap-1">
                        <span class="font-bold shrink-0">XIII. Alat/Obat/Cara KB terakhir</span>
                    </div>
                    <div class="flex items-start pl-4 text-[7px] leading-tight gap-2">
                        <div class="grid grid-cols-5 gap-x-1.5">
                            <div>1)&nbsp;&nbsp;Suntikan 1 Bulanan</div>
                            <div>2)&nbsp;&nbsp;Suntikan 3 Bulanan</div>
                            <div>3)&nbsp;&nbsp;Pil</div>
                            <div>4)&nbsp;&nbsp;Kondom</div>
                            <div>&nbsp;</div>
                            <div>5)&nbsp;&nbsp;Implan 1 Batang</div>
                            <div>6)&nbsp;&nbsp;Implan 2 Batang</div>
                            <div>7)&nbsp;&nbsp;IUD CuT 380A</div>
                            <div>8)&nbsp;&nbsp;IUD Lain-lain</div>
                            <div>&nbsp;</div>
                            <div>9)&nbsp;&nbsp;Tubektomi</div>
                            <div>10)&nbsp;Vasektomi</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ====== SECTION XIV: PENAPISAN (SKRINING) ====== -->
            <div class="text-[8px] border-b border-black pb-1 mb-1">
                <div class="font-bold text-[8.5px] mb-0.5">
                    XIV.&nbsp;&nbsp;&nbsp;Penapisan (Skrining) untuk menentukan alat kontrasepsi yang dapat digunakan &nbsp;calon peserta &nbsp;KB
                </div>
                <div class="pl-6 text-[7px] text-black mb-0.5 leading-tight">
                    <b>Petunjuk :</b>&nbsp;&nbsp;&nbsp;Periksalah keadaan berikut ini dan hasilnya ditulis dengan angka atau tanda centang (√) pada kotak yang tersedia.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Penapisan (Skrining) hanya boleh dilakukan oleh pelaksana yang telah dilatih dalam &nbsp;pelayanan KB.
                </div>

                <!-- ===== ANAMNESE ===== -->
                <div class="mt-1">
                    <div class="font-bold underline text-[8.5px] mb-0.5 pl-2">Anamnese</div>

                    <!-- Row: 1. Haid terakhir & 2. Hamil/Diduga Hamil -->
                    <div class="flex items-center justify-between pl-2 mb-1">
                        <div class="flex items-center gap-1">
                            <span class="font-bold">1. Haid terakhir tanggal</span>
                            <span>:</span>
                            <div class="flex items-end gap-1 ml-1">
                                <div class="text-center">
                                    <div class="flex"><div class="cb">{{ substr($haidDay, 0, 1) }}</div><div class="cb -ml-px">{{ substr($haidDay, 1, 1) }}</div></div>
                                    <span class="text-[5.5px] italic">Tanggal</span>
                                </div>
                                <div class="text-center">
                                    <div class="flex"><div class="cb">{{ substr($haidMonth, 0, 1) }}</div><div class="cb -ml-px">{{ substr($haidMonth, 1, 1) }}</div></div>
                                    <span class="text-[5.5px] italic">Bulan</span>
                                </div>
                                <div class="text-center">
                                    <div class="flex"><div class="cb">{{ substr($haidYear, 0, 1) }}</div><div class="cb -ml-px">{{ substr($haidYear, 1, 1) }}</div></div>
                                    <span class="text-[5.5px] italic">Tahun</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="font-bold">2. Hamil/Diduga Hamil</span>
                            <span>:</span>
                            <div class="cb-lg ml-1">{{ $skrining ? ($skrining->hamil_diduga_hamil ? '1' : '2') : '2' }}</div>
                            <div class="text-[7px] leading-tight ml-1">
                                <div>1) Ya</div>
                                <div>2) Tidak</div>
                            </div>
                        </div>
                    </div>

                    <!-- Row: 3. Jumlah GPA -->
                    <div class="flex items-center pl-2 mb-1">
                        <span class="font-bold">3. Jumlah GPA</span>
                        <span class="ml-1">:</span>
                    </div>
                    <div class="flex items-center gap-6 pl-6 mb-1">
                        <div class="flex items-center gap-1">
                            <span>Gravida (Kehamilan)</span>
                            <div class="flex ml-1"><div class="cb">{{ substr($gpaG, 0, 1) }}</div><div class="cb -ml-px">{{ substr($gpaG, 1, 1) }}</div></div>
                        </div>
                        <div class="flex items-center gap-1">
                            <span>Partus (Persalinan)</span>
                            <div class="flex ml-1"><div class="cb">{{ substr($gpaP, 0, 1) }}</div><div class="cb -ml-px">{{ substr($gpaP, 1, 1) }}</div></div>
                        </div>
                        <div class="flex items-center gap-1">
                            <span>Abortus (Keguguran)</span>
                            <div class="flex ml-1"><div class="cb">{{ substr($gpaA, 0, 1) }}</div><div class="cb -ml-px">{{ substr($gpaA, 1, 1) }}</div></div>
                        </div>
                    </div>

                    <!-- Row: 4. Menyusui -->
                    <div class="flex items-center pl-2 mb-1">
                        <span class="font-bold">4. Menyusui</span>
                        <span class="ml-1">:</span>
                        <div class="cb-lg ml-2">{{ $skrining ? ($skrining->status_menyusui ? '1' : '2') : '2' }}</div>
                        <div class="text-[7px] ml-1.5">
                            <span>1) Ya</span>
                            <span class="ml-2">2) Tidak</span>
                        </div>
                    </div>

                    <!-- Row: 5. Riwayat Penyakit Sebelumnya + Notice Box (side by side) -->
                    <div class="flex items-start pl-2 gap-3">
                        <!-- Left: Items 5a-5d with Tidak/Ya -->
                        <div class="w-[55%]">
                            <div class="font-bold mb-0.5">5. Riwayat Penyakit Sebelumnya :</div>
                            <div class="flex justify-end pr-2 text-[7px] mb-0.5 gap-3">
                                <span>Tidak</span>
                                <span>Ya</span>
                            </div>
                            <div class="space-y-0.5 pl-3">
                                <div class="flex items-center justify-between">
                                    <span>a.&nbsp;&nbsp;&nbsp;Sakit kuning</span>
                                    <div class="flex gap-3 pr-2">
                                        <div class="cb">{!! ($skrining && !$skrining->rwyt_sakit_kuning) ? '√' : '' !!}</div>
                                        <div class="cb">{!! ($skrining && $skrining->rwyt_sakit_kuning) ? '√' : '' !!}</div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="leading-tight">b.&nbsp;&nbsp;&nbsp;Perdarahan pervaginam yang<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tidak diketahui sebabnya</span>
                                    <div class="flex gap-3 pr-2">
                                        <div class="cb">{!! ($skrining && !$skrining->rwyt_pendarahan) ? '√' : '' !!}</div>
                                        <div class="cb">{!! ($skrining && $skrining->rwyt_pendarahan) ? '√' : '' !!}</div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>c.&nbsp;&nbsp;&nbsp;Keputihan yang lama</span>
                                    <div class="flex gap-3 pr-2">
                                        <div class="cb">{!! ($skrining && !$skrining->rwyt_keputihan) ? '√' : '' !!}</div>
                                        <div class="cb">{!! ($skrining && $skrining->rwyt_keputihan) ? '√' : '' !!}</div>
                                    </div>
                                </div>
                                <div class="flex items-start justify-between">
                                    <div class="leading-tight">
                                        <span>d.&nbsp;&nbsp;&nbsp;Tumor</span><br>
                                        <span class="pl-6">-&nbsp;&nbsp;&nbsp;&nbsp;Payudara</span><br>
                                        <span class="pl-6">-&nbsp;&nbsp;&nbsp;&nbsp;Rahim</span><br>
                                        <span class="pl-6">-&nbsp;&nbsp;&nbsp;&nbsp;Indung telur</span>
                                    </div>
                                    <div class="flex gap-3 pr-2 pt-0.5">
                                        <div class="cb">{!! ($skrining && !$skrining->rwyt_tumor) ? '√' : '' !!}</div>
                                        <div class="cb">{!! ($skrining && $skrining->rwyt_tumor) ? '√' : '' !!}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Notice Box -->
                        <div class="w-[45%] mt-4">
                            <div class="border-2 border-black p-1.5 text-[7px] leading-tight">
                                <div class="mb-0.5">-&nbsp;&nbsp;&nbsp;Bila semua jawaban <b>TIDAK</b>, dapat diberikan salah satu dari cara KB (kecuali IUD dan Tubektomi).</div>
                                <div>-&nbsp;&nbsp;&nbsp;Bila salah satu jawaban <b>YA</b>, rujuk ke dokter.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== PEMERIKSAAN ===== -->
                <div class="mt-1.5">
                    <div class="font-bold underline text-[8.5px] mb-0.5 pl-2">Pemeriksaan</div>

                    <!-- Row: 6. Keadaan Umum & 7. Berat Badan -->
                    <div class="flex items-center justify-between pl-4 mb-0.5">
                        <div class="flex items-center gap-1">
                            <span class="font-bold">6. Keadaan Umum</span>
                            <span>:</span>
                            <div class="cb-lg ml-1">{{ $getKeadaanUmumCode($skrining ? $skrining->fisik_keadaan_umum : '') }}</div>
                            <span class="ml-1">1) Baik</span>
                            <span class="ml-3">2) Sedang</span>
                            <span class="ml-3">3) Kurang</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="font-bold">7. Berat Badan</span>
                            <span>:</span>
                            <div class="cb-lg ml-1">{{ $skrining->fisik_berat_badan ?? '' }}</div>
                            <span class="ml-1">Kg</span>
                        </div>
                    </div>

                    <!-- Row: 8. Tekanan Darah -->
                    <div class="flex items-center pl-4 mb-1">
                        <span class="font-bold">8. Tekanan Darah</span>
                        <span class="ml-1">:</span>
                        <span class="ml-2 border-b border-black inline-block min-w-[120px] font-bold text-center">{{ $skrining->fisik_tekanan_darah ?? '' }}</span>
                    </div>

                    <!-- Row: 9. Pemasangan IUD + 10. Posisi Rahim + Notice Box -->
                    <div class="flex items-start pl-4 gap-2 mb-1">
                        <!-- Left: 9a, 9b -->
                        <div class="w-[38%]">
                            <div class="font-bold leading-tight mb-0.5">9. Sebelum dilakukan pemasangan IUD atau<br>&nbsp;&nbsp;&nbsp;&nbsp;Tubektomi dilakukan pemeriksaan dalam :</div>
                            <div class="flex justify-end pr-1 text-[7px] mb-0.5 gap-3">
                                <span>Tidak</span>
                                <span>Ya</span>
                            </div>
                            <div class="space-y-0.5 pl-4">
                                <div class="flex items-center justify-between">
                                    <span>a.&nbsp;&nbsp;&nbsp;Tanda - tanda radang</span>
                                    <div class="flex gap-3 pr-1">
                                        <div class="cb">{!! ($skrining && !$skrining->pemeriksaan_dalam_radang) ? '√' : '' !!}</div>
                                        <div class="cb">{!! ($skrining && $skrining->pemeriksaan_dalam_radang) ? '√' : '' !!}</div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>b.&nbsp;&nbsp;&nbsp;Tumor/keganasan ginekologi</span>
                                    <div class="flex gap-3 pr-1">
                                        <div class="cb">{!! ($skrining && !$skrining->pemeriksaan_dalam_tumor) ? '√' : '' !!}</div>
                                        <div class="cb">{!! ($skrining && $skrining->pemeriksaan_dalam_tumor) ? '√' : '' !!}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Center: 10. Posisi Rahim -->
                        <div class="w-[25%] pt-0.5">
                            <div class="flex items-center gap-1">
                                <span class="font-bold">10.&nbsp;&nbsp;Posisi Rahim</span>
                                <span>:</span>
                                <div class="cb-lg ml-1">{{ $getPosisiRahimCode($skrining ? $skrining->posisi_rahim : '') }}</div>
                            </div>
                            <div class="text-[7px] pl-6 mt-0.5">
                                <span>1) Retrofleksi</span>
                                <span class="ml-2">2) Antefleksi</span>
                            </div>
                        </div>
                        <!-- Right: Notice Box IUD -->
                        <div class="w-[37%] pt-2">
                            <div class="border-2 border-black p-1.5 text-[7px] leading-tight">
                                Bila semua jawaban <b>TIDAK</b>, pemasangan IUD atau tindakan Tubektomi dapat dilakukan.
                                Bila salah satu jawaban <b>YA</b>, &nbsp;rujuk ke dokter.
                            </div>
                        </div>
                    </div>

                    <!-- Row: 11. Pemeriksaan tambahan + Notice Box Vasektomi -->
                    <div class="flex items-start pl-4 gap-2 mb-1">
                        <!-- Left: 11a-11d -->
                        <div class="w-[55%]">
                            <div class="font-bold leading-tight mb-0.5">11. Pemeriksaan tambahan<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(khusus untuk calon Vasektomi dan Tubektomi)</div>
                            <div class="flex justify-end pr-1 text-[7px] mb-0.5 gap-3">
                                <span>Tidak</span>
                                <span>Ya</span>
                            </div>
                            <div class="space-y-0.5 pl-6">
                                <div class="flex items-center justify-between">
                                    <span>a.&nbsp;&nbsp;&nbsp;Tanda-tanda diabetes</span>
                                    <div class="flex gap-3 pr-1">
                                        <div class="cb">{!! ($skrining && !$skrining->pemeriksaan_tambahan_diabetes) ? '√' : '' !!}</div>
                                        <div class="cb">{!! ($skrining && $skrining->pemeriksaan_tambahan_diabetes) ? '√' : '' !!}</div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>b.&nbsp;&nbsp;&nbsp;Kelainan pembekuan darah</span>
                                    <div class="flex gap-3 pr-1">
                                        <div class="cb">{!! ($skrining && !$skrining->pemeriksaan_tambahan_pembekuan_darah) ? '√' : '' !!}</div>
                                        <div class="cb">{!! ($skrining && $skrining->pemeriksaan_tambahan_pembekuan_darah) ? '√' : '' !!}</div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>c.&nbsp;&nbsp;&nbsp;Radang orchitis/epididymitis</span>
                                    <div class="flex gap-3 pr-1">
                                        <div class="cb">{!! ($skrining && !$skrining->pemeriksaan_tambahan_orchitis) ? '√' : '' !!}</div>
                                        <div class="cb">{!! ($skrining && $skrining->pemeriksaan_tambahan_orchitis) ? '√' : '' !!}</div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>d.&nbsp;&nbsp;&nbsp;Tumor/keganasan ginekologi</span>
                                    <div class="flex gap-3 pr-1">
                                        <div class="cb">{!! ($skrining && !$skrining->pemeriksaan_tambahan_tumor) ? '√' : '' !!}</div>
                                        <div class="cb">{!! ($skrining && $skrining->pemeriksaan_tambahan_tumor) ? '√' : '' !!}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Right: Notice Box Vasektomi -->
                        <div class="w-[45%] mt-6">
                            <div class="border-2 border-black p-1.5 text-[7px] leading-tight">
                                Bila semua jawaban <b>TIDAK</b>, dapat dilakukan Vasektomi. Bila salah satu jawabannya <b>YA</b>,
                                maka rujuklah ke Faskes KB/Rumah Sakit yang lengkap.
                            </div>
                        </div>
                    </div>

                    <!-- 12. Tabel Alat/obat/cara kontrasepsi yang boleh dipergunakan -->
                    <div class="pl-4 mb-0.5">
                        <div class="flex items-center gap-1 mb-0.5">
                            <span class="font-bold">12. Alat/obat/cara kontrasepsi yang boleh dipergunakan :</span>
                        </div>
                        <div class="flex border-2 border-black text-[6.5px] text-center font-semibold">
                            @php
                                $caraList = [
                                    'Suntikan 1 Bulan' => 'Suntikan 1 Bulanan',
                                    'Suntikan 3 Bulan Kombinasi' => 'Suntikan 3 Bulanan',
                                    'Pil Kombinasi' => 'Pil',
                                    'Kondom' => 'Kondom',
                                    'Implan 1 Batang' => 'Implan 1 Batang',
                                    'Implan 2 Batang' => 'Implan 2 Batang',
                                    'IUD' => 'IUD CuT 380A',
                                    'IUD Lain-lain' => 'IUD Lain-lain',
                                    'Tubektomi' => 'Tubektomi',
                                    'Vasektomi' => 'Vasektomi',
                                ];
                            @endphp
                            @foreach($caraList as $dbKey => $label)
                                <div class="flex-1 border-r border-black last:border-r-0 py-0.5 px-0.5 flex flex-col items-center justify-center min-h-[22px]">
                                    <span class="leading-tight">{{ $label }}</span>
                                </div>
                            @endforeach
                            <div class="flex items-center px-1 font-bold text-[8px]">*)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ====== BOTTOM: XV - XIX & KETERANGAN ====== -->
            <div class="text-[8px]">
                <!-- Row: XV & XVI -->
                <div class="flex justify-between mb-0.5">
                    <!-- XV. Alat/obat/cara kontrasepsi yang dipilih -->
                    <div class="w-[50%]">
                        <div class="flex items-start gap-1">
                            <span class="font-bold shrink-0">XV.&nbsp;&nbsp;&nbsp;&nbsp;Alat/obat/cara kontrasepsi yang dipilih</span>
                            <span>:</span>
                            <div class="cb-lg ml-1 mt-0.5 shrink-0">{{ $getAlokonCode($alokon->nama_alokon ?? '') }}</div>
                        </div>
                        <div class="grid grid-cols-5 gap-x-0.5 text-[6.5px] leading-tight pl-8 mt-0.5">
                            <div>1)&nbsp;Suntikan 1 Bulanan</div>
                            <div>2)&nbsp;Suntikan 3 Bulanan</div>
                            <div>3)&nbsp;Pil</div>
                            <div>4)&nbsp;Kondom</div>
                            <div>&nbsp;</div>
                            <div>5)&nbsp;Implan 1 Batang</div>
                            <div>6)&nbsp;Implan 2 Batang</div>
                            <div>7)&nbsp;IUD CuT 380A</div>
                            <div>8)&nbsp;IUD Lain-lain</div>
                            <div>&nbsp;</div>
                            <div>9)&nbsp;Tubektomi</div>
                            <div>10) Vasektomi</div>
                        </div>
                    </div>

                    <!-- XVI. Tanggal dilayani -->
                    <div class="w-[50%] pl-2">
                        <div class="flex items-center gap-1">
                            <span class="font-bold shrink-0">XVI.&nbsp;&nbsp;Tanggal dilayani</span>
                            <span class="font-bold ml-2">**)&nbsp;.......</span>
                            <div class="flex items-end gap-1 ml-2">
                                <div class="text-center">
                                    <div class="flex"><div class="cb">{{ substr($tglDilayaniD, 0, 1) }}</div><div class="cb -ml-px">{{ substr($tglDilayaniD, 1, 1) }}</div></div>
                                    <span class="text-[5.5px] italic">Tanggal</span>
                                </div>
                                <div class="text-center">
                                    <div class="flex"><div class="cb">{{ substr($tglDilayaniM, 0, 1) }}</div><div class="cb -ml-px">{{ substr($tglDilayaniM, 1, 1) }}</div></div>
                                    <span class="text-[5.5px] italic">Bulan</span>
                                </div>
                                <div class="text-center">
                                    <div class="flex"><div class="cb">{{ substr($tglDilayaniY, 0, 1) }}</div><div class="cb -ml-px">{{ substr($tglDilayaniY, 1, 1) }}</div></div>
                                    <span class="text-[5.5px] italic">Tahun</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row: XVII & XVIII -->
                <div class="flex justify-between mb-1">
                    <!-- XVII. Tanggal kunjungan ulang -->
                    <div class="w-[50%]">
                        <div class="flex items-center gap-1">
                            <span class="font-bold shrink-0">XVII.&nbsp;&nbsp;Tanggal kunjungan ulang</span>
                            <span>:</span>
                            <div class="flex items-end gap-1 ml-1">
                                <div class="text-center">
                                    <div class="flex"><div class="cb">{{ substr($tglUlangD, 0, 1) }}</div><div class="cb -ml-px">{{ substr($tglUlangD, 1, 1) }}</div></div>
                                    <span class="text-[5.5px] italic">Tanggal</span>
                                </div>
                                <div class="text-center">
                                    <div class="flex"><div class="cb">{{ substr($tglUlangM, 0, 1) }}</div><div class="cb -ml-px">{{ substr($tglUlangM, 1, 1) }}</div></div>
                                    <span class="text-[5.5px] italic">Bulan</span>
                                </div>
                                <div class="text-center">
                                    <div class="flex"><div class="cb">{{ substr($tglUlangY, 0, 1) }}</div><div class="cb -ml-px">{{ substr($tglUlangY, 1, 1) }}</div></div>
                                    <span class="text-[5.5px] italic">Tahun</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- XVIII. Tanggal dicabut -->
                    <div class="w-[50%] pl-2">
                        <div class="flex items-center gap-1">
                            <span class="font-bold shrink-0">XVIII. Tanggal dicabut</span>
                            <span class="text-[7px]">(khusus Implan/IUD)</span>
                            <span>:</span>
                            <div class="flex items-end gap-1 ml-1">
                                <div class="text-center">
                                    <div class="flex"><div class="cb">{{ substr($tglCabutD, 0, 1) }}</div><div class="cb -ml-px">{{ substr($tglCabutD, 1, 1) }}</div></div>
                                    <span class="text-[5.5px] italic">Tanggal</span>
                                </div>
                                <div class="text-center">
                                    <div class="flex"><div class="cb">{{ substr($tglCabutM, 0, 1) }}</div><div class="cb -ml-px">{{ substr($tglCabutM, 1, 1) }}</div></div>
                                    <span class="text-[5.5px] italic">Bulan</span>
                                </div>
                                <div class="text-center">
                                    <div class="flex"><div class="cb">{{ substr($tglCabutY, 0, 1) }}</div><div class="cb -ml-px">{{ substr($tglCabutY, 1, 1) }}</div></div>
                                    <span class="text-[5.5px] italic">Tahun</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row: KETERANGAN & XIX -->
                <div class="flex justify-between">
                    <!-- KETERANGAN -->
                    <div class="w-[50%] text-[7.5px] leading-tight">
                        <div class="font-bold underline mb-0.5">KETERANGAN :</div>
                        <div><b>*)</b>&nbsp;&nbsp;&nbsp;&nbsp;Coret yang tidak perlu / yang tidak boleh diberikan.</div>
                        <div><b>**)</b>&nbsp;&nbsp;&nbsp;Ditulis <b><u>gratis</u></b> untuk pelayanan tidak bayar</div>
                    </div>

                    <!-- XIX. Penanggungjawab Pelayanan KB -->
                    <div class="w-[50%] pl-4 text-center">
                        <div class="font-bold">XIX.&nbsp;&nbsp;Penanggungjawab Pelayanan KB</div>
                        <div class="text-[7.5px]">Dokter/Bidan/Perawat Kesehatan</div>
                        <div class="mt-5">
                            <div class="inline-block">
                                <span class="block">( {{ $pelayanan->penanggung_jawab_nama ?: '.............................................' }} )</span>
                                <span class="block font-bold mt-0.5">NIP. {{ $pelayanan->penanggung_jawab_nip ?: '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- ==================== HALAMAN 2: INFORMED CONSENT ======================== -->
        <!-- ========================================================================= -->
        <div class="page-sheet bg-white p-4 sm:p-5 border-2 border-black text-black leading-snug select-none shadow-xl print:shadow-none">
            
            <!-- HEADER INFORMED CONSENT -->
            <div class="border-b-2 border-black pb-2 mb-3 text-center">
                <div class="flex items-center justify-between text-[8px] font-semibold text-zinc-600 mb-1 border-b border-zinc-200 pb-0.5">
                    <span>DINAS PENGENDALIAN PENDUDUK & KELUARGA BERENCANA</span>
                    <span class="font-bold text-black">PUSKESMAS WUNDULAKO</span>
                    <span>KABUPATEN KOLAKA</span>
                </div>
                <h2 class="text-xs font-black uppercase tracking-wider font-sans">LEMBAR PERSETUJUAN TINDAKAN MEDIS (INFORMED CONSENT)</h2>
                <p class="text-[9px] font-bold text-zinc-700 uppercase">PELAYANAN KONTRASEPSI KELUARGA BERENCANA (KB)</p>
            </div>

            <!-- SECTION I: TEMPAT PELAYANAN -->
            <div class="border border-black p-2 bg-zinc-50 mb-2.5 text-[8.5px]">
                <span class="font-bold block text-[8.5px] uppercase border-b border-zinc-300 pb-0.5 mb-1">I. Tempat & Waktu Pelayanan</span>
                <div class="grid grid-cols-2 gap-2">
                    <div>Fasilitas Pelayanan Kesehatan : <b>{{ auth()->user()->instansi->nama_instansi ?? 'Puskesmas Wundulako' }}</b></div>
                    <div>Tanggal Pelayanan : <b>{{ $pelayanan->tanggal_pelayanan ? $pelayanan->tanggal_pelayanan->translatedFormat('d F Y') : date('d-m-Y') }}</b></div>
                </div>
            </div>

            <!-- SECTION II: INFORMASI TINDAKAN MEDIS -->
            <div class="border border-black p-2 mb-2.5 text-[8.5px]">
                <span class="font-bold block text-[8.5px] uppercase border-b border-zinc-300 pb-0.5 mb-1">II. Informasi Tindakan Medis Pelayanan KB</span>
                <table class="w-full text-[8px] border-collapse">
                    <tbody>
                        <tr class="border-b border-zinc-200">
                            <td class="w-40 font-semibold py-0.5">1. Tindakan Pelayanan</td>
                            <td>: Pemasangan / Pemberian <b>{{ $alokon->nama_alokon ?? 'Alat Kontrasepsi' }}</b></td>
                        </tr>
                        <tr class="border-b border-zinc-200">
                            <td class="font-semibold py-0.5">2. Indikasi Tindakan</td>
                            <td>: Menjarangkan / Mencegah Kehamilan Peserta KB</td>
                        </tr>
                        <tr class="border-b border-zinc-200">
                            <td class="font-semibold py-0.5">3. Tata Cara</td>
                            <td>: Sesuai Standar Operasional Prosedur (SOP) & Pelayanan Medis KB Nasional</td>
                        </tr>
                        <tr class="border-b border-zinc-200">
                            <td class="font-semibold py-0.5">4. Tujuan & Manfaat</td>
                            <td>: Efektivitas perlindungan dan pencegahan kehamilan secara aman & terencana</td>
                        </tr>
                        <tr class="border-b border-zinc-200">
                            <td class="font-semibold py-0.5">5. Risiko & Komplikasi</td>
                            <td>: Efek samping hormonal / reaksi lokal ringan yang telah diedukasi kepada Klien</td>
                        </tr>
                        <tr>
                            <td class="font-semibold py-0.5">6. Kunjungan Ulang</td>
                            <td>: <b>{{ $pelayanan->tanggal_kunjungan_ulang ? $pelayanan->tanggal_kunjungan_ulang->translatedFormat('d F Y') : 'Sesuai keluhan atau jadwal kontrol' }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- SECTION III: ALOKON PILIHAN -->
            <div class="border border-black p-2 mb-2.5 text-[8.5px]">
                <span class="font-bold block text-[8.5px] uppercase mb-1">III. Metode / Alokon Kontrasepsi Yang Dipilih:</span>
                <div class="grid grid-cols-4 gap-1.5 text-[8px]">
                    @foreach(['Suntikan 1 Bulan', 'Suntikan 3 Bulan Progestin', 'Pil Kombinasi', 'Kondom', 'Implan 1 Batang', 'Implan 2 Batang', 'IUD CuT 380A', 'Tubektomi'] as $opt)
                        <div class="flex items-center gap-1.5 bg-zinc-50 border border-zinc-200 p-1.5 rounded">
                            <div class="border border-black size-3.5 flex items-center justify-center font-bold text-[8px] bg-white">
                                {!! (str_contains($alokon->nama_alokon ?? '', $opt) || $alokon->nama_alokon === $opt) ? '√' : '' !!}
                            </div>
                            <span>{{ $opt }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- SECTION IV & V: PERNYATAAN KLIEN & PASANGAN -->
            <div class="grid grid-cols-2 gap-3 mb-4 text-[8.5px]">
                <!-- IV. Pernyataan Persetujuan Klien -->
                <div class="border border-black p-2.5 space-y-1">
                    <span class="font-bold block text-[8.5px] uppercase border-b border-zinc-300 pb-0.5 mb-1">IV. Pernyataan Persetujuan Klien (Istri)</span>
                    <p class="text-[8px] leading-tight">
                        Saya yang bertanda tangan di bawah ini:<br>
                        Nama : <b>{{ strtoupper($peserta->nama_lengkap) }}</b><br>
                        NIK : <b>{{ $peserta->nik }}</b><br>
                        Tgl Lahir : <b>{{ $peserta->tanggal_lahir_istri ? $peserta->tanggal_lahir_istri->format('d/m/Y') : '-' }}</b><br>
                        Alamat : <b>{{ $peserta->alamat_lengkap }}</b>
                    </p>
                    <p class="text-[7.5px] text-zinc-700 leading-tight pt-1">
                        Menyatakan telah mendapat penjelasan secara lengkap dan <b>MENGERTI SEPENUHNYA</b> perihal tindakan medis, manfaat, serta efek samping pelayanan KB. Maka secara <b>SADAR & SUKARELA</b> saya memberikan persetujuan untuk dilakukan tindakan medis pencegahan kehamilan.
                    </p>
                </div>

                <!-- V. Pernyataan Persetujuan Suami -->
                <div class="border border-black p-2.5 space-y-1">
                    <span class="font-bold block text-[8.5px] uppercase border-b border-zinc-300 pb-0.5 mb-1">V. Persetujuan Pasangan (Suami/Istri)</span>
                    <p class="text-[8px] leading-tight">
                        Saya yang bertanda tangan di bawah ini:<br>
                        Nama Suami : <b>{{ strtoupper($peserta->nama_suami_istri) }}</b><br>
                        Pekerjaan : <b>{{ $peserta->pekerjaan_suami ?? '-' }}</b><br>
                        Alamat : <b>{{ $peserta->alamat_lengkap }}</b>
                    </p>
                    <p class="text-[7.5px] text-zinc-700 leading-tight pt-1">
                        Selaku pasangan sah dari Klien, menyatakan telah memahami, menyetujui, dan memberikan dukungan penuh atas keputusan dan tindakan pelayanan kontrasepsi KB yang diberikan kepada pasangan saya.
                    </p>
                </div>
            </div>

            <!-- TANDA TANGAN 3 PIHAK -->
            <div class="border-t border-black pt-3">
                <p class="text-[8px] text-right mb-2">Wundulako, {{ $pelayanan->tanggal_pelayanan ? $pelayanan->tanggal_pelayanan->translatedFormat('d F Y') : date('d F Y') }}</p>
                <div class="grid grid-cols-3 gap-4 text-center text-[8.5px]">
                    <!-- Bidan / Petugas -->
                    <div class="flex flex-col justify-between h-28 border-r border-zinc-200 pr-2">
                        <div>
                            <p class="font-semibold">Pemberi Pelayanan / Konseling,</p>
                            <p class="text-[7.5px] text-zinc-500">Dokter / Bidan</p>
                        </div>
                        <div>
                            <p class="font-bold border-b border-black pb-0.5 text-[8.5px]">{{ $pelayanan->penanggung_jawab_nama ?: '(................................)' }}</p>
                            <p class="text-[7.5px] text-zinc-600">NIP. {{ $pelayanan->penanggung_jawab_nip ?: '........................' }}</p>
                        </div>
                    </div>

                    <!-- Peserta KB -->
                    <div class="flex flex-col justify-between h-28 border-r border-zinc-200 pr-2">
                        <div>
                            <p class="font-semibold">Klien / Peserta KB,</p>
                            <p class="text-[7.5px] text-zinc-500">Istri</p>
                        </div>
                        <div>
                            <p class="font-bold border-b border-black pb-0.5 text-[8.5px]">{{ strtoupper($peserta->nama_lengkap) }}</p>
                            <p class="text-[7.5px] text-zinc-600">Tanda Tangan</p>
                        </div>
                    </div>

                    <!-- Suami -->
                    <div class="flex flex-col justify-between h-28">
                        <div>
                            <p class="font-semibold">Suami / Pasangan Klien,</p>
                            <p class="text-[7.5px] text-zinc-500">Saksi Keluarga</p>
                        </div>
                        <div>
                            <p class="font-bold border-b border-black pb-0.5 text-[8.5px]">{{ strtoupper($peserta->nama_suami_istri) }}</p>
                            <p class="text-[7.5px] text-zinc-600">Tanda Tangan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FOOTER NOTE -->
            <div class="flex justify-between items-center text-[7.5px] text-zinc-500 mt-6 border-t border-zinc-300 pt-1">
                <span>Dokumen Rekam Medis Resmi Pelayanan KB — Puskesmas Wundulako</span>
                <span>Halaman 2 dari 2</span>
            </div>

        </div>

    </div>
</div>

<style>
/* Checkbox box helper classes */
.cb {
    border: 2px solid black;
    width: 12px;
    height: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: monospace;
    font-weight: bold;
    font-size: 8px;
    flex-shrink: 0;
}
.cb-lg {
    border: 2px solid black;
    width: 16px;
    height: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: monospace;
    font-weight: bold;
    font-size: 9px;
    flex-shrink: 0;
}

/* Base screen and printing setup for F4 / Folio (215mm x 330mm) Portrait */
@media screen {
    .page-sheet {
        width: 205mm;
        min-height: 320mm;
        max-height: 320mm;
        margin: 0 auto;
        box-sizing: border-box;
        overflow: hidden;
    }
}

@media print {
    @page {
        size: 215mm 330mm;
        margin: 4mm 5mm;
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
        color: black !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .no-print {
        display: none !important;
    }

    #print-area {
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .page-sheet {
        width: 205mm !important;
        height: 322mm !important;
        max-height: 322mm !important;
        page-break-after: always !important;
        break-after: page !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        margin: 0 auto !important;
        padding: 4mm 5mm !important;
        box-sizing: border-box !important;
        border: 2px solid black !important;
        box-shadow: none !important;
        overflow: hidden !important;
    }

    .page-sheet:last-child {
        page-break-after: auto !important;
        break-after: auto !important;
    }
}
</style>
