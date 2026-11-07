<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $nama_desa_kelurahan
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $link_gmaps
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Wilayah extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_desa_kelurahan',
        'latitude',
        'longitude',
        'link_gmaps',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    /**
     * Ekstrak koordinat Latitude & Longitude dari Link Google Maps atau teks "lat, lng".
     *
     * @return array{latitude: float, longitude: float}|null
     */
    public static function extractCoordinatesFromInput(?string $input): ?array
    {
        if (! $input || trim($input) === '') {
            return null;
        }

        $text = urldecode(trim($input));

        // 1. Format pin spesifik Google Maps: !3d-4.1385!4d121.6178
        if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $text, $m)) {
            return [
                'latitude' => round((float) $m[1], 7),
                'longitude' => round((float) $m[2], 7),
            ];
        }

        // 2. Format URL Google Maps: @-4.1385,121.6178 atau ?q=-4.1385,121.6178 atau ll=-4.1385,121.6178
        if (preg_match('/(?:@|[?&](?:q|ll|query|center)=)(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/i', $text, $m)) {
            return [
                'latitude' => round((float) $m[1], 7),
                'longitude' => round((float) $m[2], 7),
            ];
        }

        // 3. Format teks koordinat langsung: "-4.1385, 121.6178"
        if (preg_match('/^\s*(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)\s*$/', $text, $m)) {
            $lat = (float) $m[1];
            $lng = (float) $m[2];
            if ($lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180) {
                return [
                    'latitude' => round($lat, 7),
                    'longitude' => round($lng, 7),
                ];
            }
        }

        return null;
    }

    /**
     * Peserta KB yang berdomisili di wilayah ini.
     */
    public function pesertaKbs(): HasMany
    {
        return $this->hasMany(PesertaKb::class);
    }

    /**
     * Pelayanan KB untuk peserta di wilayah ini.
     */
    public function pelayanans(): HasManyThrough
    {
        return $this->hasManyThrough(Pelayanan::class, PesertaKb::class);
    }

    /**
     * Jumlah peserta KB di wilayah ini.
     */
    public function pesertaCount(): int
    {
        return $this->pesertaKbs()->count();
    }
}
