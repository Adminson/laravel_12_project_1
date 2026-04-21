@props([
    'title',
    'subTitle' => null,
    'badge' => null,
    'badgeType' => 'success',
    'rightText' => null,
    'class' => 'mt-4',
    'bodyClass' => '',
])

@php
    $allowedBadgeTypes = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];

    $badgeText = null;
    $finalBadgeType = $badgeType;

    if (is_array($badge)) {
        $badgeText = $badge['text'] ?? null;
        $finalBadgeType = $badge['type'] ?? $badgeType;
    } else {
        $badgeText = $badge;
    }

    if (!in_array($finalBadgeType, $allowedBadgeTypes, true)) {
        $finalBadgeType = 'success';
    }
@endphp

<div {{ $attributes->class(['card', $class]) }}>
    <div class="card-header pb-0 d-flex justify-content-between align-items-start gap-3">
        <div class="flex-grow-1">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2 flex-wrap">
                <span>{{ $title }}</span>

                @if ($badgeText)
                    <small>
                        <span class="badge text-bg-{{ $finalBadgeType }}">
                            {{ $badgeText }}
                        </span>
                    </small>
                @endif
            </h5>

            @if ($subTitle)
                <small class="text-muted">{{ $subTitle }}</small>
            @endif
        </div>

        @if (isset($headerActions) || $rightText)
            <div class="text-end flex-shrink-0">
                @isset($headerActions)
                    {{ $headerActions }}
                @elseif ($rightText)
                    <small class="text-muted">{{ $rightText }}</small>
            @endif
        </div>
        @endif
    </div>

    <div class="card-body {{ $bodyClass }}">
        {{ $slot }}
    </div>
    </div>



    {{-- 
Example usage

<x-admin.section-card
    title="Company Details"
    sub-title="Basic company profile information"
    badge="Required"
    badge-type="primary"
    :right-text="now()->format('d/m/Y H:i')"
>
    ...
</x-admin.section-card>
============================================================================================================================================================
<x-admin.section-card
    title="Subscription & Status"
    :badge="['text' => 'Active', 'type' => 'success']"
    class="mt-3 shadow-sm"
    body-class="pt-2"
>
    ...
</x-admin.section-card>
============================================================================================================================================================
<x-admin.section-card
    title="System Info"
    badge="Read Only"
    badge-type="dark"
    :right-text="'Last updated by Admin'"
>
    ...
</x-admin.section-card>
============================================================================================================================================================
<x-card.section-card title="Header & Footer">
    <x-slot name="headerActions">
        <button
            type="button"
            id="testPdfBtn"
            class="btn btn-primary"
        >
            Test PDF Generation
        </button>
    </x-slot>
</x-admin.section-card>

============================================================================================================================================================
if multiple buttons
<x-card.section-card title="Header & Footer">
    <x-slot name="headerActions">
        <div class="d-flex gap-2">
            <a href="{{ route('setting.company.index') }}" class="btn btn-label-secondary">
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                {{ $isEdit ? 'Save Changes' : 'Create Company' }}
            </button>
        </div>
    </x-slot>

    <div class="row">
        ...
    </div>
</x-card.section-card>
--}}
