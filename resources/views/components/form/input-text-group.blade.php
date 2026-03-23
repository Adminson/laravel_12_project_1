@props([
    'name',
    'id' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'readonly' => false,
    'disabled' => false,
])

@php
    $fieldId = $id ?? $name;
    $fieldNameDot = str_replace(['[', ']'], ['.', ''], $name);
    $fieldValue = old($name, $value);

    $customStyle = $attributes->get('style');
    $inputType = 'group-input';

    $finalStyle = \App\Support\FormInputStyle::make($appConfig, $customStyle, $inputType);

    $wrapperStyle = sprintf('margin-bottom: %spx;', $appConfig->input_margin);
@endphp
<div
    class="input-group"
    style="{{ $wrapperStyle }}"
>
    <input
        id="{{ $fieldId }}"
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $type !== 'password' ? $fieldValue : '' }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->except('style')->class(['form-control', 'is-invalid' => $errors->has($fieldNameDot)]) }}
        style="{{ $finalStyle }}"
    >
    <span class="input-group-text">px</span>
</div>
@error($fieldNameDot)
    <div class="invalid-feedback d-block">{{ $message }}</div>
@enderror


{{-- 
Example usage

<x-form.input-text-group
    name="label_font_size"
    type="number"
    :value="$configuration->label_font_size ?? 14"
    min="1"
    max="100"
/>

<x-form.input-text-group
    name="input_padding"
    type="number"
    :value="$configuration->input_padding ?? 10"
    min="0"
    max="50"
    step="1"
/>

<x-form.input-text-group
    name="card_border_radius"
    type="number"
    :value="$configuration->card_border_radius ?? 8"
    min="0"
    max="100"
/>
--}}