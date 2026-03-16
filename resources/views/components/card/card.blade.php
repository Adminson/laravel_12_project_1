@props([
    'title',
    'subTitle' => null,
    'rightText' => null,
    'class' => 'mt-4',
])

<div {{ $attributes->merge(['class' => 'card ' . $class]) }}>
    <div class="card-header pb-0 d-flex justify-content-between align-items-start mb-4">
        <div>
            <h5 class="card-title mb-0">{{ $title }}</h5>

            @if ($subTitle)
                <small class="text-muted">{{ $subTitle }}</small>
            @endif
        </div>

        @if ($rightText)
            <div class="text-muted small text-end">
                {{ $rightText }}
            </div>
        @endif
    </div>

    <div class="card-body">
        {{ $slot }}
    </div>
</div>

{{-- 
Example Usage

<x-card.card
    title="Configuration Profile"
    :sub-title="'test Sub'"
    :right-text="'today date is ' . now()->format('Y-m-d H:i:s')"
>
//content
</x-card.card>

--}}