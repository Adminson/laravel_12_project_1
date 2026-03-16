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
