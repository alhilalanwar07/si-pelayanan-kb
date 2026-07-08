<div class="flex-1 space-y-6 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div>
            <flux:heading size="xl" level="1">Tambah Peserta KB</flux:heading>
            <flux:text size="sm">Registrasi peserta baru oleh operator/admin</flux:text>
        </div>
        <flux:button variant="outline" href="{{ route('peserta-kb.index') }}" icon="arrow-left" wire:navigate>
            Kembali
        </flux:button>
    </div>

    <!-- Form Card -->
    <flux:card class="max-w-2xl mx-auto">
        <form wire:submit="simpan" class="space-y-6">
            @csrf

            <!-- Section 1: Identitas Diri -->
            <div class="space-y-4">
                <flux:heading size="lg">1. Identitas Diri</flux:heading>
                
                <div class="grid gap-4 sm:grid-cols-2">
                    <!-- NIK -->
                    <flux:field>
                        <flux:label>Nomor Induk Kependudukan (NIK)</flux:label>
                        <flux:input type="text" maxlength="16" placeholder="Masukkan 16 digit NIK" wire:model="nik" />
                        <flux:error name="nik" />
                    </flux:field>

                    <!-- Nomor WhatsApp -->
                    <flux:field>
                        <flux:label>Nomor WhatsApp Aktif</flux:label>
                        <flux:input type="text" placeholder="Contoh: 081234567890" wire:model="nomor_hp" />
                        <flux:error name="nomor_hp" />
                    </flux:field>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <!-- Nama Lengkap -->
                    <flux:field>
                        <flux:label>Nama Lengkap (Istri)</flux:label>
                        <flux:input type="text" placeholder="Nama Lengkap Pasien" wire:model="nama_lengkap" />
                        <flux:error name="nama_lengkap" />
                    </flux:field>

                    <!-- Nama Suami / Pasangan -->
                    <flux:field>
                        <flux:label>Nama Suami / Istri</flux:label>
                        <flux:input type="text" placeholder="Nama Pasangan Hidup" wire:model="nama_suami_istri" />
                        <flux:error name="nama_suami_istri" />
                    </flux:field>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <!-- Tanggal Lahir Istri -->
                    <flux:field class="sm:col-span-2">
                        <flux:label>Tanggal Lahir Istri</flux:label>
                        <flux:input type="date" wire:model="tanggal_lahir_istri" />
                        <flux:error name="tanggal_lahir_istri" />
                    </flux:field>
                </div>
            </div>

            <flux:separator />

            <!-- Section 2: Domisili & Asuransi -->
            <div class="space-y-4">
                <flux:heading size="lg">2. Domisili & Asuransi</flux:heading>

                <div class="grid gap-4 sm:grid-cols-2">
                    <!-- Wilayah -->
                    <flux:field>
                        <flux:label>Desa / Kelurahan</flux:label>
                        <flux:select wire:model="wilayah_id">
                            <option value="">Pilih Desa / Kelurahan</option>
                            @foreach($wilayahs as $wilayah)
                                <option value="{{ $wilayah->id }}">{{ $wilayah->nama_desa_kelurahan }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="wilayah_id" />
                    </flux:field>

                    <!-- Asuransi -->
                    <flux:field>
                        <flux:label>Penggunaan Asuransi</flux:label>
                        <flux:select wire:model="penggunaan_asuransi">
                            <option value="umum">Umum / Mandiri</option>
                            <option value="bpjs">BPJS Kesehatan</option>
                            <option value="kis">Kartu Indonesia Sehat (KIS)</option>
                            <option value="lainnya">Lainnya</option>
                        </flux:select>
                        <flux:error name="penggunaan_asuransi" />
                    </flux:field>
                </div>

                <!-- Alamat Lengkap -->
                <flux:field>
                    <flux:label>Alamat Lengkap</flux:label>
                    <flux:textarea rows="3" placeholder="Masukkan alamat lengkap (jalan, RT/RW, nomor rumah)" wire:model="alamat_lengkap" />
                    <flux:error name="alamat_lengkap" />
                </flux:field>
            </div>

            <flux:separator />

            <!-- Section 3: Data Keluarga -->
            <div class="space-y-4">
                <flux:heading size="lg">3. Data Keluarga</flux:heading>

                <div class="grid gap-4 sm:grid-cols-2">
                    <!-- Jumlah Anak Hidup -->
                    <flux:field>
                        <flux:label>Jumlah Anak Hidup</flux:label>
                        <flux:input type="number" min="0" placeholder="0" wire:model="jumlah_anak_hidup" />
                        <flux:error name="jumlah_anak_hidup" />
                    </flux:field>

                    <!-- Umur Anak Terakhir -->
                    <flux:field>
                        <flux:label>Umur Anak Terakhir (Bulan)</flux:label>
                        <flux:input type="number" min="0" placeholder="Kosongkan jika belum memiliki anak" wire:model="umur_anak_terakhir" />
                        <flux:error name="umur_anak_terakhir" />
                    </flux:field>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3">
                <flux:button href="{{ route('peserta-kb.index') }}" variant="outline" wire:navigate>Batal</flux:button>
                <flux:button type="submit" variant="primary">Simpan Data Peserta</flux:button>
            </div>
        </form>
    </flux:card>
</div>
