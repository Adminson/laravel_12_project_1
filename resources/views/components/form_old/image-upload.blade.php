{{-- resources/views/components/form/image-upload.blade.php --}}
@props([
    'name',
    'label' => 'Image',
    'preview' => null,
    'accept' => 'image/*',
    'id' => null,
    'previewId' => null,
    'colInput' => 'col-md-6',
    'colPreview' => 'col-md-6',
    'previewLabel' => 'Image Preview',
])

@php
    $fieldNameDot = str_replace(['][', '[', ']'], ['.', '.', ''], $name);
    $fieldId = $id ?: \Illuminate\Support\Str::slug(str_replace(['][', '[', ']'], ['_', '_', ''], $name), '_');
    $previewId = $previewId ?: $fieldId . '_preview';
    $previewSrc = $preview ?: 'https://placehold.co/600x400?text=Preview';
@endphp

<div class="{{ $colInput }} mb-3">
    <label for="{{ $fieldId }}" class="form-label">{{ $label }}</label>

    <input
        id="{{ $fieldId }}"
        type="file"
        name="{{ $name }}"
        accept="{{ $accept }}"
        data-preview-target="{{ $previewId }}"
        {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($fieldNameDot)]) }}
    >

    @error($fieldNameDot)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="{{ $colPreview }} mb-3">
    <label class="form-label">{{ $previewLabel }}</label>
    <div>
        <img
            id="{{ $previewId }}"
            src="{{ $previewSrc }}"
            alt="{{ $label }} Preview"
            class="img-thumbnail"
            style="max-width: 180px; max-height: 120px;"
        >
    </div>
</div>