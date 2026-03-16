@props([
    'name',
    'id' => null,
    'options' => [],
    'optionAttributes' => [],
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'wrapperClass' => '',
])

@php
    $id = $id ?? $name;
    $selectedValue = old($name, $value);
    $isMultiple = filter_var($multiple, FILTER_VALIDATE_BOOLEAN);

    $fieldNameDot = str_replace(['[', ']'], ['.', ''], $name);
    $errorClass = $errors->has($fieldNameDot) ? ' is-invalid' : '';

    $customStyle = $attributes->get('style');
    $inputType = 'input-select2';

    $finalStyle = \App\Support\FormInputStyle::make($appConfig, $customStyle, $inputType);

    $wrapperStyle = sprintf('margin-bottom: %spx;', $appConfig->input_margin);
@endphp

<div
    class="select2-field-wrapper {{ $wrapperClass }}"
    style="{{ $wrapperStyle }}"
>
    <select
        name="{{ $isMultiple ? $name . '[]' : $name }}"
        id="{{ $id }}"
        @if ($isMultiple) multiple @endif
        @if ($required) required @endif
        @if ($disabled) disabled @endif
        {{ $attributes->except('style', 'class')->merge([
            'class' => 'form-select js-input-select2' . $errorClass,
            'style' => $finalStyle,
        ]) }}
    >
        @if (!$isMultiple && !is_null($placeholder))
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $optionValue => $optionLabel)
            @php
                $isSelected = false;

                if ($isMultiple) {
                    $selectedItems = is_array($selectedValue)
                        ? $selectedValue
                        : (filled($selectedValue)
                            ? [$selectedValue]
                            : []);

                    $isSelected = in_array((string) $optionValue, array_map('strval', $selectedItems), true);
                } else {
                    $isSelected = (string) $selectedValue === (string) $optionValue;
                }

                $attrs = $optionAttributes[$optionValue] ?? [];
            @endphp

            <option
                value="{{ $optionValue }}"
                @selected($isSelected)
                @foreach ($attrs as $attrKey => $attrValue)
                    {{ $attrKey }}="{{ $attrValue }}" @endforeach
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @error($fieldNameDot)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
