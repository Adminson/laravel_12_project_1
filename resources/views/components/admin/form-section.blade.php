@props([
    'title',
    'description' => null,
    'icon' => 'tabler-settings',
    'class' => '',
])

<div class="card shadow-none border {{ $class }}">
    <div class="card-header">
        <div class="d-flex align-items-start gap-3">
            <span class="avatar avatar-sm bg-label-primary">
                <i class="icon-base ti {{ $icon }}"></i>
            </span>
            <div>
                <h5 class="mb-1">{{ $title }}</h5>
                @if ($description)
                    <p class="mb-0 text-muted">{{ $description }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4">
            {{ $slot }}
        </div>
    </div>
</div>
