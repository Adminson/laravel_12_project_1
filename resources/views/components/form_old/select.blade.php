{{-- resources/views/components/form/select.blade.php --}}
@props([
    'name',
    'label',
    'options' => [],
    'value' => null,
    'required' => false,
    'col' => 'col-md-6',
    'placeholder' => 'Please select',
    'id' => null,
    'help' => null,
    'disabled' => false,
])

@php
    $fieldNameDot = str_replace(['][', '[', ']'], ['.', '.', ''], $name);
    $fieldId = $id ?: \Illuminate\Support\Str::slug(str_replace(['][', '[', ']'], ['_', '_', ''], $name), '_');
    $fieldValue = (string) old($fieldNameDot, $value);
@endphp

<div class="{{ $col }} mb-3">
    <label for="{{ $fieldId }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <select
        id="{{ $fieldId }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->class(['form-select', 'is-invalid' => $errors->has($fieldNameDot)]) }}
    >
        @if(!is_null($placeholder))
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $optionValue === $fieldValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif

    @error($fieldNameDot)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>