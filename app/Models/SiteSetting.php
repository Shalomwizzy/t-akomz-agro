<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    public $timestamps = false;

    protected $fillable = ['key', 'value'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting_{$key}");
    }

    public static function getMany(array $keys): array
    {
        $settings = static::whereIn('key', $keys)->pluck('value', 'key')->toArray();
        return array_merge(array_fill_keys($keys, null), $settings);
    }

    /**
     * Single source of truth for the admin/business email address.
     * Priority: MAIL_ADMIN_ADDRESS env → contact_email site setting → MAIL_FROM_ADDRESS env
     */
    public static function adminEmail(): string
    {
        return config('mail.admin_address')
            ?: static::get('contact_email')
            ?: config('mail.from.address', '');
    }
}
