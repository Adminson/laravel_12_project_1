@props([
    'name',
    'id' => null,
    'required' => false,
    'disabled' => false,
])

@php
    $fieldId = $id ?? $name;
    $fieldNameDot = str_replace(['[', ']'], ['.', ''], $name);
@endphp

<input
    type="file"
    id="{{ $fieldId }}"
    name="{{ $name }}"
    @required($required)
    @disabled($disabled)
    {{ $attributes->class([
        'form-control',
        'is-invalid' => $errors->has($fieldNameDot),
    ]) }}
>

@error($fieldNameDot)
    <div class="invalid-feedback d-block">{{ $message }}</div>
@enderror

{{-- 
Example usage

<x-form.input-file
    name="logo"
    id="logo"
    accept="image/*"
/>

<x-form.input-file
    name="attachment"
    id="attachment"
    accept=".pdf,.doc,.docx"
    :required="true"
/>

<x-form.input-file
    name="import_file"
    id="import_file"
    accept=".xlsx,.csv"
    class="mt-1"
/>
--}}