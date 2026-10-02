<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $jadwal_pelayanan_id
 * @property int $peserta_kb_id
 * @property int $nomor_antrian
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class AntrianJadwal extends Model
{
    protected $fillable = [
        'jadwal_pelayanan_id',
        'peserta_kb_id',
        'nomor_antrian',
        'jenis_pendaftaran',
        'kode_antrian',
        'status',
    ];

    public function getKodeDisplayAttribute(): string
    {
        if (!empty($this->kode_antrian)) {
            return $this->kode_antrian;
        }

        return ($this->jenis_pendaftaran === 'walkin' ? 'W-' : '#') . str_pad($this->nomor_antrian, 3, '0', STR_PAD_LEFT);
    }

    protected function casts(): array
    {
        return [
            'nomor_antrian' => 'integer',
        ];
    }

    // ──── Relationships ────

    public function jadwalPelayanan(): BelongsTo
    {
        return $this->belongsTo(JadwalPelayanan::class);
    }

    public function pesertaKb(): BelongsTo
    {
        return $this->belongsTo(PesertaKb::class);
    }
}
