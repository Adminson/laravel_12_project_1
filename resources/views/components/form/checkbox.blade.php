@props([
    'name',
    'id' => null,
    'label' => null,
    'checked' => false,
    'value' => 1,
    'disabled' => false,
])

@php
    $fieldId = $id ?? $name;
    $isChecked = old($name, $checked);
@endphp

<div class="form-check">
    <input
        type="checkbox"
        id="{{ $fieldId }}"
        name="{{ $name }}"
        value="{{ $value }}"
        @checked($isChecked)
        @disabled($disabled)
        {{ $attributes->class([
            'form-check-input',
            'is-invalid' => $errors->has($name),
        ]) }}
    >

    @if ($label)
        <label for="{{ $fieldId }}" class="form-check-label">{{ $label }}</label>
    @endif
</div>

@error($name)
    <div class="invalid-feedback d-block">{{ $message }}</div>
@enderror

{{-- 
Example usage

<x-form.checkbox
    name="is_active"
    id="is_active"
    label="Active"
    :checked="$company->is_active ?? true"
/>

<x-form.checkbox
    name="allow_login"
    id="allow_login"
    label="Allow Login"
    :checked="old('allow_login', $company->allow_login ?? false)"
/>

<x-form.checkbox
    name="send_notification"
    id="send_notification"
    label="Send Notification Email"
    :checked="true"
    :disabled="false"
/>
--}}


