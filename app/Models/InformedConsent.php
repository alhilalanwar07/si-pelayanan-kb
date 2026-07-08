<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $skrining_medis_id
 * @property bool $persetujuan_klien
 * @property bool $persetujuan_pasangan
 * @property string $jenis_tindakan_medis
 * @property \Illuminate\Support\Carbon $tanggal_persetujuan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class InformedConsent extends Model
{
    use HasFactory;

    protected $fillable = [
        'skrining_medis_id',
        'persetujuan_klien',
        'persetujuan_pasangan',
        'jenis_tindakan_medis',
        'tanggal_persetujuan',
    ];

    protected $attributes = [
        'persetujuan_klien' => false,
        'persetujuan_pasangan' => false,
    ];

    protected function casts(): array
    {
        return [
            'persetujuan_klien' => 'boolean',
            'persetujuan_pasangan' => 'boolean',
            'tanggal_persetujuan' => 'date',
        ];
    }

    // ──── Relationships ────

    /**
     * Skrining medis yang terkait dengan consent ini.
     */
    public function skriningMedis(): BelongsTo
    {
        return $this->belongsTo(SkriningMedis::class);
    }

    // ──── Helpers ────

    /**
     * Apakah kedua persetujuan (klien & pasangan) sudah diberikan?
     */
    public function isLengkap(): bool
    {
        return $this->persetujuan_klien && $this->persetujuan_pasangan;
    }

    /**
     * Label jenis tindakan medis yang lebih readable.
     */
    public function labelTindakan(): string
    {
        return match ($this->jenis_tindakan_medis) {
            'pemasangan' => 'Pemasangan Kontrasepsi',
            'pencabutan' => 'Pencabutan Kontrasepsi',
            'penggantian' => 'Penggantian Kontrasepsi',
            'penyuntikan' => 'Penyuntikan',
            default => $this->jenis_tindakan_medis,
        };
    }
}
