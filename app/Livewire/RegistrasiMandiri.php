<?php

namespace App\Livewire;

use App\Models\AntrianJadwal;
use App\Models\JadwalPelayanan;
use App\Models\PesertaKb;
use App\Models\Wilayah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RegistrasiMandiri extends Component
{
    // Flow state: 'cek_nik', 'registrasi', 'pilih_jadwal', 'selesai'
    public string $step = 'cek_nik';

    // Step: Cek NIK
    public string $cekNik = '';
    public ?PesertaKb $foundPeserta = null;
    public string $nikStatus = ''; // '', 'terdaftar', 'not_found'

    // Step: Registrasi (peserta baru)
    public $nik = '';
    public $nomor_hp = '';
    public $nama_lengkap = '';
    public $nama_suami_istri = '';
    public $tanggal_lahir_istri = '';
    public $alamat_lengkap = '';
    public $wilayah_id = '';
    public $penggunaan_asuransi = 'bpjs';
    public $jumlah_anak_hidup = 0;
    public $umur_anak_terakhir = '';

    // Step: Pilih Jadwal (baik pendaftar baru maupun peserta terverifikasi)
    public ?int $selectedJadwalId = null;

    // Hasil Tiket Antrian
    public ?int $antrianId = null;
    public string $successMessage = '';
    public ?int $nomorAntrian = null;
    public ?string $jadwalInfo = null;
    public ?JadwalPelayanan $selectedJadwal = null;

    /**
     * Cek NIK di database
     */
    public function cekNikAction()
    {
        $this->validate([
            'cekNik' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/'],
        ], [], [
            'cekNik' => 'NIK',
        ]);

        $peserta = PesertaKb::where('nik', $this->cekNik)->first();

        if (!$peserta) {
            $this->nikStatus = 'not_found';
            $this->nik = $this->cekNik; // pre-fill NIK for registration form
            return;
        }

        $this->foundPeserta = $peserta;

        // Langsung aktif tanpa menunggu verifikasi
        if (!$peserta->isTerverifikasi()) {
            $peserta->update(['status' => 'terverifikasi']);
        }

        $this->nikStatus = 'terdaftar';
        $this->step = 'pilih_jadwal';
    }

    /**
     * Lanjut ke form registrasi (NIK not found)
     */
    public function lanjutRegistrasi()
    {
        $this->step = 'registrasi';
    }

    /**
     * Kembali ke cek NIK
     */
    public function kembaliCekNik()
    {
        $this->step = 'cek_nik';
        $this->nikStatus = '';
        $this->foundPeserta = null;
        $this->cekNik = '';
    }

    /**
     * Simpan registrasi baru beserta jadwal & antrian
     */
    public function daftar()
    {
        $this->validate([
            'nik' => ['required', 'string', 'size:16', 'unique:peserta_kbs,nik'],
            'nomor_hp' => ['required', 'string', 'min:10', 'max:15', 'regex:/^[0-9]+$/'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nama_suami_istri' => ['required', 'string', 'max:255'],
            'tanggal_lahir_istri' => ['required', 'date', 'before:today'],
            'alamat_lengkap' => ['required', 'string'],
            'wilayah_id' => ['required', 'exists:wilayahs,id'],
            'penggunaan_asuransi' => ['required', 'string', 'in:bpjs,kis,umum,lainnya'],
            'jumlah_anak_hidup' => ['required', 'integer', 'min:0'],
            'umur_anak_terakhir' => ['nullable', 'integer', 'min:0'],
            'selectedJadwalId' => ['required', 'exists:jadwal_pelayanans,id'],
        ], [
            'selectedJadwalId.required' => 'Silakan pilih salah satu jadwal pelayanan.',
        ], [
            'nik' => 'NIK',
            'nomor_hp' => 'Nomor WhatsApp Aktif',
            'nama_lengkap' => 'Nama Lengkap',
            'nama_suami_istri' => 'Nama Suami/Istri',
            'tanggal_lahir_istri' => 'Tanggal Lahir Istri',
            'alamat_lengkap' => 'Alamat Lengkap',
            'wilayah_id' => 'Desa/Kelurahan',
            'penggunaan_asuransi' => 'Penggunaan Asuransi',
            'jumlah_anak_hidup' => 'Jumlah Anak Hidup',
            'umur_anak_terakhir' => 'Umur Anak Terakhir (Bulan)',
            'selectedJadwalId' => 'Jadwal Pelayanan',
        ]);

        $jadwal = JadwalPelayanan::aktif()->mendatang()->findOrFail($this->selectedJadwalId);

        if ($jadwal->isFull()) {
            $this->addError('selectedJadwalId', 'Maaf, kuota jadwal yang dipilih sudah penuh. Silakan pilih jadwal lain.');
            return;
        }

        DB::transaction(function () use ($jadwal) {
            // 1. Simpan Peserta Baru
            $peserta = PesertaKb::create([
                'user_id' => null,
                'wilayah_id' => $this->wilayah_id,
                'nik' => $this->nik,
                'nomor_hp' => $this->nomor_hp,
                'nama_lengkap' => $this->nama_lengkap,
                'nama_suami_istri' => $this->nama_suami_istri,
                'tanggal_lahir_istri' => $this->tanggal_lahir_istri,
                'alamat_lengkap' => $this->alamat_lengkap,
                'penggunaan_asuransi' => $this->penggunaan_asuransi,
                'jumlah_anak_hidup' => $this->jumlah_anak_hidup,
                'umur_anak_terakhir' => empty($this->umur_anak_terakhir) ? null : $this->umur_anak_terakhir,
                'status' => 'terverifikasi',
            ]);

            // 2. Buat Nomor Antrian
            $lastAntrian = AntrianJadwal::where('jadwal_pelayanan_id', $jadwal->id)->max('nomor_antrian');
            $nomorAntrian = ($lastAntrian ?? 0) + 1;

            $antrian = AntrianJadwal::create([
                'jadwal_pelayanan_id' => $jadwal->id,
                'peserta_kb_id' => $peserta->id,
                'nomor_antrian' => $nomorAntrian,
                'status' => 'terdaftar',
            ]);

            $this->foundPeserta = $peserta;
            $this->antrianId = $antrian->id;
            $this->nomorAntrian = $nomorAntrian;
            $this->selectedJadwal = $jadwal;
            $this->jadwalInfo = $jadwal->tanggal->translatedFormat('l, d F Y') . ' (' . substr($jadwal->waktu_mulai, 0, 5) . ' - ' . substr($jadwal->waktu_selesai, 0, 5) . ' WITA)';
            $this->successMessage = 'Pendaftaran dan pemesanan antrian pelayanan KB Anda berhasil!';
        });

        $this->step = 'selesai';
    }

    /**
     * Pilih jadwal dan buat antrian (untuk peserta lama / yang sudah terdaftar)
     */
    public function pilihJadwal()
    {
        $this->validate([
            'selectedJadwalId' => ['required', 'exists:jadwal_pelayanans,id'],
        ], [
            'selectedJadwalId.required' => 'Silakan pilih salah satu jadwal.',
        ]);

        $jadwal = JadwalPelayanan::aktif()->mendatang()->findOrFail($this->selectedJadwalId);

        // Check if already registered for this jadwal
        $existing = AntrianJadwal::where('jadwal_pelayanan_id', $jadwal->id)
            ->where('peserta_kb_id', $this->foundPeserta->id)
            ->where('status', 'terdaftar')
            ->first();

        if ($existing) {
            $this->antrianId = $existing->id;
            $this->nomorAntrian = $existing->nomor_antrian;
            $this->selectedJadwal = $jadwal;
            $this->jadwalInfo = $jadwal->tanggal->translatedFormat('l, d F Y') . ' (' . substr($jadwal->waktu_mulai, 0, 5) . ' - ' . substr($jadwal->waktu_selesai, 0, 5) . ' WITA)';
            $this->successMessage = 'Anda sudah memiliki antrian aktif pada jadwal ini.';
            $this->step = 'selesai';
            return;
        }

        // Check kuota
        if ($jadwal->isFull()) {
            $this->addError('selectedJadwalId', 'Maaf, kuota jadwal ini sudah penuh. Silakan pilih jadwal lain.');
            return;
        }

        // Calculate nomor antrian
        $lastAntrian = AntrianJadwal::where('jadwal_pelayanan_id', $jadwal->id)->max('nomor_antrian');
        $nomorAntrian = ($lastAntrian ?? 0) + 1;

        $antrian = AntrianJadwal::create([
            'jadwal_pelayanan_id' => $jadwal->id,
            'peserta_kb_id' => $this->foundPeserta->id,
            'nomor_antrian' => $nomorAntrian,
            'status' => 'terdaftar',
        ]);

        $this->antrianId = $antrian->id;
        $this->nomorAntrian = $nomorAntrian;
        $this->selectedJadwal = $jadwal;
        $this->jadwalInfo = $jadwal->tanggal->translatedFormat('l, d F Y') . ' (' . substr($jadwal->waktu_mulai, 0, 5) . ' - ' . substr($jadwal->waktu_selesai, 0, 5) . ' WITA)';
        $this->successMessage = 'Pemesanan nomor antrian jadwal pelayanan KB berhasil!';
        $this->step = 'selesai';
    }

    /**
     * Batalkan antrian yang belum dilayani agar peserta bisa memilih jadwal lain
     */
    public function batalkanAntrian(int $antrianId)
    {
        $antrian = AntrianJadwal::where('id', $antrianId)
            ->where('peserta_kb_id', $this->foundPeserta->id)
            ->firstOrFail();

        $antrian->update(['status' => 'batal']);

        $this->selectedJadwalId = null;
        $this->antrianId = null;
        $this->nomorAntrian = null;

        session()->flash('success_pembatalan', 'Jadwal antrian Anda berhasil dibatalkan. Kuota telah dikembalikan dan Anda dapat memilih jadwal pelayanan baru di bawah.');
    }

    /**
     * Tampilkan tiket antrian yang sudah ada
     */
    public function lihatTiket(int $antrianId)
    {
        $antrian = AntrianJadwal::where('id', $antrianId)
            ->where('peserta_kb_id', $this->foundPeserta->id)
            ->with('jadwalPelayanan')
            ->firstOrFail();

        $jadwal = $antrian->jadwalPelayanan;
        $this->antrianId = $antrian->id;
        $this->nomorAntrian = $antrian->nomor_antrian;
        $this->selectedJadwal = $jadwal;
        $this->jadwalInfo = $jadwal ? ($jadwal->tanggal->translatedFormat('l, d F Y') . ' (' . substr($jadwal->waktu_mulai, 0, 5) . ' - ' . substr($jadwal->waktu_selesai, 0, 5) . ' WITA)') : '-';
        $this->successMessage = 'Tiket antrian Anda.';
        $this->step = 'selesai';
    }

    /**
     * Unduh PDF Tiket Antrian Resmi (DomPDF)
     */
    public function unduhPdf()
    {
        if (!$this->antrianId) {
            return;
        }

        $antrian = AntrianJadwal::with(['pesertaKb.wilayah', 'jadwalPelayanan'])->findOrFail($this->antrianId);
        $pdf = Pdf::loadView('pdf.tiket-antrian', compact('antrian'));
        $pdf->setPaper('a5', 'portrait');

        $filename = 'Tiket-Antrian-KB-' . str_pad($antrian->nomor_antrian, 3, '0', STR_PAD_LEFT) . '-' . ($antrian->pesertaKb->nik ?? 'pasien') . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }

    /**
     * Reset semua dan mulai dari awal
     */
    public function resetForm()
    {
        $this->reset();
        $this->step = 'cek_nik';
    }

    public function render()
    {
        $wilayahs = Wilayah::orderBy('nama_desa_kelurahan')->get();

        $jadwalTersedia = JadwalPelayanan::aktif()
            ->mendatang()
            ->withCount(['antrians' => fn($q) => $q->where('status', '!=', 'batal')])
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->get();

        // Antrian aktif pasien yang belum dilayani (status 'terdaftar')
        $antrianAktif = $this->foundPeserta
            ? AntrianJadwal::where('peserta_kb_id', $this->foundPeserta->id)
                ->where('status', 'terdaftar')
                ->with('jadwalPelayanan')
                ->latest()
                ->first()
            : null;

        // Riwayat pelayanan medis yang sudah dijalani oleh pasien
        $riwayatPelayanan = $this->foundPeserta
            ? $this->foundPeserta->pelayanans()
                ->with(['alokon', 'user'])
                ->latest('tanggal_pelayanan')
                ->get()
            : collect();

        // Riwayat antrian sebelumnya (hadir / batal)
        $riwayatAntrian = $this->foundPeserta
            ? AntrianJadwal::where('peserta_kb_id', $this->foundPeserta->id)
                ->whereIn('status', ['hadir', 'batal'])
                ->with('jadwalPelayanan')
                ->latest()
                ->get()
            : collect();

        return view('livewire.registrasi-mandiri', [
            'wilayahs' => $wilayahs,
            'jadwalTersedia' => $jadwalTersedia,
            'antrianAktif' => $antrianAktif,
            'riwayatPelayanan' => $riwayatPelayanan,
            'riwayatAntrian' => $riwayatAntrian,
        ])->layout('layouts.plain', ['title' => 'Registrasi Mandiri Peserta KB']);
    }
}
