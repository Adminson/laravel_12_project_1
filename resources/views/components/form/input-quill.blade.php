@props([
    'name',
    'id' => null,
    'value' => '',
    'placeholder' => 'Write here...',
    'required' => false,
    'height' => 180,
])

@php
    $fieldId = $id ?? $name;
    $editorId = $fieldId . '_editor';
    $fieldNameDot = str_replace(['[', ']'], ['.', ''], $name);
@endphp

<input
    type="hidden"
    name="{{ $name }}"
    id="{{ $fieldId }}"
    value="{{ old($name, $value) }}"
    {{ $required ? 'required' : '' }}
>

<div
    id="{{ $editorId }}"
    class="quill-editor bg-white"
    data-input-id="{{ $fieldId }}"
    data-placeholder="{{ $placeholder }}"
    style="min-height: {{ $height }}px;"
>{!! old($name, $value) !!}</div>

@error($fieldNameDot)
    <div class="invalid-feedback d-block">{{ $message }}</div>
@enderror


{{-- 
Example usage

<x-form.input-quill
    name="memo_content"
    id="memo_content"
    placeholder="Write memo, remarks, follow-up notes, internal comments..."
/>


memo_content -> memo_content_editor


let memoQuill = null;

if (document.getElementById('memo_content_editor')) {
    memoQuill = new Quill('#memo_content_editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, 4, 5, 6, false] }],
                [{ font: [] }],
                [{ size: ['small', false, 'large', 'huge'] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ color: [] }, { background: [] }],
                [{ script: 'sub' }, { script: 'super' }],
                [{ list: 'ordered' }, { list: 'bullet' }],
                [{ indent: '-1' }, { indent: '+1' }],
                [{ align: [] }],
                ['blockquote', 'code-block'],
                ['link', 'image', 'video', 'formula'],
                ['clean']
            ]
        }
    });

    memoQuill.on('text-change', function () {
        $('#memo_content').val(memoQuill.root.innerHTML);
    });
}


--}}
