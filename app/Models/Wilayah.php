<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $nama_desa_kelurahan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Wilayah extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_desa_kelurahan',
    ];

    /**
     * Peserta KB yang berdomisili di wilayah ini.
     */
    public function pesertaKbs(): HasMany
    {
        return $this->hasMany(PesertaKb::class);
    }

    /**
     * Jumlah peserta KB di wilayah ini.
     */
    public function pesertaCount(): int
    {
        return $this->pesertaKbs()->count();
    }
}
