<div class="min-h-screen bg-zinc-100 p-4 sm:p-8 dark:bg-zinc-900 text-black">
    <!-- Float Action Buttons (Hidden on Print) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex justify-between items-center bg-white p-4 rounded-xl shadow-sm dark:bg-zinc-800 dark:border-zinc-700 border">
        <flux:button variant="outline" href="{{ route('pelayanan.show', $pelayanan->id) }}" icon="arrow-left" wire:navigate>
            Kembali ke Detail
        </flux:button>
        <div class="flex gap-2">
            <flux:button variant="primary" onclick="window.print()" icon="printer">
                Cetak / Simpan ke PDF
            </flux:button>
        </div>
    </div>

    <!-- Print Container (A4 layout styled for printing) -->
    <div class="max-w-4xl mx-auto bg-white p-8 border border-zinc-200 shadow-lg dark:shadow-none print:shadow-none print:border-none print:p-0">
        
        <!-- ==================== PAGE 1: KARTU STATUS PESERTA KB ==================== -->
        <div class="page-break space-y-6">
            <!-- Header Grid -->
            <div class="grid grid-cols-12 gap-4 items-center border-b-2 border-black pb-4">
                <div class="col-span-3 text-xs">
                    <p class="font-bold">Kode Faskes KB/Jaringan/Jejaring:</p>
                    <div class="flex gap-0.5 mt-1">
                        @for($i = 0; $i < 4; $i++)
                            <div class="border border-black size-5 flex items-center justify-center font-bold font-mono"></div>
                        @endfor
                    </div>
                </div>
                <div class="col-span-6 text-center">
                    <flux:heading size="lg" class="font-extrabold uppercase text-black dark:text-black tracking-wide">KARTU STATUS PESERTA KB</flux:heading>
                </div>
                <div class="col-span-3 text-xs justify-self-end">
                    <p class="font-bold">Nomor Induk Kependudukan (NIK):</p>
                    <div class="flex gap-0.5 mt-1">
                        @foreach(str_split(str_pad($pelayanan->pesertaKb->nik, 16, ' ')) as $char)
                            <div class="border border-black size-5 flex items-center justify-center font-bold font-mono text-[10px]">
                                {{ trim($char) }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Identitas Section -->
            <div class="grid grid-cols-2 gap-x-8 gap-y-4 text-xs">
                <div class="space-y-2">
                    <div class="grid grid-cols-3">
                        <span class="font-semibold col-span-1">Nama Peserta KB</span>
                        <span class="col-span-2">: {{ $pelayanan->pesertaKb->nama_lengkap }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="font-semibold col-span-1">Nama Suami/Istri</span>
                        <span class="col-span-2">: {{ $pelayanan->pesertaKb->nama_suami_istri }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="font-semibold col-span-1">Tanggal Lahir/Umur Istri</span>
                        <span class="col-span-2">: {{ $pelayanan->pesertaKb->tanggal_lahir_istri->translatedFormat('d-m-Y') }} / {{ $pelayanan->pesertaKb->tanggal_lahir_istri->age }} Tahun</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="font-semibold col-span-1">Alamat Peserta KB</span>
                        <span class="col-span-2">: {{ $pelayanan->pesertaKb->alamat_lengkap }}</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="grid grid-cols-3">
                        <span class="font-semibold col-span-1">Penggunaan Asuransi</span>
                        <span class="col-span-2">: 
                            [ {!! $pelayanan->pesertaKb->penggunaan_asuransi === 'bpjs' || $pelayanan->pesertaKb->penggunaan_asuransi === 'kis' ? '✓' : '&nbsp;' !!} ] 1. BPJS Kesehatan / KIS <br>
                            [ {!! $pelayanan->pesertaKb->penggunaan_asuransi !== 'bpjs' && $pelayanan->pesertaKb->penggunaan_asuransi !== 'kis' && $pelayanan->pesertaKb->penggunaan_asuransi !== 'umum' ? '✓' : '&nbsp;' !!} ] 2. Lainnya <br>
                            [ {!! $pelayanan->pesertaKb->penggunaan_asuransi === 'umum' ? '✓' : '&nbsp;' !!} ] 3. Tidak
                        </span>
                    </div>
                </div>
            </div>

            <!-- Pendidikan & Pekerjaan Table -->
            <div class="border border-black overflow-hidden text-xs">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b border-black bg-zinc-50">
                            <th class="border-r border-black p-2 text-left w-1/2">VI. Pendidikan Terakhir</th>
                            <th class="p-2 text-left w-1/2">VIII. Pekerjaan Suami dan Istri</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border-r border-black p-2 valign-top">
                                <div class="grid grid-cols-2 gap-2 text-[10px]">
                                    <div>
                                        <p class="font-bold border-b border-zinc-200 mb-1">Istri:</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pendidikan_istri === 'Tidak Sekolah' ? '✓' : '&nbsp;' !!} ] Tidak Sekolah</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pendidikan_istri === 'Tidak Tamat SD' ? '✓' : '&nbsp;' !!} ] Tidak Tamat SD</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pendidikan_istri === 'Tamat SD' ? '✓' : '&nbsp;' !!} ] Tamat SD</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pendidikan_istri === 'Tamat SLTP' ? '✓' : '&nbsp;' !!} ] Tamat SLTP</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pendidikan_istri === 'Tamat SLTA' ? '✓' : '&nbsp;' !!} ] Tamat SLTA</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pendidikan_istri === 'Tamat PT/Akademi' ? '✓' : '&nbsp;' !!} ] Tamat PT/Akademi</p>
                                    </div>
                                    <div>
                                        <p class="font-bold border-b border-zinc-200 mb-1">Suami:</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pendidikan_suami === 'Tidak Sekolah' ? '✓' : '&nbsp;' !!} ] Tidak Sekolah</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pendidikan_suami === 'Tidak Tamat SD' ? '✓' : '&nbsp;' !!} ] Tidak Tamat SD</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pendidikan_suami === 'Tamat SD' ? '✓' : '&nbsp;' !!} ] Tamat SD</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pendidikan_suami === 'Tamat SLTP' ? '✓' : '&nbsp;' !!} ] Tamat SLTP</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pendidikan_suami === 'Tamat SLTA' ? '✓' : '&nbsp;' !!} ] Tamat SLTA</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pendidikan_suami === 'Tamat PT/Akademi' ? '✓' : '&nbsp;' !!} ] Tamat PT/Akademi</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-2 valign-top">
                                <div class="grid grid-cols-2 gap-2 text-[10px]">
                                    <div>
                                        <p class="font-bold border-b border-zinc-200 mb-1">Istri:</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pekerjaan_istri === 'Tidak Bekerja' ? '✓' : '&nbsp;' !!} ] Tidak Bekerja</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pekerjaan_istri === 'Petani' ? '✓' : '&nbsp;' !!} ] Petani</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pekerjaan_istri === 'Nelayan' ? '✓' : '&nbsp;' !!} ] Nelayan</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pekerjaan_istri === 'Pedagang' ? '✓' : '&nbsp;' !!} ] Pedagang</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pekerjaan_istri === 'PNS/TNI/Polri' ? '✓' : '&nbsp;' !!} ] PNS/TNI/Polri</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pekerjaan_istri === 'Pegawai Swasta' ? '✓' : '&nbsp;' !!} ] Pegawai Swasta</p>
                                    </div>
                                    <div>
                                        <p class="font-bold border-b border-zinc-200 mb-1">Suami:</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pekerjaan_suami === 'Tidak Bekerja' ? '✓' : '&nbsp;' !!} ] Tidak Bekerja</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pekerjaan_suami === 'Petani' ? '✓' : '&nbsp;' !!} ] Petani</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pekerjaan_suami === 'Nelayan' ? '✓' : '&nbsp;' !!} ] Nelayan</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pekerjaan_suami === 'Pedagang' ? '✓' : '&nbsp;' !!} ] Pedagang</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pekerjaan_suami === 'PNS/TNI/Polri' ? '✓' : '&nbsp;' !!} ] PNS/TNI/Polri</p>
                                        <p>[ {!! $pelayanan->pesertaKb->pekerjaan_suami === 'Pegawai Swasta' ? '✓' : '&nbsp;' !!} ] Swasta/Lainnya</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- X. Jumlah Anak, XI. Umur Anak, XII. Status Peserta -->
            <div class="grid grid-cols-3 gap-4 text-[10px] border border-black p-2">
                <div>
                    <span class="font-bold block">X. Jumlah Anak Hidup:</span>
                    <span>Laki-laki: <b>{{ $pelayanan->pesertaKb->jumlah_anak_laki }}</b> anak</span><br>
                    <span>Perempuan: <b>{{ $pelayanan->pesertaKb->jumlah_anak_perempuan }}</b> anak</span>
                </div>
                <div>
                    <span class="font-bold block">XI. Umur Anak Terakhir:</span>
                    <span>
                        @if($pelayanan->pesertaKb->umur_anak_terakhir)
                            <b>{{ floor($pelayanan->pesertaKb->umur_anak_terakhir / 12) }}</b> Tahun 
                            <b>{{ $pelayanan->pesertaKb->umur_anak_terakhir % 12 }}</b> Bulan
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div>
                    <span class="font-bold block">XII. Status Peserta KB:</span>
                    <p>[ {!! $pelayanan->pesertaKb->status_kepesertaan === 'baru' ? '✓' : '&nbsp;' !!} ] 1. Peserta KB Baru</p>
                    <p>[ {!! $pelayanan->pesertaKb->status_kepesertaan === 'ganti_cara' ? '✓' : '&nbsp;' !!} ] 2. Peserta KB Ganti Cara</p>
                    <p>[ {!! $pelayanan->pesertaKb->status_kepesertaan === 'ulangan' ? '✓' : '&nbsp;' !!} ] 3. Peserta KB Ulangan</p>
                </div>
            </div>

            <!-- XIII. KB Terakhir -->
            <div class="border border-black p-2 text-[10px]">
                <span class="font-bold block mb-1">XIII. Alat/Obat/Cara KB Terakhir Yang Masih Hidup / Dipakai:</span>
                <div class="grid grid-cols-4 gap-2">
                    <p>[ {!! $pelayanan->pesertaKb->kb_terakhir === 'Suntikan 1 Bulan' ? '✓' : '&nbsp;' !!} ] Suntikan 1 Bulan</p>
                    <p>[ {!! $pelayanan->pesertaKb->kb_terakhir === 'Suntikan 3 Bulan Kombinasi' ? '✓' : '&nbsp;' !!} ] Suntikan 3 Bulan Komb</p>
                    <p>[ {!! $pelayanan->pesertaKb->kb_terakhir === 'Suntikan 3 Bulan Progestin' ? '✓' : '&nbsp;' !!} ] Suntikan 3 Bulan Prog</p>
                    <p>[ {!! $pelayanan->pesertaKb->kb_terakhir === 'Pil Kombinasi' ? '✓' : '&nbsp;' !!} ] Pil Kombinasi</p>
                    <p>[ {!! $pelayanan->pesertaKb->kb_terakhir === 'Pil Progestin' ? '✓' : '&nbsp;' !!} ] Pil Progestin</p>
                    <p>[ {!! $pelayanan->pesertaKb->kb_terakhir === 'Kondom' ? '✓' : '&nbsp;' !!} ] Kondom</p>
                    <p>[ {!! $pelayanan->pesertaKb->kb_terakhir === 'Implan 1 Batang' ? '✓' : '&nbsp;' !!} ] Implan 1 Batang</p>
                    <p>[ {!! $pelayanan->pesertaKb->kb_terakhir === 'Implan 2 Batang' ? '✓' : '&nbsp;' !!} ] Implan 2 Batang</p>
                    <p>[ {!! $pelayanan->pesertaKb->kb_terakhir === 'IUD' ? '✓' : '&nbsp;' !!} ] IUD / AKDR</p>
                    <p>[ {!! $pelayanan->pesertaKb->kb_terakhir === 'Tubektomi' ? '✓' : '&nbsp;' !!} ] Tubektomi</p>
                    <p>[ {!! $pelayanan->pesertaKb->kb_terakhir === 'Vasektomi' ? '✓' : '&nbsp;' !!} ] Vasektomi</p>
                </div>
            </div>

            <!-- XIV. Penapisan (Skrining) -->
            <div class="border border-black text-[10px] space-y-2 overflow-hidden">
                <div class="bg-zinc-50 p-2 font-bold border-b border-black">
                    XIV. Penapisan (Skrining) Untuk Menentukan Alat Kontrasepsi Yang Dapat Digunakan
                </div>
                
                <div class="grid grid-cols-2 gap-4 p-2">
                    <!-- Left: Anamnese -->
                    <div class="space-y-2">
                        <p class="font-bold border-b pb-0.5">Anamnese</p>
                        <p>1. Haid Terakhir Tanggal: <b>{{ $pelayanan->skriningMedis->haid_terakhir ? $pelayanan->skriningMedis->haid_terakhir->translatedFormat('d-m-Y') : '-' }}</b></p>
                        <p>2. Hamil/Diduga Hamil: <b>{{ $pelayanan->skriningMedis->hamil_diduga_hamil ? 'Ya' : 'Tidak' }}</b></p>
                        <p>3. GPA: Gravida: <b>{{ substr($pelayanan->skriningMedis->gravida_partus_abortus, 1, 1) ?: '-' }}</b>, Partus: <b>{{ substr($pelayanan->skriningMedis->gravida_partus_abortus, 3, 1) ?: '-' }}</b>, Abortus: <b>{{ substr($pelayanan->skriningMedis->gravida_partus_abortus, 5, 1) ?: '-' }}</b></p>
                        <p>4. Menyusui: <b>{{ $pelayanan->skriningMedis->status_menyusui ? 'Ya' : 'Tidak' }}</b></p>
                        
                        <div class="space-y-1">
                            <p class="font-semibold">5. Riwayat Penyakit Sebelumnya:</p>
                            <p>[ {!! $pelayanan->skriningMedis->rwyt_sakit_kuning ? '✓' : '&nbsp;' !!} ] Sakit kuning</p>
                            <p>[ {!! $pelayanan->skriningMedis->rwyt_pendarahan ? '✓' : '&nbsp;' !!} ] Pendarahan pervaginam</p>
                            <p>[ {!! $pelayanan->skriningMedis->rwyt_keputihan ? '✓' : '&nbsp;' !!} ] Keputihan yang lama</p>
                            <p>[ {!! $pelayanan->skriningMedis->rwyt_tumor ? '✓' : '&nbsp;' !!} ] Tumor Payudara/Rahim/Indung Telur</p>
                        </div>
                    </div>

                    <!-- Right: Pemeriksaan Fisik -->
                    <div class="space-y-2">
                        <p class="font-bold border-b pb-0.5">Pemeriksaan Fisik & Dalam</p>
                        <p>6. Keadaan Umum: <span class="capitalize font-bold">{{ $pelayanan->skriningMedis->fisik_keadaan_umum ?? '-' }}</span></p>
                        <p>7. Berat Badan: <b>{{ $pelayanan->skriningMedis->fisik_berat_badan ?? '-' }}</b> Kg</p>
                        <p>8. Tekanan Darah: <b>{{ $pelayanan->skriningMedis->fisik_tekanan_darah ?? '-' }}</b> mmHg</p>
                        
                        <div class="space-y-1">
                            <p class="font-semibold">9. Pemeriksaan Dalam (Khusus IUD/MOW):</p>
                            <p>Tanda Radang: <b>{{ $pelayanan->skriningMedis->pemeriksaan_dalam_radang ? 'Ya' : 'Tidak' }}</b></p>
                            <p>Tumor Ginekologi: <b>{{ $pelayanan->skriningMedis->pemeriksaan_dalam_tumor ? 'Ya' : 'Tidak' }}</b></p>
                            <p>10. Posisi Rahim: <span class="capitalize font-bold">{{ $pelayanan->skriningMedis->posisi_rahim ?? '-' }}</span></p>
                        </div>

                        <div class="space-y-1">
                            <p class="font-semibold">11. Pemeriksaan Tambahan (Vasektomi/MOW):</p>
                            <p>Diabetes: <b>{{ $pelayanan->skriningMedis->pemeriksaan_tambahan_diabetes ? 'Ya' : 'Tidak' }}</b></p>
                            <p>Pembekuan Darah: <b>{{ $pelayanan->skriningMedis->pemeriksaan_tambahan_pembekuan_darah ? 'Ya' : 'Tidak' }}</b></p>
                            <p>Radang Orchitis: <b>{{ $pelayanan->skriningMedis->pemeriksaan_tambahan_orchitis ? 'Ya' : 'Tidak' }}</b></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- XV. Alat Kontrasepsi Terpilih & Jadwal -->
            <div class="border border-black text-[10px] divide-y divide-black">
                <div class="p-2">
                    <span class="font-bold block mb-1">XV. Alat/obat/cara kontrasepsi yang boleh dipergunakan & dipilih:</span>
                    <div class="grid grid-cols-4 gap-2">
                        @php
                            $bolehArr = json_decode($pelayanan->skriningMedis->alat_kontrasepsi_boleh_digunakan, true) ?? [];
                        @endphp
                        @foreach(['Suntikan 1 Bulan', 'Suntikan 3 Bulan Kombinasi', 'Suntikan 3 Bulan Progestin', 'Pil Kombinasi', 'Pil Progestin', 'Kondom', 'Implan 1 Batang', 'Implan 2 Batang', 'IUD', 'Tubektomi', 'Vasektomi'] as $cara)
                            <p>[ {!! in_array($cara, $bolehArr) ? '✓' : '&nbsp;' !!} ] {{ $cara }}</p>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-3 divide-x divide-black p-2 font-semibold">
                    <div>XVI. Tanggal Dilayani: {{ $pelayanan->tanggal_pelayanan->translatedFormat('d-m-Y') }}</div>
                    <div>XVII. Kunjungan Ulang: {{ $pelayanan->tanggal_kunjungan_ulang ? $pelayanan->tanggal_kunjungan_ulang->translatedFormat('d-m-Y') : '-' }}</div>
                    <div>XVIII. Tanggal Dicabut: {{ $pelayanan->tanggal_dicabut ? $pelayanan->tanggal_dicabut->translatedFormat('d-m-Y') : '-' }}</div>
                </div>
            </div>

            <!-- Sign Off -->
            <div class="flex justify-end text-xs pt-4">
                <div class="text-center w-64 space-y-12">
                    <div>
                        <p class="font-semibold">Penanggungjawab Pelayanan KB,</p>
                        <p class="text-[10px] text-zinc-500 capitalize">({{ $pelayanan->penanggung_jawab_jabatan }})</p>
                    </div>
                    <div>
                        <p class="font-bold border-b border-black pb-0.5">{{ $pelayanan->penanggung_jawab_nama }}</p>
                        <p class="text-[10px]">NIP. {{ $pelayanan->penanggung_jawab_nip ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== PAGE 2: INFORMED CONSENT ==================== -->
        <div class="mt-8 border-t-2 border-dashed border-zinc-400 pt-8 print:mt-0 print:border-none print:pt-0">
            <div class="space-y-6">
                <!-- Header -->
                <div class="text-center border-b-2 border-black pb-4">
                    <flux:heading size="md" class="font-black text-black dark:text-black uppercase">LEMBAR PERSETUJUAN TINDAKAN MEDIK (INFORMED CONSENT)</flux:heading>
                    <flux:heading size="sm" class="text-zinc-650 dark:text-zinc-650 font-bold uppercase mt-1">PELAYANAN KELUARGA BERENCANA (KB)</flux:heading>
                </div>

                <!-- Identitas Faskes -->
                <div class="border border-black p-3 text-xs space-y-1">
                    <p class="font-bold uppercase tracking-wider text-[10px] text-zinc-600 mb-1">IDENTITAS TEMPAT PELAYANAN</p>
                    <div class="grid grid-cols-2 gap-4">
                        <p>Nama Faskes: <b>{{ auth()->user()->instansi->nama_instansi }}</b></p>
                        <p>Kode Faskes: <b>-</b></p>
                    </div>
                </div>

                <!-- Pernyataan Persetujuan Klien -->
                <div class="space-y-3 text-xs">
                    <p class="font-bold border-b pb-0.5">PERSETUJUAN KLIEN</p>
                    <p>Saya yang bertanda tangan di bawah ini:</p>
                    <div class="space-y-1 pl-4">
                        <div class="grid grid-cols-4"><span class="font-semibold">Nama Klien (Istri)</span><span class="col-span-3">: {{ $pelayanan->pesertaKb->nama_lengkap }}</span></div>
                        <div class="grid grid-cols-4"><span class="font-semibold">Nomor NIK Kependudukan</span><span class="col-span-3">: {{ $pelayanan->pesertaKb->nik }}</span></div>
                    </div>

                    <p class="leading-relaxed">
                        Setelah mendapat penjelasan dan <b>MENGERTI SEPENUHNYA</b> perihal alat/obat/cara kontrasepsi yang saya pilih di bawah ini, maka saya secara <b>SUKARELA</b> memberikan persetujuan untuk dilakukan tindakan medis pencegahan kehamilan berupa:
                    </p>

                    <!-- Checkbox Selected Alokon -->
                    <div class="grid grid-cols-3 gap-2 bg-zinc-50 border border-zinc-200 p-4 rounded-lg">
                        @foreach(['Suntikan 1 Bulan', 'Suntikan 3 Bulan Kombinasi', 'Suntikan 3 Bulan Progestin', 'Implan 1 Batang', 'Implan 2 Batang', 'IUD', 'Tubektomi', 'Vasektomi'] as $cara)
                            <p>[ {!! $pelayanan->alokon->nama_alokon === $cara || str_contains($pelayanan->alokon->nama_alokon, $cara) ? '✓' : '&nbsp;' !!} ] {{ $cara }}</p>
                        @endforeach
                    </div>
                </div>

                <!-- Pernyataan Persetujuan Pasangan -->
                <div class="space-y-3 text-xs">
                    <p class="font-bold border-b pb-0.5">PERSETUJUAN SUAMI / ISTRI KLIEN</p>
                    <p>Saya yang bertanda tangan di bawah ini:</p>
                    <div class="space-y-1 pl-4">
                        <div class="grid grid-cols-4"><span class="font-semibold">Nama Suami/Pasangan</span><span class="col-span-3">: {{ $pelayanan->pesertaKb->nama_suami_istri }}</span></div>
                    </div>
                    <p class="leading-relaxed">
                        Selaku Suami/Pasangan resmi dari Klien, menyatakan menyetujui dan memberikan dukungan penuh atas pilihan kontrasepsi tersebut demi kesehatan reproduksi keluarga kami.
                    </p>
                </div>

                <!-- Signatures Grid -->
                <div class="grid grid-cols-3 gap-4 text-xs text-center pt-8">
                    <!-- Counselor -->
                    <div class="space-y-16">
                        <div>
                            <p class="font-semibold">Yang memberi pelayanan konseling,</p>
                            <p class="text-[10px] text-zinc-500">({{ $pelayanan->penanggung_jawab_jabatan }})</p>
                        </div>
                        <div>
                            <p class="font-bold border-b border-black pb-0.5">{{ $pelayanan->penanggung_jawab_nama }}</p>
                            <p class="text-[10px]">NIP. {{ $pelayanan->penanggung_jawab_nip ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Client -->
                    <div class="space-y-16">
                        <div>
                            <p class="font-semibold">Klien,</p>
                        </div>
                        <div>
                            <p class="font-bold border-b border-black pb-0.5">{{ $pelayanan->pesertaKb->nama_lengkap }}</p>
                            <p class="text-[10px]">Peserta KB</p>
                        </div>
                    </div>

                    <!-- Spouse -->
                    <div class="space-y-16">
                        <div>
                            <p class="font-semibold">Suami/Istri Klien,</p>
                        </div>
                        <div>
                            <p class="font-bold border-b border-black pb-0.5">{{ $pelayanan->pesertaKb->nama_suami_istri }}</p>
                            <p class="text-[10px]">Saksi Utama</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
@media print {
    /* Set page settings */
    @page {
        size: A4;
        margin: 1.5cm;
    }
    body {
        background-color: white !important;
        color: black !important;
    }
    .no-print {
        display: none !important;
    }
    .page-break {
        page-break-after: always;
        break-after: page;
    }
}
</style>
