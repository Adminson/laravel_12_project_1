<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalSetupProfile extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public static function defaultSettings(): array
    {
        return [
            'localization' => [
                'date_format' => 'd-m-Y',
                'time_format' => 'H:i',
                'date_separator' => '-',
                'timezone' => config('app.timezone', 'UTC'),
                'currency_symbol' => 'RM',
                'search_wildcard' => true,
            ],
            'modal' => [
                'frame_width' => 1048,
                'frame_height' => 600,
                'overlay_close' => false,
            ],
            'label' => [
                'font_family' => 'Public Sans',
                'font_size' => 10,
                'color' => '#4b465c',
                'weight' => '600',
                'position' => 'top',
            ],
            'input' => [
                'font_family' => 'Public Sans',
                'font_size' => 11,
                'color' => '#6f6b7d',
                'margin' => 8,
            ],
        ];
    }

    public function getSettingsWithDefaultsAttribute(): array
    {
        return array_replace_recursive(static::defaultSettings(), $this->settings ?? []);
    }
}
