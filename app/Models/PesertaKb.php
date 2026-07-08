<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|null $user_id
 * @property int $wilayah_id
 * @property string $nik
 * @property string $nama_lengkap
 * @property string $nama_suami_istri
 * @property string|null $nomor_hp
 * @property \Illuminate\Support\Carbon $tanggal_lahir_istri
 * @property string $alamat_lengkap
 * @property string|null $penggunaan_asuransi
 * @property int $jumlah_anak_hidup
 * @property int|null $umur_anak_terakhir
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PesertaKb extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wilayah_id',
        'nik',
        'nomor_hp',
        'nama_lengkap',
        'nama_suami_istri',
        'tanggal_lahir_istri',
        'alamat_lengkap',
        'penggunaan_asuransi',
        'pendidikan_istri',
        'pendidikan_suami',
        'pekerjaan_istri',
        'pekerjaan_suami',
        'jumlah_anak_hidup',
        'jumlah_anak_laki',
        'jumlah_anak_perempuan',
        'umur_anak_terakhir',
        'status_kepesertaan',
        'kb_terakhir',
        'status',
    ];

    protected $attributes = [
        'jumlah_anak_hidup' => 0,
        'jumlah_anak_laki' => 0,
        'jumlah_anak_perempuan' => 0,
        'status' => 'menunggu',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir_istri' => 'date',
            'jumlah_anak_hidup' => 'integer',
            'jumlah_anak_laki' => 'integer',
            'jumlah_anak_perempuan' => 'integer',
            'umur_anak_terakhir' => 'integer',
        ];
    }

    // ──── Relationships ────

    /**
     * User yang mendaftarkan peserta ini (null jika registrasi mandiri).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Wilayah domisili peserta.
     */
    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class);
    }

    /**
     * Riwayat skrining medis peserta.
     */
    public function skriningMedis(): HasMany
    {
        return $this->hasMany(SkriningMedis::class);
    }

    /**
     * Riwayat pelayanan peserta.
     */
    public function pelayanans(): HasMany
    {
        return $this->hasMany(Pelayanan::class);
    }

    // ──── Scopes ────

    /**
     * Scope: peserta yang menunggu verifikasi.
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    /**
     * Scope: peserta yang sudah terverifikasi.
     */
    public function scopeTerverifikasi($query)
    {
        return $query->where('status', 'terverifikasi');
    }

    // ──── Helpers ────

    /**
     * Apakah peserta sudah terverifikasi?
     */
    public function isTerverifikasi(): bool
    {
        return $this->status === 'terverifikasi';
    }

    /**
     * Verifikasi peserta.
     */
    public function verifikasi(): bool
    {
        return $this->update(['status' => 'terverifikasi']);
    }

    /**
     * Skrining medis terakhir.
     */
    public function skriningTerakhir(): ?SkriningMedis
    {
        return $this->skriningMedis()->latest('tanggal_skrining')->first();
    }

    /**
     * Dapatkan link WhatsApp dengan template pesan konfirmasi.
     */
    public function getWhatsappLinkAttribute(): string
    {
        if (empty($this->nomor_hp)) {
            return '#';
        }

        $phone = preg_replace('/[^0-9]/', '', $this->nomor_hp);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $message = "Halo Ibu " . $this->nama_lengkap . ",\n\nPendaftaran Anda di aplikasi SI Pelayanan KB Kecamatan Wundulako telah berhasil DIKONFIRMASI.\n\nSilakan hadir di faskes untuk mendapatkan pelayanan kontrasepsi.\n\nJadwal Kehadiran:\nHari/Tanggal: \nJam: \n\nHarap membawa KTP dan Kartu BPJS/KIS (jika ada). Terima kasih.";

        return "https://api.whatsapp.com/send?phone=" . $phone . "&text=" . rawurlencode($message);
    }
}
