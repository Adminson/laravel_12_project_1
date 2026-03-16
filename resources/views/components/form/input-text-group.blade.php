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
