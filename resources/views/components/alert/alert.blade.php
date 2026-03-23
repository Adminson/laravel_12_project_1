@props([
    'type' => 'primary',
    'dismissible' => true,
    'show' => true,
])

@php
    $allowedTypes = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark', 'light'];

    $type = in_array($type, $allowedTypes) ? $type : 'primary';

    $classes = 'alert alert-' . $type;

    if ($dismissible) {
        $classes .= ' alert-dismissible fade show';
    }
@endphp

@if ($show)
    <div {{ $attributes->merge(['class' => $classes, 'role' => 'alert']) }}>
        {{ $slot }}

        @if ($dismissible)
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>
        @endif
    </div>
@endif


{{-- 
Example usage

<x-alert.alert type="success">
    Company saved successfully.
</x-alert.alert>

<x-alert.alert type="danger" :dismissible="false">
    Failed to save record.
</x-alert.alert>

<x-alert.alert type="warning" class="mt-3">
    Please double check the required fields.
</x-alert.alert>
--}}