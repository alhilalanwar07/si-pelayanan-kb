<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $instansi_id
 * @property string $nama_alokon
 * @property int $stok
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Alokon extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
        'nama_alokon',
        'stok',
    ];

    protected $attributes = [
        'stok' => 0,
    ];

    /**
     * Instansi pemilik alokon ini.
     */
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    /**
     * Pelayanan yang menggunakan alokon ini.
     */
    public function pelayanans(): HasMany
    {
        return $this->hasMany(Pelayanan::class);
    }

    /**
     * Cek apakah stok masih tersedia.
     */
    public function isStokTersedia(int $jumlah = 1): bool
    {
        return $this->stok >= $jumlah;
    }

    /**
     * Kurangi stok alokon.
     */
    public function kurangiStok(int $jumlah = 1): bool
    {
        if (! $this->isStokTersedia($jumlah)) {
            return false;
        }

        $this->decrement('stok', $jumlah);

        return true;
    }

    /**
     * Scope: alokon dengan stok rendah (di bawah threshold).
     */
    public function scopeStokRendah($query, int $threshold = 10)
    {
        return $query->where('stok', '<', $threshold);
    }
}
