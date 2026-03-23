@props([
    'for' => null,
    'value' => null,
    'required' => false,
])

@php
    $labelFontSize = $appConfig->label_font_size ?? 16;
    $labelFontFamily = $appConfig->label_font_family ?? 'inherit';
    $labelColor = $appConfig->label_color ?? 'inherit';
    $labelFontWeight = $appConfig->label_font_weight ?? '400';

    $defaultStyle = "
        font-size: {$labelFontSize}px;
        font-family: {$labelFontFamily};
        color: {$labelColor};
        font-weight: {$labelFontWeight};
    ";

    $customStyle = $attributes->get('style');
    $finalStyle = trim($defaultStyle . ' ' . ($customStyle ?? ''));
@endphp

<label
    @if($for) for="{{ $for }}" @endif
    {{ $attributes->except('style')->merge(['class' => 'form-label']) }}
    style="{{ $finalStyle }}"
>
    {{ $value ?? $slot }}

    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>

{{-- 
Example usage

<x-form.input-label for="company_name" value="Company Name" :required="true" />

<x-form.input-label for="config_key">
    Configuration Key
</x-form.input-label>

<x-form.input-label
    for="email"
    value="Email Address"
    class="fw-bold"
    style="letter-spacing: .3px;"
/>
--}}