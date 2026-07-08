<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $peserta_kb_id
 * @property int $alokon_id
 * @property int|null $skrining_medis_id
 * @property \Illuminate\Support\Carbon $tanggal_pelayanan
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Pelayanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'peserta_kb_id',
        'alokon_id',
        'skrining_medis_id',
        'tanggal_pelayanan',
        'keterangan',
        'tanggal_kunjungan_ulang',
        'tanggal_dicabut',
        'penanggung_jawab_nama',
        'penanggung_jawab_nip',
        'penanggung_jawab_jabatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pelayanan' => 'date',
            'tanggal_kunjungan_ulang' => 'date',
            'tanggal_dicabut' => 'date',
        ];
    }

    // ──── Relationships ────

    /**
     * Peserta KB yang menerima pelayanan.
     */
    public function pesertaKb(): BelongsTo
    {
        return $this->belongsTo(PesertaKb::class);
    }

    /**
     * Alokon yang diberikan dalam pelayanan ini.
     */
    public function alokon(): BelongsTo
    {
        return $this->belongsTo(Alokon::class);
    }

    /**
     * Skrining medis terkait pelayanan ini.
     */
    public function skriningMedis(): BelongsTo
    {
        return $this->belongsTo(SkriningMedis::class);
    }

    // ──── Helpers ────

    /**
     * Ambil informed consent melalui skrining medis.
     */
    public function informedConsent(): ?InformedConsent
    {
        return $this->skriningMedis?->informedConsent;
    }

    /**
     * Scope: pelayanan pada bulan tertentu.
     */
    public function scopeBulan($query, int $bulan, int $tahun)
    {
        return $query->whereMonth('tanggal_pelayanan', $bulan)
            ->whereYear('tanggal_pelayanan', $tahun);
    }

    /**
     * Scope: pelayanan pada periode tertentu.
     */
    public function scopePeriode($query, string $dari, string $sampai)
    {
        return $query->whereBetween('tanggal_pelayanan', [$dari, $sampai]);
    }
}
