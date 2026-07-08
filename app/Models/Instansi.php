<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $nama_instansi
 * @property string $kode_faskes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Instansi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_instansi',
        'kode_faskes',
    ];

    /**
     * Pengguna yang terdaftar di instansi ini.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Stok alokon milik instansi ini.
     */
    public function alokons(): HasMany
    {
        return $this->hasMany(Alokon::class);
    }
}
