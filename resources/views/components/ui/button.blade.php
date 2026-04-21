@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => null,
    'icon' => null,
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
        'outline-primary' => 'btn-outline-primary',
        'outline-secondary' => 'btn-outline-secondary',
        'outline-success' => 'btn-outline-success',
        'outline-danger' => 'btn-outline-danger',
        'outline-warning' => 'btn-outline-warning',
        'outline-info' => 'btn-outline-info',
        'outline-light' => 'btn-outline-light',
        'outline-dark' => 'btn-outline-dark',
        default => 'btn-primary',
    };

    $sizeClass = match ($size) {
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        default => '',
    };

    $classes = trim($baseClass . ' ' . $variantClass . ' ' . $sizeClass);
@endphp

<button
    type="{{ $type }}"
    @disabled($disabled)
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if ($icon)
        <i class="{{ $icon }} me-1"></i>
    @endif

    <span>{{ $slot }}</span>
</button>


{{-- 

<x-ui.button
    type="button"
    variant="success"
    size="sm"
    id="audit_log_btn"
    icon="ti ti-eye"
    data-audit-url="{{ route('setting.audit.audit-list', [$auditId, $auditType]) }}"
>
    View Log
</x-ui.button>

<x-ui.button
    type="submit"
    variant="primary"
>
    {{ $isEdit ? 'Save Changes' : 'Create Company' }}
</x-ui.button>


--}}
