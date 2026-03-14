@props([
    'field',
    'model' => null,
    'value' => null,
])

@php
    $type = $field['type'] ?? 'text';
    $name = $field['name'];
    $oldKey = $field['old_key'] ?? $name;
    $valuePath = $field['value_path'] ?? $oldKey;
    $errorKey = $field['error_key'] ?? $oldKey;
    $defaultValue = $field['default'] ?? null;
    $resolvedValue = old($oldKey, $value ?? data_get($model, $valuePath, $defaultValue));
    $fieldId = $field['id'] ?? \Illuminate\Support\Str::of($name)
        ->replace(['][', '[', ']', '.'], ['_', '_', '', '_'])
        ->trim('_')
        ->toString();
    $colClass = $field['col'] ?? 'col-12';
    $required = $field['required'] ?? false;
    $helpText = $field['help'] ?? null;
    $placeholder = $field['placeholder'] ?? null;
    $isInvalid = $errors->has($errorKey);
    $baseClass = in_array($type, ['checkbox', 'switch'], true) ? 'form-check-input' : 'form-control';
    $normalizedValue = $resolvedValue;

    if ($normalizedValue instanceof \DateTimeInterface && $type === 'datetime-local') {
        $normalizedValue = $normalizedValue->format('Y-m-d\TH:i');
    } elseif (is_bool($normalizedValue)) {
        $normalizedValue = $normalizedValue ? '1' : '0';
    }

    if ($type === 'select') {
        $baseClass = 'form-select';
    }

    $inputClass = trim($baseClass . ' ' . ($field['input_class'] ?? '') . ($isInvalid ? ' is-invalid' : ''));
@endphp

<div class="{{ $colClass }}">
    @if (in_array($type, ['checkbox', 'switch'], true))
        <div class="form-check {{ $type === 'switch' ? 'form-switch' : '' }} mt-2">
            <input type="hidden" name="{{ $name }}" value="{{ $field['unchecked_value'] ?? 0 }}">
            <input
                type="checkbox"
                name="{{ $name }}"
                id="{{ $fieldId }}"
                value="{{ $field['checked_value'] ?? 1 }}"
                class="{{ $inputClass }}"
                @checked(filter_var($resolvedValue, FILTER_VALIDATE_BOOLEAN) || (string) $resolvedValue === (string) ($field['checked_value'] ?? 1))
            >
            <label class="form-check-label" for="{{ $fieldId }}">{{ $field['label'] }}</label>
        </div>
    @else
        <label class="form-label" for="{{ $fieldId }}">
            {{ $field['label'] }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>

        @if ($type === 'textarea')
            <textarea
                name="{{ $name }}"
                id="{{ $fieldId }}"
                class="{{ $inputClass }}"
                rows="{{ $field['rows'] ?? 3 }}"
                @if ($placeholder) placeholder="{{ $placeholder }}" @endif
                @if (!empty($field['disabled'])) disabled @endif
            >{{ $resolvedValue }}</textarea>
        @elseif ($type === 'select')
            <select
                name="{{ $name }}"
                id="{{ $fieldId }}"
                class="{{ $inputClass }}"
                @if (!empty($field['disabled'])) disabled @endif
            >
                @foreach ($field['options'] ?? [] as $optionValue => $optionLabel)
                    @php
                        $resolvedOptionValue = is_array($optionLabel) ? $optionLabel['value'] : $optionValue;
                        $resolvedOptionLabel = is_array($optionLabel) ? $optionLabel['label'] : $optionLabel;
                    @endphp
                    <option value="{{ $resolvedOptionValue }}" @selected((string) $normalizedValue === (string) $resolvedOptionValue)>
                        {{ $resolvedOptionLabel }}
                    </option>
                @endforeach
            </select>
        @else
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $fieldId }}"
                class="{{ $inputClass }}"
                @if ($type !== 'file') value="{{ $normalizedValue }}" @endif
                @if ($placeholder) placeholder="{{ $placeholder }}" @endif
                @if (!empty($field['accept'])) accept="{{ $field['accept'] }}" @endif
                @if (isset($field['min'])) min="{{ $field['min'] }}" @endif
                @if (isset($field['max'])) max="{{ $field['max'] }}" @endif
                @if (isset($field['step'])) step="{{ $field['step'] }}" @endif
                @if (!empty($field['disabled'])) disabled @endif
            >
        @endif
    @endif

    @if ($helpText)
        <div class="form-text">{{ $helpText }}</div>
    @endif

    @error($errorKey)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
