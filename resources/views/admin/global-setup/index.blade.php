@extends('layouts.app')

@section('content')
    @php
        $activeTab = (string) request('tab', $profiles->first()?->id ?? 'new');
    @endphp

    <div class="card">
        <div class="card-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h4 class="mb-1">Global Setup Profiles</h4>
                <p class="mb-0 text-muted">Manage reusable UI defaults for localization, modal sizing, label styling and input presentation.</p>
            </div>
            <span class="badge bg-label-primary">Stored in `global_setup_profiles.settings`</span>
        </div>
        <div class="card-body">
            <ul class="nav nav-pills flex-column flex-md-row gap-2 mb-4" id="globalSetupTabs" role="tablist">
                @foreach ($profiles as $profile)
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link {{ $activeTab === (string) $profile->id ? 'active' : '' }}"
                            data-bs-toggle="tab"
                            data-bs-target="#global-setup-pane-{{ $profile->id }}"
                            type="button"
                            role="tab"
                        >
                            Configuration: {{ $profile->code }}
                        </button>
                    </li>
                @endforeach
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link {{ $activeTab === 'new' ? 'active' : '' }}"
                        data-bs-toggle="tab"
                        data-bs-target="#global-setup-pane-new"
                        type="button"
                        role="tab"
                    >
                        New Configuration
                    </button>
                </li>
            </ul>

            <div class="tab-content px-0">
                @foreach ($profiles as $profile)
                    <div
                        class="tab-pane fade {{ $activeTab === (string) $profile->id ? 'show active' : '' }}"
                        id="global-setup-pane-{{ $profile->id }}"
                        role="tabpanel"
                    >
                        @include('admin.global-setup.partials.profile-form', ['profile' => $profile])
                    </div>
                @endforeach

                <div
                    class="tab-pane fade {{ $activeTab === 'new' ? 'show active' : '' }}"
                    id="global-setup-pane-new"
                    role="tabpanel"
                >
                    @include('admin.global-setup.partials.profile-form', ['profile' => $newProfile])
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function resolveFieldName(errorKey) {
            const segments = errorKey.split('.');

            return segments.reduce((carry, segment, index) => {
                if (index === 0) {
                    return segment;
                }

                return `${carry}[${segment}]`;
            }, '');
        }

        function applyServerErrors($form, errors) {
            $form.find('.server-error').remove();
            $form.find('.is-invalid').removeClass('is-invalid');

            $.each(errors, function(key, value) {
                const inputName = resolveFieldName(key);
                const $input = $form.find(`[name="${inputName}"]`).last();

                if (!$input.length) {
                    return;
                }

                $input.addClass('is-invalid');

                if ($input.hasClass('form-check-input')) {
                    $input.closest('.form-check').after(`<div class="invalid-feedback server-error d-block">${value[0]}</div>`);
                    return;
                }

                $input.after(`<div class="invalid-feedback server-error d-block">${value[0]}</div>`);
            });
        }

        $(function() {
            $(document).on('submit', '.global-setup-form', function(event) {
                event.preventDefault();

                const form = this;
                const $form = $(form);

                appAjax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: new FormData(form),
                    onSuccess: function(res) {
                        Swal.fire('Success', res.message, 'success').then(() => {
                            window.location.href = res.redirect_url;
                        });
                    },
                    onError: function(xhr) {
                        const response = xhr.responseJSON || {};
                        let message = response.message || 'Save failed.';

                        if (xhr.status === 422 && response.errors) {
                            applyServerErrors($form, response.errors);
                            message = 'Please check the highlighted configuration fields.';
                        }

                        Swal.fire('Error', message, 'error');
                    }
                });
            });

            $(document).on('click', '.btn-delete-global-setup', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                Swal.fire({
                    title: 'Delete configuration profile?',
                    text: `Profile "${name}" will be removed permanently.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    appAjax({
                        url: `/admin/global-setup/${id}`,
                        method: 'DELETE',
                        onSuccess: function(res) {
                            Swal.fire('Success', res.message, 'success').then(() => {
                                window.location.href = res.redirect_url;
                            });
                        },
                        onError: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON?.message || 'Delete failed.', 'error');
                        }
                    });
                });
            });
        });
    </script>
@endpush
