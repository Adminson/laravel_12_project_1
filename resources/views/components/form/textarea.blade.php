@props([
    'name',
    'id' => null,
    'value' => null,
    'rows' => 3,
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

<textarea
    id="{{ $fieldId }}"
    name="{{ $name }}"
    rows="{{ $rows }}"
    placeholder="{{ $placeholder }}"
    @required($required)
    @readonly($readonly)
    @disabled($disabled)
    {{ $attributes->except('style')->class([
        'form-control',
        'is-invalid' => $errors->has($fieldNameDot),
    ]) }}
    style="{{ $finalStyle }}"
>{{ $fieldValue }}</textarea>

@error($fieldNameDot)
    <div class="invalid-feedback d-block">{{ $message }}</div>
@enderror

{{-- 
Example usage

<x-form.textarea
    name="company_address"
    :value="$company->company_address ?? ''"
    rows="3"
    placeholder="Enter full company address"
/>

<x-form.textarea
    name="footer_text"
    :value="$configuration->footer_text ?? ''"
    rows="4"
/>

<x-form.textarea
    name="remarks"
    :value="old('remarks')"
    rows="5"
    maxlength="1000"
/>
--}}