{{-- resources/views/components/admin/section-card.blade.php --}}
@props(['title', 'class' => 'mt-4'])

<div class="card {{ $class }}">

    <div class="card-header pb-0 d-flex justify-content-between">
        <h5 class="card-title">Card title</h5>
    </div>

    <div class="card-body">
        <div class="divider divider-info">
            <h4 class="divider-text">{{ $title }}</h4>
        </div>

        {{ $slot }}
    </div>
</div>
