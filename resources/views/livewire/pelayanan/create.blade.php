<div class="flex-1 space-y-6 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div>
            <flux:heading size="xl" level="1">Pelayanan KB Baru</flux:heading>
            <flux:text size="sm">Registrasi pelayanan kontrasepsi melalui wizard 3-langkah</flux:text>
        </div>
        <flux:button variant="outline" href="{{ route('pelayanan.index') }}" icon="arrow-left" wire:navigate>
            Batal
        </flux:button>
    </div>

    <!-- Step Indicator -->
    <flux:card class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between max-w-lg mx-auto">
            <!-- Step 1 -->
            <div class="flex flex-col items-center">
                <div class="flex size-8 items-center justify-center rounded-full text-xs font-bold 
                    {{ $currentStep === 1 ? 'bg-blue-600 text-white' : ($currentStep > 1 ? 'bg-emerald-500 text-white' : 'bg-zinc-100 text-zinc-500 dark:bg-zinc-800') }}">
                    @if($currentStep > 1)
                        <flux:icon name="check" class="size-4" />
                    @else
                        1
                    @endif
                </div>
                <span class="mt-2 text-2xs font-semibold {{ $currentStep === 1 ? 'text-blue-600' : 'text-zinc-500' }}">Skrining Medis</span>
            </div>

            <!-- Line -->
            <div class="h-0.5 flex-1 mx-2 {{ $currentStep > 1 ? 'bg-emerald-500' : 'bg-zinc-200 dark:bg-zinc-700' }}"></div>

            <!-- Step 2 -->
            <div class="flex flex-col items-center">
                <div class="flex size-8 items-center justify-center rounded-full text-xs font-bold 
                    {{ $currentStep === 2 ? 'bg-blue-600 text-white' : ($currentStep > 2 ? 'bg-emerald-500 text-white' : 'bg-zinc-100 text-zinc-500 dark:bg-zinc-800') }}">
                    @if($currentStep > 2)
                        <flux:icon name="check" class="size-4" />
                    @else
                        2
                    @endif
                </div>
                <span class="mt-2 text-2xs font-semibold {{ $currentStep === 2 ? 'text-blue-600' : 'text-zinc-500' }}">Informed Consent</span>
            </div>

            <!-- Line -->
            <div class="h-0.5 flex-1 mx-2 {{ $currentStep > 2 ? 'bg-emerald-500' : 'bg-zinc-200 dark:bg-zinc-700' }}"></div>

            <!-- Step 3 -->
            <div class="flex flex-col items-center">
                <div class="flex size-8 items-center justify-center rounded-full text-xs font-bold 
                    {{ $currentStep === 3 ? 'bg-blue-600 text-white' : 'bg-zinc-100 text-zinc-500 dark:bg-zinc-800' }}">
                    3
                </div>
                <span class="mt-2 text-2xs font-semibold {{ $currentStep === 3 ? 'text-blue-600' : 'text-zinc-500' }}">Pemberian Alokon</span>
            </div>
        </div>
    </flux:card>

    <!-- Step Contents -->
    <flux:card class="max-w-4xl mx-auto">
        <!-- ==================== STEP 1: SKRINING MEDIS ==================== -->
        @if($currentStep === 1)
            <div class="space-y-8">
                <div class="border-b border-zinc-100 dark:border-zinc-800 pb-3">
                    <flux:heading size="lg">Langkah 1: Profil Peserta & Skrining Medis</flux:heading>
                    <flux:text size="sm">Pilih peserta terverifikasi, lengkapi profil sosial/keluarga, dan catat riwayat medis skrining.</flux:text>
                </div>

                <!-- Warning if not eligible -->
                @if(!$isLayak)
                    <div class="rounded-lg bg-rose-50 border border-rose-200 p-4 text-sm text-rose-700 dark:bg-rose-950/30 dark:border-rose-900 dark:text-rose-400">
                        <div class="flex gap-2">
                            <flux:icon name="exclamation-triangle" class="size-5 shrink-0" />
                            <span>{{ $medicalWarningMessage }}</span>
                        </div>
                    </div>
                @endif

                <!-- A. Identitas Utama -->
                <div class="space-y-4">
                    <flux:heading size="md" class="text-blue-600 dark:text-blue-400 font-bold">A. Identitas Utama & Pemilihan Peserta</flux:heading>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <!-- Pilih Peserta -->
                        <flux:field>
                            <flux:label>Pilih Peserta KB</flux:label>
                            <flux:select wire:model.live="peserta_kb_id">
                                <option value="">Pilih Peserta</option>
                                @foreach($pesertas as $peserta)
                                    <option value="{{ $peserta->id }}">{{ $peserta->nama_lengkap }} (NIK: {{ $peserta->nik }})</option>
                                @endforeach
                            </flux:select>
                            <flux:error name="peserta_kb_id" />
                        </flux:field>

                        <!-- NIK (Readonly) -->
                        <flux:field>
                            <flux:label>NIK Peserta</flux:label>
                            <flux:input type="text" wire:model="nik" readonly class="bg-zinc-50 dark:bg-zinc-800" />
                        </flux:field>
                    </div>
                </div>

                @if($peserta_kb_id)
                    <flux:separator />

                    <!-- B. Profil Sosial & Keluarga -->
                    <div class="space-y-4">
                        <flux:heading size="md" class="text-blue-600 dark:text-blue-400 font-bold">B. Profil Pendidikan, Pekerjaan & Keluarga</flux:heading>
                        
                        <div class="grid gap-4 sm:grid-cols-2">
                            <flux:field>
                                <flux:label>Pendidikan Terakhir Istri</flux:label>
                                <flux:select wire:model="pendidikan_istri">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="Tidak Sekolah">Tidak Sekolah</option>
                                    <option value="Tidak Tamat SD">Tidak Tamat SD/Sederajat</option>
                                    <option value="Tamat SD">Tamat SD/Sederajat</option>
                                    <option value="Tamat SLTP">Tamat SLTP/Sederajat</option>
                                    <option value="Tamat SLTA">Tamat SLTA/Sederajat</option>
                                    <option value="Tamat PT/Akademi">Tamat PT/Akademi</option>
                                </flux:select>
                                <flux:error name="pendidikan_istri" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Pendidikan Terakhir Suami</flux:label>
                                <flux:select wire:model="pendidikan_suami">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="Tidak Sekolah">Tidak Sekolah</option>
                                    <option value="Tidak Tamat SD">Tidak Tamat SD/Sederajat</option>
                                    <option value="Tamat SD">Tamat SD/Sederajat</option>
                                    <option value="Tamat SLTP">Tamat SLTP/Sederajat</option>
                                    <option value="Tamat SLTA">Tamat SLTA/Sederajat</option>
                                    <option value="Tamat PT/Akademi">Tamat PT/Akademi</option>
                                </flux:select>
                                <flux:error name="pendidikan_suami" />
                            </flux:field>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <flux:field>
                                <flux:label>Pekerjaan Istri</flux:label>
                                <flux:select wire:model="pekerjaan_istri">
                                    <option value="">Pilih Pekerjaan</option>
                                    <option value="Tidak Bekerja">Tidak Bekerja</option>
                                    <option value="Petani">Petani</option>
                                    <option value="Nelayan">Nelayan</option>
                                    <option value="Pedagang">Pedagang</option>
                                    <option value="Pejabat Negara">Pejabat Negara</option>
                                    <option value="PNS/TNI/Polri">PNS/TNI/Polri</option>
                                    <option value="Pegawai Swasta">Pegawai Swasta</option>
                                    <option value="Wiraswasta">Wiraswasta</option>
                                    <option value="Pensiunan">Pensiunan</option>
                                    <option value="Pekerja Lepas">Pekerja Lepas</option>
                                </flux:select>
                                <flux:error name="pekerjaan_istri" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Pekerjaan Suami</flux:label>
                                <flux:select wire:model="pekerjaan_suami">
                                    <option value="">Pilih Pekerjaan</option>
                                    <option value="Tidak Bekerja">Tidak Bekerja</option>
                                    <option value="Petani">Petani</option>
                                    <option value="Nelayan">Nelayan</option>
                                    <option value="Pedagang">Pedagang</option>
                                    <option value="Pejabat Negara">Pejabat Negara</option>
                                    <option value="PNS/TNI/Polri">PNS/TNI/Polri</option>
                                    <option value="Pegawai Swasta">Pegawai Swasta</option>
                                    <option value="Wiraswasta">Wiraswasta</option>
                                    <option value="Pensiunan">Pensiunan</option>
                                    <option value="Pekerja Lepas">Pekerja Lepas</option>
                                </flux:select>
                                <flux:error name="pekerjaan_suami" />
                            </flux:field>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <flux:field>
                                <flux:label>Jumlah Anak Hidup (Laki-laki)</flux:label>
                                <flux:input type="number" min="0" wire:model="jumlah_anak_laki" />
                                <flux:error name="jumlah_anak_laki" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Jumlah Anak Hidup (Perempuan)</flux:label>
                                <flux:input type="number" min="0" wire:model="jumlah_anak_perempuan" />
                                <flux:error name="jumlah_anak_perempuan" />
                            </flux:field>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <flux:field>
                                <flux:label>Status Kepesertaan KB</flux:label>
                                <flux:select wire:model="status_kepesertaan">
                                    <option value="baru">Peserta KB Baru</option>
                                    <option value="ganti_cara">Peserta KB Ganti Cara</option>
                                    <option value="ulangan">Peserta KB Ulangan</option>
                                </flux:select>
                                <flux:error name="status_kepesertaan" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Alat/Obat/Cara KB Terakhir</flux:label>
                                <flux:select wire:model="kb_terakhir">
                                    <option value="">Pilih Cara Terakhir (Kosongkan jika baru)</option>
                                    <option value="Suntikan 1 Bulan">Suntikan 1 Bulan</option>
                                    <option value="Suntikan 3 Bulan Kombinasi">Suntikan 3 Bulan Kombinasi</option>
                                    <option value="Suntikan 3 Bulan Progestin">Suntikan 3 Bulan Progestin</option>
                                    <option value="Pil Kombinasi">Pil Kombinasi</option>
                                    <option value="Pil Progestin">Pil Progestin</option>
                                    <option value="Kondom">Kondom</option>
                                    <option value="Implan 1 Batang">Implan 1 Batang</option>
                                    <option value="Implan 2 Batang">Implan 2 Batang</option>
                                    <option value="IUD">IUD</option>
                                    <option value="Tubektomi">Tubektomi</option>
                                    <option value="Vasektomi">Vasektomi</option>
                                </flux:select>
                                <flux:error name="kb_terakhir" />
                            </flux:field>
                        </div>
                    </div>

                    <flux:separator />

                    <!-- C. Anamnese (Pemeriksaan Skrining) -->
                    <div class="space-y-4">
                        <flux:heading size="md" class="text-blue-600 dark:text-blue-400 font-bold">C. Anamnese & Skrining Awal</flux:heading>
                        
                        <div class="grid gap-4 sm:grid-cols-3">
                            <flux:field>
                                <flux:label>Tanggal Skrining</flux:label>
                                <flux:input type="date" wire:model="tanggal_skrining" />
                                <flux:error name="tanggal_skrining" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Tanggal Haid Terakhir</flux:label>
                                <flux:input type="date" wire:model="haid_terakhir" />
                                <flux:error name="haid_terakhir" />
                            </flux:field>

                            <flux:field>
                                <flux:label>GPA (Gravida/Partus/Abortus)</flux:label>
                                <flux:input type="text" placeholder="Contoh: G2P1A0" wire:model="gravida_partus_abortus" />
                                <flux:error name="gravida_partus_abortus" />
                            </flux:field>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <flux:field>
                                <flux:label>Hamil / Diduga Hamil</flux:label>
                                <flux:select wire:model="hamil_diduga_hamil">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </flux:select>
                                <flux:error name="hamil_diduga_hamil" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Sedang Menyusui</flux:label>
                                <flux:select wire:model="status_menyusui">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </flux:select>
                                <flux:error name="status_menyusui" />
                            </flux:field>
                        </div>

                        <!-- Checkboxes Penyakit -->
                        <div class="rounded-lg bg-zinc-50 border border-zinc-150 p-4 dark:bg-zinc-800/30 dark:border-zinc-800 space-y-3">
                            <flux:label class="font-semibold block">Riwayat Penyakit Pasien</flux:label>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <flux:checkbox wire:model.live="rwyt_sakit_kuning" :label="__('Sakit kuning / Gangguan Hati')" />
                                <flux:checkbox wire:model.live="rwyt_pendarahan" :label="__('Pendarahan pervaginam tidak diketahui sebabnya')" />
                                <flux:checkbox wire:model.live="rwyt_keputihan" :label="__('Keputihan yang lama')" />
                                <flux:checkbox wire:model.live="rwyt_tumor" :label="__('Tumor (Payudara, Rahim, atau Indung Telur)')" />
                            </div>
                        </div>
                    </div>

                    <flux:separator />

                    <!-- D. Pemeriksaan Fisik & Keadaan Umum -->
                    <div class="space-y-4">
                        <flux:heading size="md" class="text-blue-600 dark:text-blue-400 font-bold">D. Pemeriksaan Fisik & Keadaan Umum</flux:heading>
                        
                        <div class="grid gap-4 sm:grid-cols-3">
                            <flux:field>
                                <flux:label>Keadaan Umum</flux:label>
                                <flux:select wire:model.live="fisik_keadaan_umum">
                                    <option value="baik">Baik</option>
                                    <option value="sedang">Sedang</option>
                                    <option value="kurang">Kurang</option>
                                    <option value="lemah">Lemah / Sakit</option>
                                </flux:select>
                                <flux:error name="fisik_keadaan_umum" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Berat Badan (Kg)</flux:label>
                                <flux:input type="number" step="0.1" placeholder="0.0" wire:model="fisik_berat_badan" />
                                <flux:error name="fisik_berat_badan" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Tekanan Darah (mmHg)</flux:label>
                                <flux:input type="text" placeholder="Contoh: 120/80" wire:model="fisik_tekanan_darah" />
                                <flux:error name="fisik_tekanan_darah" />
                            </flux:field>
                        </div>
                    </div>

                    <flux:separator />

                    <!-- E. Pemeriksaan Dalam (Khusus IUD / Tubektomi / Vasektomi) -->
                    <div class="space-y-4">
                        <flux:heading size="md" class="text-blue-600 dark:text-blue-400 font-bold">E. Pemeriksaan Dalam & Posisi Rahim</flux:heading>
                        
                        <div class="grid gap-4 sm:grid-cols-3">
                            <flux:field>
                                <flux:label>Tanda-tanda Radang</flux:label>
                                <flux:select wire:model="pemeriksaan_dalam_radang">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </flux:select>
                                <flux:error name="pemeriksaan_dalam_radang" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Tumor / Keganasan Ginekologi</flux:label>
                                <flux:select wire:model="pemeriksaan_dalam_tumor">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </flux:select>
                                <flux:error name="pemeriksaan_dalam_tumor" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Posisi Rahim</flux:label>
                                <flux:select wire:model="posisi_rahim">
                                    <option value="normal">Normal</option>
                                    <option value="antaflexi">Antefleksi</option>
                                    <option value="retroflexi">Retrofleksi</option>
                                </flux:select>
                                <flux:error name="posisi_rahim" />
                            </flux:field>
                        </div>
                    </div>

                    <flux:separator />

                    <!-- F. Pemeriksaan Tambahan (Khusus Vasektomi & Tubektomi) -->
                    <div class="space-y-4">
                        <flux:heading size="md" class="text-blue-600 dark:text-blue-400 font-bold">F. Pemeriksaan Tambahan</flux:heading>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <flux:field>
                                <flux:label>Tanda-tanda Diabetes</flux:label>
                                <flux:select wire:model="pemeriksaan_tambahan_diabetes">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </flux:select>
                                <flux:error name="pemeriksaan_tambahan_diabetes" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Kelainan Pembekuan Darah</flux:label>
                                <flux:select wire:model="pemeriksaan_tambahan_pembekuan_darah">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </flux:select>
                                <flux:error name="pemeriksaan_tambahan_pembekuan_darah" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Radang Orchitis / Epididymitis</flux:label>
                                <flux:select wire:model="pemeriksaan_tambahan_orchitis">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </flux:select>
                                <flux:error name="pemeriksaan_tambahan_orchitis" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Tumor Ginekologi Tambahan</flux:label>
                                <flux:select wire:model="pemeriksaan_tambahan_tumor">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </flux:select>
                                <flux:error name="pemeriksaan_tambahan_tumor" />
                            </flux:field>
                        </div>
                    </div>

                    <flux:separator />

                    <!-- G. Alat Kontrasepsi yang Boleh Dipergunakan -->
                    <div class="space-y-3">
                        <flux:label class="font-bold block text-blue-600 dark:text-blue-400">G. Alat Kontrasepsi yang Boleh Dipergunakan</flux:label>
                        <div class="grid gap-4 sm:grid-cols-3 bg-zinc-50 border border-zinc-150 p-4 rounded-xl dark:bg-zinc-800/30 dark:border-zinc-800">
                            <flux:checkbox wire:model="alat_kontrasepsi_boleh_digunakan" value="Suntikan 1 Bulan" label="Suntikan 1 Bulan" />
                            <flux:checkbox wire:model="alat_kontrasepsi_boleh_digunakan" value="Suntikan 3 Bulan Kombinasi" label="Suntikan 3 Bulan Kombinasi" />
                            <flux:checkbox wire:model="alat_kontrasepsi_boleh_digunakan" value="Suntikan 3 Bulan Progestin" label="Suntikan 3 Bulan Progestin" />
                            <flux:checkbox wire:model="alat_kontrasepsi_boleh_digunakan" value="Pil Kombinasi" label="Pil Kombinasi" />
                            <flux:checkbox wire:model="alat_kontrasepsi_boleh_digunakan" value="Pil Progestin" label="Pil Progestin" />
                            <flux:checkbox wire:model="alat_kontrasepsi_boleh_digunakan" value="Kondom" label="Kondom" />
                            <flux:checkbox wire:model="alat_kontrasepsi_boleh_digunakan" value="Implan 1 Batang" label="Implan 1 Batang" />
                            <flux:checkbox wire:model="alat_kontrasepsi_boleh_digunakan" value="Implan 2 Batang" label="Implan 2 Batang" />
                            <flux:checkbox wire:model="alat_kontrasepsi_boleh_digunakan" value="IUD" label="IUD" />
                            <flux:checkbox wire:model="alat_kontrasepsi_boleh_digunakan" value="Tubektomi" label="Tubektomi" />
                            <flux:checkbox wire:model="alat_kontrasepsi_boleh_digunakan" value="Vasektomi" label="Vasektomi" />
                        </div>
                    </div>
                @endif

                <!-- Navigation -->
                <div class="flex justify-end pt-4 border-t border-zinc-100 dark:border-zinc-850">
                    <flux:button variant="primary" wire:click="nextStep" :disabled="!$isLayak || !$peserta_kb_id">
                        Lanjut ke Informed Consent
                    </flux:button>
                </div>
            </div>
        @endif

        <!-- ==================== STEP 2: INFORMED CONSENT ==================== -->
        @if($currentStep === 2)
            <div class="space-y-6">
                <div class="border-b border-zinc-100 dark:border-zinc-800 pb-3">
                    <flux:heading size="lg">Langkah 2: Persetujuan Tindakan Medik (Informed Consent)</flux:heading>
                    <flux:text size="sm">Konfirmasi persetujuan dari pasien dan pasangan resmi sebelum tindakan medis</flux:text>
                </div>

                <!-- Persetujuan Checkboxes -->
                <div class="rounded-xl bg-zinc-50 border border-zinc-200 p-6 space-y-4 dark:bg-zinc-800/30 dark:border-zinc-700">
                    <flux:checkbox wire:model="persetujuan_klien" :label="__('Saya menyatakan bahwa klien telah mendapatkan penjelasan lengkap mengenai efek samping, risiko, serta alternatif KB dan menyetujui tindakan ini secara sukarela.')" />
                    <flux:checkbox wire:model="persetujuan_pasangan" :label="__('Saya menyatakan bahwa suami / istri klien telah menyetujui tindakan KB yang akan dilaksanakan.')" />
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <!-- Jenis Tindakan -->
                    <flux:field>
                        <flux:label>Jenis Tindakan Medis</flux:label>
                        <flux:select wire:model="jenis_tindakan_medis">
                            <option value="pemasangan">Pemasangan Alat Kontrasepsi</option>
                            <option value="pencabutan">Pencabutan Alat Kontrasepsi</option>
                            <option value="penggantian">Penggantian Alat Kontrasepsi</option>
                            <option value="penyuntikan">Penyuntikan</option>
                        </flux:select>
                        <flux:error name="jenis_tindakan_medis" />
                    </flux:field>

                    <!-- Tanggal Persetujuan -->
                    <flux:field>
                        <flux:label>Tanggal Persetujuan</flux:label>
                        <flux:input type="date" wire:model="tanggal_persetujuan" />
                        <flux:error name="tanggal_persetujuan" />
                    </flux:field>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between pt-4 border-t border-zinc-100 dark:border-zinc-850">
                    <flux:button variant="outline" wire:click="prevStep">
                        Kembali
                    </flux:button>
                    <flux:button variant="primary" wire:click="nextStep" :disabled="!$persetujuan_klien || !$persetujuan_pasangan">
                        Lanjut ke Pemberian Alokon
                    </flux:button>
                </div>
            </div>
        @endif

        <!-- ==================== STEP 3: PEMBERIAN ALOKON ==================== -->
        @if($currentStep === 3)
            <div class="space-y-6">
                <div class="border-b border-zinc-100 dark:border-zinc-800 pb-3">
                    <flux:heading size="lg">Langkah 3: Pencatatan Pelayanan & Alokon</flux:heading>
                    <flux:text size="sm">Pilih jenis kontrasepsi yang diberikan. Sistem akan secara otomatis mengurangi stok alokon.</flux:text>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <!-- Alokon -->
                    <flux:field>
                        <flux:label>Pilih Alokon (Alat/Obat Kontrasepsi)</flux:label>
                        <flux:select wire:model="alokon_id">
                            <option value="">Pilih Alokon</option>
                            @foreach($alokons as $alokon)
                                <option value="{{ $alokon->id }}">{{ $alokon->nama_alokon }} (Sisa Stok: {{ $alokon->stok }} unit)</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="alokon_id" />
                    </flux:field>

                    <!-- Tanggal Pelayanan -->
                    <flux:field>
                        <flux:label>Tanggal Pelayanan</flux:label>
                        <flux:input type="date" wire:model="tanggal_pelayanan" />
                        <flux:error name="tanggal_pelayanan" />
                    </flux:field>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <!-- Tanggal Kunjungan Ulang -->
                    <flux:field>
                        <flux:label>Tanggal Kunjungan Ulang (Kembali)</flux:label>
                        <flux:input type="date" wire:model="tanggal_kunjungan_ulang" />
                        <flux:error name="tanggal_kunjungan_ulang" />
                    </flux:field>

                    <!-- Tanggal Dicabut -->
                    <flux:field>
                        <flux:label>Tanggal Dicabut (Khusus Implan/IUD)</flux:label>
                        <flux:input type="date" wire:model="tanggal_dicabut" placeholder="Opsional jika dicabut" />
                        <flux:error name="tanggal_dicabut" />
                    </flux:field>
                </div>

                <!-- Penanggung Jawab Pelayanan -->
                <div class="space-y-4">
                    <flux:heading size="md" class="text-blue-600 dark:text-blue-400 font-bold border-b border-zinc-100 dark:border-zinc-800 pb-2">Penanggung Jawab Pelayanan</flux:heading>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <flux:field>
                            <flux:label>Nama Petugas</flux:label>
                            <flux:input type="text" placeholder="Nama Bidan/Dokter" wire:model="penanggung_jawab_nama" />
                            <flux:error name="penanggung_jawab_nama" />
                        </flux:field>

                        <flux:field>
                            <flux:label>NIP Petugas</flux:label>
                            <flux:input type="text" placeholder="Masukkan NIP (jika ada)" wire:model="penanggung_jawab_nip" />
                            <flux:error name="penanggung_jawab_nip" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Jabatan</flux:label>
                            <flux:select wire:model="penanggung_jawab_jabatan">
                                <option value="bidan">Bidan</option>
                                <option value="dokter">Dokter</option>
                                <option value="perawat">Perawat</option>
                            </flux:select>
                            <flux:error name="penanggung_jawab_jabatan" />
                        </flux:field>
                    </div>
                </div>

                <!-- Keterangan -->
                <flux:field>
                    <flux:label>Keterangan Tambahan / Catatan Bidan</flux:label>
                    <flux:textarea rows="3" placeholder="Masukkan catatan tambahan (misal: keluhan pusing, kontrol ulang 3 bulan lagi, dll)" wire:model="keterangan" />
                    <flux:error name="keterangan" />
                </flux:field>

                <!-- Navigation -->
                <div class="flex justify-between pt-4 border-t border-zinc-100 dark:border-zinc-850">
                    <flux:button variant="outline" wire:click="prevStep">
                        Kembali
                    </flux:button>
                    <flux:button variant="primary" wire:click="simpan">
                        Simpan & Kurangi Stok Alokon
                    </flux:button>
                </div>
            </div>
        @endif
    </flux:card>
</div>
