{{-- resources/views/components/form/checkbox.blade.php --}}
@props([
    'name',
    'label',
    'checked' => false,
    'value' => 1,
    'col' => 'col-md-12',
    'id' => null,
    'help' => null,
    'hideHidden' => false,
])

@php
    $fieldNameDot = str_replace(['][', '[', ']'], ['.', '.', ''], $name);
    $fieldId = $id ?: \Illuminate\Support\Str::slug(str_replace(['][', '[', ']'], ['_', '_', ''], $name), '_');
    $oldValue = old($fieldNameDot, $checked ? 1 : 0);
    $isChecked = (string) $oldValue === (string) $value;
@endphp

<div class="{{ $col }} mb-3">
    @if(!$hideHidden)
        <input type="hidden" name="{{ $name }}" value="0">
    @endif

    <div class="form-check">
        <input
            id="{{ $fieldId }}"
            type="checkbox"
            name="{{ $name }}"
            value="{{ $value }}"
            @checked($isChecked)
            {{ $attributes->class(['form-check-input', 'is-invalid' => $errors->has($fieldNameDot)]) }}
        >
        <label class="form-check-label" for="{{ $fieldId }}">
            {{ $label }}
        </label>
    </div>

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif

    @error($fieldNameDot)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>