@extends('layouts.app')

@section('content')
    @php
        $companySection = $formSections['company'];
        $subscriptionSection = $formSections['subscription'];
        $companyFields = collect($companySection['fields']);
        $logoField = $companyFields->firstWhere('name', 'logo');
        $companyPrimaryFields = $companyFields->reject(fn ($field) => $field['name'] === 'logo')->values();
        $subscriptionFields = collect($subscriptionSection['fields'])
            ->map(function ($field) use ($company) {
                if ($field['name'] === 'suspend_reason') {
                    $field['disabled'] = !filter_var(old('suspend_login', $company->suspend_login), FILTER_VALIDATE_BOOLEAN);
                }

                return $field;
            })
            ->values();
    @endphp

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link active" href="{{ $isEdit ? route('company_setting_edit', $company->id) : route('company_setting_create') }}">
                Company Details
            </a>
        </li>
        @if ($isEdit)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('system_message_index', $company->id) }}">
                    System Message
                </a>
            </li>
        @endif
    </ul>

    <form id="companyForm" method="POST" enctype="multipart/form-data" novalidate>
        @csrf

        <div class="row g-4">
            <div class="col-xl-8">
                <x-admin.form-section
                    :title="$companySection['title']"
                    :description="$companySection['description']"
                    :icon="$companySection['icon']"
                    class="mb-4"
                >
                    @foreach ($companyPrimaryFields as $field)
                        <x-admin.form-field :field="$field" :model="$company" />
                    @endforeach

                    <x-admin.form-field :field="$logoField" :model="$company" />

                    <div class="col-md-6">
                        <label class="form-label">Logo Preview</label>
                        <div class="border rounded-3 p-3 text-center bg-body">
                            <img
                                id="logoPreview"
                                src="{{ $company->logo_url ?? 'https://placehold.co/600x400?text=Logo+Preview' }}"
                                alt="Logo Preview"
                                class="img-fluid rounded"
                                style="max-height: 160px; object-fit: contain;"
                            >
                        </div>
                    </div>
                </x-admin.form-section>

                <x-admin.form-section
                    :title="$subscriptionSection['title']"
                    :description="$subscriptionSection['description']"
                    :icon="$subscriptionSection['icon']"
                >
                    @foreach ($subscriptionFields as $field)
                        <x-admin.form-field :field="$field" :model="$company" />
                    @endforeach
                </x-admin.form-section>
            </div>

            <div class="col-xl-4">
                <div class="card border shadow-none mb-4">
                    <div class="card-header">
                        <h5 class="mb-1">{{ $isEdit ? 'Update Company Profile' : 'Create Company Profile' }}</h5>
                        <p class="mb-0 text-muted">Review branding, access dates and login controls before saving.</p>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <span class="avatar avatar-lg bg-label-primary">
                                <i class="icon-base ti tabler-building icon-lg"></i>
                            </span>
                            <div>
                                <h6 class="mb-1">{{ $company->company_name ?: 'New company profile' }}</h6>
                                <span class="badge {{ $company->suspend_login ? 'bg-label-danger' : 'bg-label-success' }}">
                                    {{ $company->suspend_login ? 'Login Suspended' : 'Login Enabled' }}
                                </span>
                            </div>
                        </div>

                        <div class="bg-label-secondary rounded-3 p-3 mb-4">
                            <div class="small text-uppercase text-muted fw-semibold mb-2">Notes</div>
                            <p class="mb-2 text-body-secondary">Header and footer content are good places for invoice text, registration details and legal disclaimers.</p>
                            <p class="mb-0 text-body-secondary">
                                {{ $isEdit ? 'System messages can be managed after the company profile is saved.' : 'System messages become available after the first save.' }}
                            </p>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ $isEdit ? 'Save Changes' : 'Create Company' }}
                            </button>
                            <a href="{{ route('company_setting_index') }}" class="btn btn-label-secondary">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function toggleSuspendReason() {
            if ($('#suspend_login').is(':checked')) {
                $('#suspend_reason').prop('disabled', false);
            } else {
                $('#suspend_reason').val('').prop('disabled', true);
            }
        }

        function previewLogo(input) {
            const file = input.files[0];

            if (!file) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                $('#logoPreview').attr('src', event.target.result);
            };
            reader.readAsDataURL(file);
        }

        function applyCompanyServerErrors(errors) {
            $('.server-error').remove();
            $('#companyForm').find('.is-invalid').removeClass('is-invalid');

            $.each(errors, function(key, value) {
                const $input = $('#companyForm').find(`[name="${key}"]`).last();

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
            $.validator.addMethod('greaterThanStart', function(value) {
                const start = $('input[name="sub_start_date"]').val();

                if (!value || !start) {
                    return true;
                }

                return new Date(value) >= new Date(start);
            }, 'End date must be greater than or equal to start date.');

            toggleSuspendReason();

            $('#suspend_login').on('change', function() {
                toggleSuspendReason();
            });

            $('#logo').on('change', function() {
                previewLogo(this);
            });

            $('#companyForm').validate({
                ignore: [],
                errorElement: 'div',
                errorClass: 'invalid-feedback',
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                errorPlacement: function(error, element) {
                    if (element.hasClass('form-check-input')) {
                        error.insertAfter(element.closest('.form-check'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                rules: {
                    company_name: {
                        required: true,
                        maxlength: 255
                    },
                    reg_no: {
                        maxlength: 255
                    },
                    contact: {
                        maxlength: 20
                    },
                    sub_end_date: {
                        greaterThanStart: true
                    }
                },
                messages: {
                    company_name: {
                        required: 'Company name is required.'
                    }
                },
                submitHandler: function(form, event) {
                    event.preventDefault();

                    appAjax({
                        url: '{{ $isEdit ? route('company_setting_update', $company->id) : route('company_setting_store') }}',
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
                                applyCompanyServerErrors(response.errors);
                                message = 'Please check the highlighted company fields.';
                            }

                            Swal.fire('Error', message, 'error');
                        }
                    });

                    return false;
                }
            });
        });
    </script>
@endpush
