@props([
    'href' => '#',
    'variant' => 'primary',
    'disabled' => false,
])

@php
    $baseClass = 'btn';

    $variantClass = match ($variant) {
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'success' => 'btn-success',
        'danger' => 'btn-danger',
        'warning' => 'btn-warning',
        'info' => 'btn-info',
        'light' => 'btn-light',
        'dark' => 'btn-dark',
        'link' => 'btn-link',
        default => 'btn-primary',
    };

    $disabledClass = $disabled ? ' disabled pe-none opacity-50' : '';
@endphp

<a
    href="{{ $disabled ? '#' : $href }}"
    {{ $attributes->merge([
        'class' => trim($baseClass . ' ' . $variantClass . ' ' . $disabledClass),
    ]) }}
>
    {{ $slot }}
</a>

{{-- 
Example Usage

<x-ui.link
    :href="route('admin.ui-configurations.index')"
    variant="secondary"
    class="me-2"
>
    Back
</x-ui.link>

--}}