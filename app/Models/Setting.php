<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    use HasFactory;

    public const CACHE_KEY = 'site_settings_all';

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget(self::CACHE_KEY);
        });

        static::deleted(function () {
            Cache::forget(self::CACHE_KEY);
        });
    }

    public static function getAllSettings(): array
    {
        if (!Schema::hasTable('settings')) {
            return [];
        }

        return Cache::rememberForever(self::CACHE_KEY, function () {
            return static::all()->pluck('value', 'key')->toArray();
        });
    }

    public static function get(string $key, $default = null)
    {
        $all = static::getAllSettings();
        return array_key_exists($key, $all) ? $all[$key] : $default;
    }

    public static function set(string $key, $value, ?string $group = null)
    {
        $existing = static::where('key', $key)->first();
        if ($existing) {
            $existing->value = $value;
            if ($group !== null) {
                $existing->group = $group;
            }
            $existing->save();
            $setting = $existing;
        } else {
            $setting = static::create([
                'key'   => $key,
                'value' => $value,
                'group' => $group ?? 'general',
            ]);
        }

        Cache::forget(self::CACHE_KEY);

        return $setting;
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
