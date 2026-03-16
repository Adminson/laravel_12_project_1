<?php
// app/Http/Requests/Admin/UiConfigurationRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UiConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'search_wildcard_enabled' => $this->boolean('search_wildcard_enabled'),
            'modal_overlay_close_enabled' => $this->boolean('modal_overlay_close_enabled'),
        ]);
    }

    public function rules(): array
    {
        $uiConfiguration = $this->route('ui_configuration');

        return [
            'config_key' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('ui_configurations', 'config_key')->ignore($uiConfiguration),
            ],
            'config_name' => ['required', 'string', 'max:150'],
            'is_active' => ['required', 'boolean'],

            'date_format' => ['required', Rule::in(array_keys(config('ui-setup.date_formats')))],
            'time_format' => ['required', Rule::in(array_keys(config('ui-setup.time_formats')))],
            'date_separator' => ['required', Rule::in(array_keys(config('ui-setup.date_separators')))],
            'currency_symbol' => ['nullable', 'string', 'max:10'],
            'search_wildcard_enabled' => ['required', 'boolean'],

            'modal_frame_width' => ['required', 'integer', 'min:320', 'max:2000'],
            'modal_frame_height' => ['required', 'integer', 'min:200', 'max:1500'],
            'modal_overlay_close_enabled' => ['required', 'boolean'],

            'label_font_family' => ['required', Rule::in(array_keys(config('ui-setup.font_families')))],
            'label_font_size' => ['required', 'integer', 'min:8', 'max:32'],
            'label_color' => ['required', Rule::in(array_keys(config('ui-setup.colors')))],
            'label_font_weight' => ['required', Rule::in(array_keys(config('ui-setup.font_weights')))],
            'label_position' => ['required', Rule::in(array_keys(config('ui-setup.label_positions')))],

            'input_font_family' => ['required', Rule::in(array_keys(config('ui-setup.font_families')))],
            'input_font_size' => ['required', 'integer', 'min:8', 'max:32'],
            'input_color' => ['required', Rule::in(array_keys(config('ui-setup.colors')))],
            'input_margin' => ['required', 'integer', 'min:0', 'max:30'],
        ];
    }
}
