{{-- resources/views/components/form/textarea.blade.php --}}
@props([
    'name',
    'label',
    'value' => null,
    'required' => false,
    'rows' => 3,
    'col' => 'col-md-12',
    'placeholder' => null,
    'id' => null,
    'help' => null,
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

    <textarea
        id="{{ $fieldId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($fieldNameDot)]) }}
    >{{ $fieldValue }}</textarea>

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif

    @error($fieldNameDot)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>