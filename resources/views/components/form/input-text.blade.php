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
    $inputType = 'input';

    $finalStyle = \App\Support\FormInputStyle::make($appConfig ?? null, $customStyle, $inputType);
@endphp

<input
    id="{{ $fieldId }}"
    type="{{ $type }}"
    name="{{ $name }}"
    value="{{ $type !== 'password' ? $fieldValue : '' }}"
    placeholder="{{ $placeholder }}"
    @required($required)
    @readonly($readonly)
    @disabled($disabled)
    {{ $attributes->except('style')->class([
        'form-control',
        'is-invalid' => $errors->has($fieldNameDot),
    ]) }}
    style="{{ $finalStyle }}"
>

@error($fieldNameDot)
    <div class="invalid-feedback d-block">{{ $message }}</div>
@enderror

{{-- 
Example usage

<x-form.input-text
    name="company_name"
    :value="$company->company_name ?? ''"
    placeholder="Enter company name"
    :required="true"
/>

<x-form.input-text
    name="label_font_size"
    type="number"
    :value="$configuration->label_font_size ?? 14"
    min="1"
    max="100"
    step="1"
/>

<x-form.input-text
    name="contact_email"
    type="email"
    :value="$company->contact_email ?? ''"
    placeholder="example@domain.com"
    maxlength="255"
/>
--}}