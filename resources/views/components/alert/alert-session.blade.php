@php
    $alertTypes = ['success', 'danger', 'warning', 'info', 'primary', 'secondary', 'dark'];
@endphp

@foreach ($alertTypes as $alertType)
    @if (session($alertType))
        <x-alert.alert :type="$alertType">
            {{ session($alertType) }}
        </x-alert.alert>
    @endif
@endforeach

{{-- 
Example usage

<x-alert.alert-session />

@include('components.alert.alert-session')

--}}