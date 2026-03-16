{{-- resources/views/components/form/input.blade.php --}}
@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'col' => 'col-md-6',
    'placeholder' => null,
    'id' => null,
    'help' => null,
    'readonly' => false,
    'disabled' => false,
])

@php
    $fieldNameDot = str_replace(['][', '[', ']'], ['.', '.', ''], $name);
    $fieldId = $id ?: \Illuminate\Support\Str::slug(str_replace(['][', '[', ']'], ['_', '_', ''], $name), '_');
    $fieldValue = old($fieldNameDot, $value);
@endphp

<div class="{{ $col }} mb-3">
    <label for="{{ $fieldId }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <input
        id="{{ $fieldId }}"
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $type !== 'password' ? $fieldValue : '' }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($fieldNameDot)]) }}
    >

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif

    @error($fieldNameDot)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>