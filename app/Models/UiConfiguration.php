<?php
// app/Models/UiConfiguration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UiConfiguration extends Model
{
    protected $table = 'ui_configurations';

    protected $fillable = [
        'config_key',
        'config_name',
        'is_active',

        'date_format',
        'time_format',
        'date_separator',
        'currency_symbol',
        'search_wildcard_enabled',

        'modal_frame_width',
        'modal_frame_height',
        'modal_overlay_close_enabled',

        'label_font_family',
        'label_font_size',
        'label_color',
        'label_font_weight',
        'label_position',

        'input_font_family',
        'input_font_size',
        'input_color',
        'input_margin',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'search_wildcard_enabled' => 'boolean',
        'modal_overlay_close_enabled' => 'boolean',
    ];

    public static function defaults(): array
    {
        return [
            'is_active' => true,

            'date_format' => 'd-m-Y',
            'time_format' => 'h:i:s A',
            'date_separator' => '-',
            'currency_symbol' => '',
            'search_wildcard_enabled' => true,

            'modal_frame_width' => 1048,
            'modal_frame_height' => 600,
            'modal_overlay_close_enabled' => false,

            'label_font_family' => 'Arial',
            'label_font_size' => 20,
            'label_color' => 'black_100',
            'label_font_weight' => 'bold',
            'label_position' => 'top',

            'input_font_family' => 'Arial',
            'input_font_size' => 21,
            'input_color' => 'gray_75',
            'input_margin' => 8,
        ];
    }


    public function getResolvedDateFormatAttribute(): string
    {
        $dateFormat = $this->date_format ?: 'Y-m-d';
        $dateSeparator = $this->date_separator ?: '-';

        return str_replace(['-', '/', '.'], $dateSeparator, $dateFormat);
    }

    public function getResolvedDateTimeFormatAttribute(): string
    {
        $timeFormat = $this->time_format ?: 'H:i';

        return trim($this->resolved_date_format . ' ' . $timeFormat);
    }
}
