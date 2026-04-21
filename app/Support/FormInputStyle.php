<?php

namespace App\Support;

class FormInputStyle
{
    public static function config($appConfig): array
    {
        return [
            'font_family' => $appConfig->input_font_family ?? 'inherit',
            'font_size' => $appConfig->input_font_size ?? 14,
            'color' => $appConfig->input_color ?? '#212529',
            'margin' => $appConfig->input_margin ?? 0,
        ];
    }

    public static function make($appConfig, ?string $customStyle = null, string $inputType = null): string
    {
        $config = static::config($appConfig);

        if ($inputType == 'input') {
            $defaultStyle = sprintf(
                'font-family: %s; font-size: %spx; color: %s; margin-bottom: %spx;',
                $config['font_family'],
                $config['font_size'],
                $config['color'],
                $config['margin']
            );
        } else if ($inputType == 'group-input') {
            $defaultStyle = sprintf(
                'font-family: %s; font-size: %spx; color: %s;',
                $config['font_family'],
                $config['font_size'],
                $config['color']
            );
        } else if ($inputType == 'input-select2') {
            $defaultStyle = sprintf(
                'font-family: %s; font-size: %spx; color: %s;',
                $config['font_family'],
                $config['font_size'],
                $config['color']
            );
        } else {
            $defaultStyle = sprintf(
                'font-family: %s; font-size: %spx; color: %s; margin-bottom: %spx;',
                $config['font_family'],
                $config['font_size'],
                $config['color'],
                $config['margin']
            );
        }


        return trim($defaultStyle . ' ' . ($customStyle ?? ''));
    }
}
