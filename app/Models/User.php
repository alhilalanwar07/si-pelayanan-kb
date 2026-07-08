<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int|null $instansi_id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $level_akses
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'instansi_id',
        'name',
        'username',
        'password',
        'level_akses',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // ──── Relationships ────

    /**
     * Instansi tempat user bertugas.
     */
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    /**
     * Peserta KB yang didaftarkan oleh user ini.
     */
    public function pesertaKbs(): HasMany
    {
        return $this->hasMany(PesertaKb::class);
    }

    // ──── Helpers ────

    /**
     * Get the user's initials.
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }

    /**
     * Cek apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->level_akses === 'admin';
    }

    /**
     * Cek apakah user adalah bidan.
     */
    public function isBidan(): bool
    {
        return $this->level_akses === 'bidan';
    }

    /**
     * Cek apakah user adalah pimpinan.
     */
    public function isPimpinan(): bool
    {
        return $this->level_akses === 'pimpinan';
    }

    /**
     * Label level akses yang readable.
     */
    public function labelLevelAkses(): string
    {
        return match ($this->level_akses) {
            'admin' => 'Admin / Operator Kecamatan',
            'bidan' => 'Bidan / Petugas Medis',
            'pimpinan' => 'Pimpinan DPPKB',
            default => $this->level_akses,
        };
    }
}
