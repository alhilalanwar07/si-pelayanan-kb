<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $peserta_kb_id
 * @property \Illuminate\Support\Carbon $tanggal_skrining
 * @property \Illuminate\Support\Carbon|null $haid_terakhir
 * @property string|null $gravida_partus_abortus
 * @property bool $status_menyusui
 * @property bool $rwyt_sakit_kuning
 * @property bool $rwyt_pendarahan
 * @property bool $rwyt_keputihan
 * @property bool $rwyt_tumor
 * @property string|null $fisik_keadaan_umum
 * @property float|null $fisik_berat_badan
 * @property string|null $fisik_tekanan_darah
 * @property string|null $posisi_rahim
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class SkriningMedis extends Model
{
    use HasFactory;

    protected $table = 'skrining_medis';

    protected $fillable = [
        'peserta_kb_id',
        'tanggal_skrining',
        'haid_terakhir',
        'gravida_partus_abortus',
        'status_menyusui',
        'rwyt_sakit_kuning',
        'rwyt_pendarahan',
        'rwyt_keputihan',
        'rwyt_tumor',
        'fisik_keadaan_umum',
        'fisik_berat_badan',
        'fisik_tekanan_darah',
        'posisi_rahim',
        'hamil_diduga_hamil',
        'pemeriksaan_dalam_radang',
        'pemeriksaan_dalam_tumor',
        'pemeriksaan_tambahan_diabetes',
        'pemeriksaan_tambahan_pembekuan_darah',
        'pemeriksaan_tambahan_orchitis',
        'pemeriksaan_tambahan_tumor',
        'alat_kontrasepsi_boleh_digunakan',
    ];

    protected $attributes = [
        'status_menyusui' => false,
        'rwyt_sakit_kuning' => false,
        'rwyt_pendarahan' => false,
        'rwyt_keputihan' => false,
        'rwyt_tumor' => false,
        'hamil_diduga_hamil' => false,
        'pemeriksaan_dalam_radang' => false,
        'pemeriksaan_dalam_tumor' => false,
        'pemeriksaan_tambahan_diabetes' => false,
        'pemeriksaan_tambahan_pembekuan_darah' => false,
        'pemeriksaan_tambahan_orchitis' => false,
        'pemeriksaan_tambahan_tumor' => false,
    ];

    protected function casts(): array
    {
        return [
            'tanggal_skrining' => 'date',
            'haid_terakhir' => 'date',
            'status_menyusui' => 'boolean',
            'rwyt_sakit_kuning' => 'boolean',
            'rwyt_pendarahan' => 'boolean',
            'rwyt_keputihan' => 'boolean',
            'rwyt_tumor' => 'boolean',
            'hamil_diduga_hamil' => 'boolean',
            'pemeriksaan_dalam_radang' => 'boolean',
            'pemeriksaan_dalam_tumor' => 'boolean',
            'pemeriksaan_tambahan_diabetes' => 'boolean',
            'pemeriksaan_tambahan_pembekuan_darah' => 'boolean',
            'pemeriksaan_tambahan_orchitis' => 'boolean',
            'pemeriksaan_tambahan_tumor' => 'boolean',
            'fisik_berat_badan' => 'decimal:2',
            'alat_kontrasepsi_boleh_digunakan' => 'array',
        ];
    }

    // ──── Relationships ────

    /**
     * Peserta KB yang diskrining.
     */
    public function pesertaKb(): BelongsTo
    {
        return $this->belongsTo(PesertaKb::class);
    }

    /**
     * Informed consent terkait skrining ini.
     */
    public function informedConsent(): HasOne
    {
        return $this->hasOne(InformedConsent::class);
    }

    /**
     * Pelayanan yang terkait skrining ini.
     */
    public function pelayanans(): HasMany
    {
        return $this->hasMany(Pelayanan::class);
    }

    // ──── Helpers ────

    /**
     * Apakah ada riwayat penyakit yang terdeteksi?
     */
    public function adaRiwayatPenyakit(): bool
    {
        return $this->rwyt_sakit_kuning
            || $this->rwyt_pendarahan
            || $this->rwyt_keputihan
            || $this->rwyt_tumor;
    }

    /**
     * Daftar riwayat penyakit yang terdeteksi.
     */
    public function riwayatPenyakitList(): array
    {
        $riwayat = [];

        if ($this->rwyt_sakit_kuning) {
            $riwayat[] = 'Sakit Kuning (Hepatitis)';
        }
        if ($this->rwyt_pendarahan) {
            $riwayat[] = 'Pendarahan';
        }
        if ($this->rwyt_keputihan) {
            $riwayat[] = 'Keputihan';
        }
        if ($this->rwyt_tumor) {
            $riwayat[] = 'Tumor';
        }

        return $riwayat;
    }

    /**
     * Apakah informed consent sudah diisi?
     */
    public function sudahConsent(): bool
    {
        return $this->informedConsent()->exists();
    }
}
