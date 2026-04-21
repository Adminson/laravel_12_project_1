@props([
    'title' => 'Memo / Notes',
    'memoableType',
    'memoableId',
    'memos' => collect(),
    'storeRoute' => route('setting.memos.store'),
    'deleteRouteName' => 'setting.memos.destroy',
])

@if (!$memoableId)
    <x-alert.alert type="warning">
        Please save the record first before adding memo.
    </x-alert.alert>
@else
    <div class="row g-2 align-items-end">
        <div class="col-12">
            <x-form.input-label
                for="memo_content_editor"
                value="New Memo"
            />

            <x-form.input-quill
                name="memo_content"
                id="memo_content"
                placeholder="Write memo, remarks, follow-up notes, internal comments..."
            />
        </div>

        <div class="col-md-12 text-md-end">
            <button
                type="button"
                class="btn btn-primary"
                id="btnSaveMemo"
                data-memoable-type="{{ $memoableType }}"
                data-memoable-id="{{ $memoableId }}"
                data-store-url="{{ $storeRoute }}"
            >
                Save Memo
            </button>
        </div>
    </div>
@endif

<div class="memo-history mt-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h6 class="mb-0">Memo History</h6>
            <small class="text-muted">
                {{ $memos->count() }} record(s)
            </small>
        </div>
    </div>

    <div id="memoListWrapper">
        @forelse ($memos as $memo)
            <div
                class="memo-item card border shadow-none mb-2"
                id="memo-item-{{ $memo->id }}"
            >
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <span class="fw-semibold text-dark">
                                    {{ $memo->created_by ?: 'Unknown' }}
                                </span>

                                <span class="badge text-bg-light border text-muted fw-normal">
                                    {{ $memo->created_at?->format($appConfig->resolved_date_time_format) ?? '-' }}
                                </span>
                            </div>

                            <div class="memo-content text-body-secondary">
                                {!! $memo->content !!}
                            </div>
                        </div>

                        <div class="memo-action">
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger btn-delete-memo"
                                data-id="{{ $memo->id }}"
                                data-url="{{ route($deleteRouteName, $memo->id) }}"
                                title="Delete memo"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div
                id="emptyMemoText"
                class="border rounded-3 p-4 text-center text-muted bg-light-subtle"
            >
                <div class="mb-1 fw-semibold">No memo available</div>
                <small>Add the first note for this record.</small>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
    <style>
        .memo-history .memo-item {
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }

        .memo-history .memo-item:hover {
            box-shadow: 0 .125rem .5rem rgba(0, 0, 0, 0.06);
            border-color: rgba(var(--bs-primary-rgb), 0.25);
        }

        .memo-content {
            line-height: 1.55;
            word-break: break-word;
        }

        .memo-content p:last-child {
            margin-bottom: 0;
        }

        .memo-content ul,
        .memo-content ol {
            padding-left: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .memo-action {
            flex-shrink: 0;
        }

        @media (max-width: 767.98px) {
            .memo-action {
                width: 100%;
                margin-top: 0.75rem;
            }

            .memo-action .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function escapeHtml(text) {
            return $('<div>').text(text ?? '').html();
        }

        function buildMemoHtml(memo, deleteRouteBase = null) {
            const deleteUrl = deleteRouteBase ?
                deleteRouteBase.replace('__ID__', memo.id) :
                '';

            return `
                <div class="memo-item card border shadow-none mb-2" id="memo-item-${memo.id}">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                    <span class="fw-semibold text-dark">${escapeHtml(memo.created_by ?? 'Unknown')}</span>
                                    <span class="badge text-bg-light border text-muted fw-normal">
                                        ${escapeHtml(memo.created_at ?? '')}
                                    </span>
                                </div>

                                <div class="memo-content text-body-secondary">
                                    ${memo.content ?? ''}
                                </div>
                            </div>

                            <div class="memo-action">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger btn-delete-memo"
                                    data-id="${memo.id}"
                                    data-url="${deleteUrl}"
                                    title="Delete memo"
                                >
                                    <i class="ti ti-trash me-1"></i>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function isQuillEmpty(quill) {
            const text = quill.getText().trim();
            const html = quill.root.innerHTML.trim();

            return text.length === 0 || html === '<p><br></p>';
        }

        $(function() {
            let memoQuill = null;

            if (document.getElementById('memo_content_editor')) {
                memoQuill = new Quill('#memo_content_editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{
                                header: [1, 2, 3, 4, 5, 6, false]
                            }],
                            [{
                                font: []
                            }],
                            [{
                                size: ['small', false, 'large', 'huge']
                            }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{
                                color: []
                            }, {
                                background: []
                            }],
                            [{
                                script: 'sub'
                            }, {
                                script: 'super'
                            }],
                            [{
                                list: 'ordered'
                            }, {
                                list: 'bullet'
                            }],
                            [{
                                indent: '-1'
                            }, {
                                indent: '+1'
                            }],
                            [{
                                align: []
                            }],
                            ['blockquote', 'code-block'],
                            ['link', 'image', 'video', 'formula'],
                            ['clean']
                        ]
                    }
                });

                memoQuill.on('text-change', function() {
                    $('#memo_content').val(memoQuill.root.innerHTML);
                });
            }

            $('#btnSaveMemo').on('click', function() {
                const $btn = $(this);

                if (!memoQuill || isQuillEmpty(memoQuill)) {
                    Swal.fire('Validation', 'Memo content is required.', 'warning');
                    return;
                }

                const content = memoQuill.root.innerHTML;
                $('#memo_content').val(content);

                $.ajax({
                    url: $btn.data('store-url'),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        memoable_type: $btn.data('memoable-type'),
                        memoable_id: $btn.data('memoable-id'),
                        content: content,
                    },
                    success: function(res) {
                        memoQuill.setContents([]);
                        $('#memo_content').val('');
                        $('#emptyMemoText').remove();

                        const deleteRouteBase =
                            '{{ route('setting.memos.destroy', '__ID__') }}';
                        $('#memoListWrapper').prepend(buildMemoHtml(res.data, deleteRouteBase));

                        Swal.fire('Success', res.message, 'success');

                        location.reload();
                    },
                    error: function(xhr) {
                        let message = xhr.responseJSON?.message || 'Failed to save memo.';

                        if (xhr.status === 422 && xhr.responseJSON?.errors?.content?.[0]) {
                            message = xhr.responseJSON.errors.content[0];
                        }

                        Swal.fire('Error', message, 'error');
                    }
                });
            });

            $(document).on('click', '.btn-delete-memo', function() {
                const url = $(this).data('url');
                const memoId = $(this).data('id');

                Swal.fire({
                    title: 'Delete memo?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE',
                        },
                        success: function(res) {
                            $('#memo-item-' + memoId).remove();

                            if ($('.memo-item').length === 0) {
                                $('#memoListWrapper').html(`
                                    <div
                                        id="emptyMemoText"
                                        class="border rounded-3 p-4 text-center text-muted bg-light-subtle"
                                    >
                                        <div class="mb-1 fw-semibold">No memo available</div>
                                        <small>Add the first note for this record.</small>
                                    </div>
                                `);
                            }

                            Swal.fire('Deleted', res.message, 'success');
                        },
                        error: function(xhr) {
                            const message = xhr.responseJSON?.message ||
                                'Failed to delete memo.';
                            Swal.fire('Error', message, 'error');
                        }
                    });
                });
            });
        });
    </script>
@endpush


{{-- 
=============================NOTES=============================
- This is a reusable Blade component for displaying and managing memos/notes related to a specific record (e.g., company, system message, etc.).
1st : at at model :
    public function memos(): MorphMany
    {
        return $this->morphMany(Memo::class, 'memoable')->latest();
    }

2nd : at controller :
    $systemMessage->load('memos');

3rd : at MemoController :
    private function allowedMemoableTypes(): array
    {
        return [
            'company'           => \App\Models\CompanyProfile::class,
            'system_message'    => \App\Models\SystemMessage::class,
            'configuration'     => \App\Models\UiConfiguration::class,
            'payment'           => \App\Models\Payment::class,
            'user'              => \App\Models\User::class,
        ];
    }

4th : at blade view include :
     @include('admin.memo._memo-panel_accordion', [
         'title' => 'Memo / Notes',
         'memoableType' => 'system_message',
         'memoableId' => $systemMessage->msg_id,
         'memos' => $systemMessage->memos,
     ])

--}}
