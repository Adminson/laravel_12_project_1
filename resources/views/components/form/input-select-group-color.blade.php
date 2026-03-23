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
    'select2' => false,
])

@php
    $id = $id ?? $name;
    $selectedValue = old($name, $value);
    $isMultiple = filter_var($multiple, FILTER_VALIDATE_BOOLEAN);

    $baseClass = 'form-select';
    $select2Class = $select2 ? ' js-select2' : '';
    $errorClass = $errors->has($name) ? ' is-invalid' : '';

    $customStyle = $attributes->get('style');
    $inputType = 'group-input';

    $finalStyle = \App\Support\FormInputStyle::make($appConfig, $customStyle, $inputType);
@endphp

<div
    class="input-group"
    style="margin-bottom:{{ $appConfig->input_margin ?? 0 }}px"
>
    <select
        name="{{ $isMultiple ? $name . '[]' : $name }}"
        id="{{ $id }}"
        @if ($isMultiple) multiple @endif
        @if ($required) required @endif
        @if ($disabled) disabled @endif
        {{ $attributes->except('style')->merge([
            'class' => $baseClass . $select2Class . $errorClass,
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
    <span class="input-group-text bg-white">
        <span
            id="label_color_preview"
            class="d-inline-block rounded border"
            style="width: 18px; height: 18px; background-color: {{ $selectedValue }};"
        ></span>
    </span>
</div>
@error($name)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror


{{-- 
Example usage

<x-form.input-select2
    name="country_id"
    :options="$countryOptions"
    :value="$company->country_id ?? ''"
    placeholder="Select country"
    :required="true"
/>

<x-form.input-select2
    name="user_ids"
    :options="$userOptions"
    :value="$selectedUsers ?? []"
    :multiple="true"
    placeholder="Select users"
/>

<x-form.input-select2
    name="product_category_id"
    :options="$categoryOptions"
    :value="old('product_category_id')"
    wrapper-class="mb-0"
/>
--}}