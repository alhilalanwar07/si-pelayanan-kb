<div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 flex flex-col justify-between">
    <!-- Top Navbar -->
    <header class="bg-white border-b border-zinc-200 px-6 py-4 dark:bg-zinc-900 dark:border-zinc-800 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="flex size-10 items-center justify-center rounded-xl bg-blue-600 text-white font-extrabold shadow-md shadow-blue-500/20">
                KB
            </div>
            <div>
                <flux:heading size="md" class="font-extrabold leading-none">SI Pelayanan KB</flux:heading>
                <flux:text size="2xs" class="text-zinc-400">Kecamatan Wundulako</flux:text>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <flux:button variant="outline" size="sm" href="{{ route('home') }}" wire:navigate>Home</flux:button>
            <flux:button variant="primary" size="sm" href="{{ route('login') }}" wire:navigate>Masuk</flux:button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex justify-center items-center py-10 px-4">
        <div class="w-full max-w-3xl space-y-6">
            <!-- Form Card -->
            <flux:card class="p-6 md:p-8 space-y-6 shadow-lg shadow-zinc-200/50 dark:shadow-none">
                <div class="text-center space-y-2 border-b border-zinc-100 dark:border-zinc-800 pb-4">
                    <div class="inline-flex size-14 items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400">
                        <flux:icon name="clipboard-document-check" class="size-7" />
                    </div>
                    <flux:heading size="xl" class="font-black">Registrasi Mandiri Peserta</flux:heading>
                    <flux:text size="sm">Isi formulir di bawah dengan data KTP Anda untuk mendaftar sebagai peserta KB</flux:text>
                </div>

                @if($successMessage)
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 dark:bg-emerald-950/30 dark:border-emerald-900 dark:text-emerald-400 space-y-2">
                        <div class="flex gap-2">
                            <flux:icon name="check-circle" class="size-5 shrink-0 text-emerald-600" />
                            <span class="font-semibold">Pendaftaran Berhasil!</span>
                        </div>
                        <p class="text-xs leading-relaxed">{{ $successMessage }}</p>
                    </div>
                @else
                    <form wire:submit="daftar" class="space-y-8">
                        @csrf

                        <!-- Section 1: Identitas -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 border-b border-zinc-150 pb-2 dark:border-zinc-800">
                                <flux:badge color="blue" size="sm" class="rounded-full size-6 flex items-center justify-center p-0">1</flux:badge>
                                <flux:heading size="md" class="font-bold">Data Identitas Diri</flux:heading>
                            </div>
                            
                            <div class="grid gap-4 sm:grid-cols-2">
                                <flux:field>
                                    <flux:label>Nomor Induk Kependudukan (NIK)</flux:label>
                                    <flux:input type="text" maxlength="16" placeholder="Masukkan 16 digit NIK Anda" wire:model="nik" />
                                    <flux:error name="nik" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>Nomor WhatsApp Aktif</flux:label>
                                    <flux:input type="text" placeholder="Contoh: 081234567890" wire:model="nomor_hp" />
                                    <flux:error name="nomor_hp" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>Nama Lengkap Istri</flux:label>
                                    <flux:input type="text" placeholder="Masukkan nama lengkap sesuai KTP" wire:model="nama_lengkap" />
                                    <flux:error name="nama_lengkap" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>Nama Suami / Pasangan</flux:label>
                                    <flux:input type="text" placeholder="Nama suami/pasangan" wire:model="nama_suami_istri" />
                                    <flux:error name="nama_suami_istri" />
                                </flux:field>

                                <flux:field class="sm:col-span-2">
                                    <flux:label>Tanggal Lahir Istri</flux:label>
                                    <flux:input type="date" wire:model="tanggal_lahir_istri" />
                                    <flux:error name="tanggal_lahir_istri" />
                                </flux:field>
                            </div>
                        </div>

                        <!-- Section 2: Domisili & Asuransi -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 border-b border-zinc-150 pb-2 dark:border-zinc-800">
                                <flux:badge color="blue" size="sm" class="rounded-full size-6 flex items-center justify-center p-0">2</flux:badge>
                                <flux:heading size="md" class="font-bold">Data Wilayah & Asuransi</flux:heading>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
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

                                <flux:field class="sm:col-span-2">
                                    <flux:label>Alamat Lengkap (KTP)</flux:label>
                                    <flux:textarea rows="3" placeholder="Masukkan alamat lengkap (Jalan, RT/RW, Dusun)" wire:model="alamat_lengkap" />
                                    <flux:error name="alamat_lengkap" />
                                </flux:field>
                            </div>
                        </div>

                        <!-- Section 3: Keluarga -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 border-b border-zinc-150 pb-2 dark:border-zinc-800">
                                <flux:badge color="blue" size="sm" class="rounded-full size-6 flex items-center justify-center p-0">3</flux:badge>
                                <flux:heading size="md" class="font-bold">Data Keluarga</flux:heading>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <flux:field>
                                    <flux:label>Jumlah Anak Hidup</flux:label>
                                    <flux:input type="number" min="0" placeholder="0" wire:model="jumlah_anak_hidup" />
                                    <flux:error name="jumlah_anak_hidup" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>Umur Anak Terakhir (Bulan)</flux:label>
                                    <flux:input type="number" min="0" placeholder="Kosongkan jika belum memiliki anak" wire:model="umur_anak_terakhir" />
                                    <flux:error name="umur_anak_terakhir" />
                                </flux:field>
                            </div>
                        </div>

                        <!-- Submit -->
                        <flux:button type="submit" variant="primary" class="w-full py-3 text-base font-bold shadow-md shadow-blue-500/10">
                            Kirim Formulir Pendaftaran
                        </flux:button>
                    </form>
                @endif
            </flux:card>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-xs text-zinc-400 dark:text-zinc-500 border-t border-zinc-200 dark:border-zinc-800">
        &copy; {{ now()->year }} SI Pelayanan KB Kecamatan Wundulako. All rights reserved.
    </footer>
</div>
