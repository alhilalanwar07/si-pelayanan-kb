<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $instansi_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string $waktu_mulai
 * @property string $waktu_selesai
 * @property string|null $keterangan
 * @property int $kuota
 * @property bool $is_aktif
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class JadwalPelayanan extends Model
{
    protected $fillable = [
        'instansi_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'keterangan',
        'kuota',
        'is_aktif',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'kuota' => 'integer',
            'is_aktif' => 'boolean',
        ];
    }

    // ──── Relationships ────

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function antrians(): HasMany
    {
        return $this->hasMany(AntrianJadwal::class);
    }

    // ──── Scopes ────

    /**
     * Scope: jadwal yang aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    /**
     * Scope: jadwal mendatang (hari ini atau setelahnya).
     */
    public function scopeMendatang($query)
    {
        return $query->where('tanggal', '>=', now()->toDateString());
    }

    // ──── Helpers ────

    /**
     * Sisa kuota yang tersedia.
     */
    public function sisaKuota(): int
    {
        return max(0, $this->kuota - $this->antrians()->count());
    }

    /**
     * Apakah kuota sudah penuh?
     */
    public function isFull(): bool
    {
        return $this->sisaKuota() <= 0;
    }

    /**
     * Apakah jadwal sudah lewat?
     */
    public function isLewat(): bool
    {
        return $this->tanggal->lt(now()->startOfDay());
    }
}
