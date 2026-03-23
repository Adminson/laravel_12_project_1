@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a
                class="nav-link active"
                href="{{ $isEdit ? route('setting.company.edit', $company->cmp_id) : route('setting.company.create') }}"
            >
                Company Details
            </a>
        </li>

        @if ($isEdit)
            <li class="nav-item">
                <a
                    class="nav-link"
                    href="{{ route('setting.system_message.index', $company->cmp_id) }}"
                >
                    System Message
                </a>
            </li>
        @endif
    </ul>

    <form
        id="companyForm"
        method="POST"
        enctype="multipart/form-data"
        novalidate
    >
        @csrf

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">{{ $isEdit ? 'Edit Company' : 'Create Company' }}</h4>
        </div>

        <div class="row g-2">
            {{-- Row 1 : Company Details | Contact Info --}}
            <div class="col-12 col-xl-6">
                <x-card.section-card
                    title="Company Details"
                    class="h-100"
                >
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-form.input-label
                                for="cmp_company_name"
                                value="Company Name"
                                :required="true"
                            />
                            <x-form.input-text
                                name="cmp_company_name"
                                :value="$company->cmp_company_name"
                                :required="true"
                                maxlength="255"
                            />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-form.input-label
                                for="cmp_reg_no"
                                value="Reg No"
                            />
                            <x-form.input-text
                                name="cmp_reg_no"
                                :value="$company->cmp_reg_no"
                                maxlength="255"
                            />
                        </div>

                        <div class="col-md-12 mb-3">
                            <x-form.input-label
                                for="cmp_address"
                                value="Address"
                            />
                            <x-form.textarea
                                name="cmp_address"
                                :value="$company->cmp_address"
                                rows="5"
                            />
                        </div>
                    </div>
                </x-card.section-card>
            </div>

            <div class="col-12 col-xl-6">
                <x-card.section-card
                    title="Contact Info"
                    class="h-100"
                >
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-form.input-label
                                for="cmp_contact_person"
                                value="Contact Person"
                            />
                            <x-form.input-text
                                name="cmp_contact_person"
                                :value="$company->cmp_contact_person"
                                maxlength="255"
                                :required="true"
                            />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-form.input-label
                                for="cmp_contact_email"
                                value="Contact Email"
                            />
                            <x-form.input-text
                                name="cmp_contact_email"
                                type="email"
                                :value="$company->cmp_contact_email"
                                maxlength="255"
                            />
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-form.input-label
                                for="cmp_mobile"
                                value="Mobile"
                            />
                            <x-form.input-text
                                name="cmp_mobile"
                                :value="$company->cmp_mobile"
                                maxlength="50"
                            />
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-form.input-label
                                for="cmp_tel"
                                value="Tel"
                            />
                            <x-form.input-text
                                name="cmp_tel"
                                :value="$company->cmp_tel"
                                maxlength="50"
                            />
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-form.input-label
                                for="cmp_fax"
                                value="Fax"
                            />
                            <x-form.input-text
                                name="cmp_fax"
                                :value="$company->cmp_fax"
                                maxlength="50"
                            />
                        </div>

                        <div class="col-md-12 mb-3">
                            <x-form.input-label
                                for="cmp_url"
                                value="URL"
                            />
                            <x-form.input-text
                                name="cmp_url"
                                :value="$company->cmp_url"
                                maxlength="255"
                            />
                        </div>
                    </div>
                </x-card.section-card>
            </div>

            {{-- Row 2 : Header & Footer | Parameters for PDF --}}
            <div class="col-12 col-xl-6">
                <x-card.section-card
                    title="Header & Footer"
                >
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <x-form.input-label
                                for="cmp_header_title"
                                value="Header Title"
                            />
                            <x-form.input-text
                                name="cmp_header_title"
                                :value="$company->cmp_header_title"
                                maxlength="255"
                            />
                        </div>

                        <div class="col-md-12 mb-3">
                            <x-form.input-label
                                for="cmp_header_text"
                                value="Header Text"
                            />
                            <x-form.textarea
                                name="cmp_header_text"
                                :value="$company->cmp_header_text"
                                rows="4"
                            />
                        </div>

                        <div class="col-md-12 mb-3">
                            <x-form.input-label
                                for="cmp_footer_text"
                                value="Footer Text"
                            />
                            <x-form.textarea
                                name="cmp_footer_text"
                                :value="$company->cmp_footer_text"
                                rows="4"
                            />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-form.input-label
                                for="logoInput"
                                value="Logo"
                            />
                            <x-form.input-file
                                name="logo"
                                id="logoInput"
                                accept="image/*"
                            />
                        </div>

                        <div class="col-md-3 mb-3">
                            <x-form.input-label
                                for="cmp_logo_size"
                                value="Logo Size"
                            />
                            <x-form.input-text
                                name="cmp_logo_size"
                                type="number"
                                :value="$company->cmp_logo_size ?? 12"
                                min="1"
                                max="999"
                                step="1"
                            />
                        </div>

                        <div class="col-md-3 mb-3">
                            <x-form.input-label
                                for="logoPreview"
                                value="Image Preview"
                            />
                            <div>
                                <img
                                    id="logoPreview"
                                    src="{{ $company->logo_url ?? 'https://placehold.co/300x180?text=No+Logo' }}"
                                    alt="Logo Preview"
                                    style="max-width: 180px; max-height: 120px; border:1px solid #ddd; padding:4px;"
                                >
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-form.input-label
                                for="cmp_pdf_header"
                                value="Header"
                            />
                            <x-form.input-select
                                name="cmp_pdf_header"
                                :options="[
                                    'logo_only' => 'Logo only',
                                    'text_only' => 'Text only',
                                    'logo_and_text' => 'Logo and Text',
                                ]"
                                :value="$company->cmp_pdf_header ?? 'logo_and_text'"
                            />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-form.input-label
                                for="cmp_pdf_footer"
                                value="Footer"
                            />
                            <x-form.input-select
                                name="cmp_pdf_footer"
                                :options="[
                                    'show' => 'Show',
                                    'hide' => 'Hide',
                                ]"
                                :value="$company->cmp_pdf_footer ?? 'show'"
                            />
                        </div>
                    </div>
                </x-card.section-card>
            </div>
            {{-- Row 3 : Subscription --}}
            @php
                $subscriptionBadge = null;

                if (!empty($company->cmp_sub_end_date)) {
                    $subscriptionEndDate = \Illuminate\Support\Carbon::parse($company->cmp_sub_end_date);

                    $subscriptionBadge = $subscriptionEndDate->lt(now())
                        ? ['text' => 'Expired', 'type' => 'danger']
                        : ['text' => 'Active', 'type' => 'success'];
                }
            @endphp

            <div class="col-12 col-xl-6">
                <x-card.section-card
                    title="Subscription"
                    :badge="$subscriptionBadge"
                    
                >
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-form.input-label
                                for="cmp_sub_start_date"
                                value="Subscription Start Date"
                            />
                            <x-form.input-text
                                name="cmp_sub_start_date"
                                type="datetime-local"
                                :value="optional($company->cmp_sub_start_date)->format('Y-m-d\TH:i')"
                            />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-form.input-label
                                for="cmp_sub_end_date"
                                value="Subscription End Date"
                            />
                            <x-form.input-text
                                name="cmp_sub_end_date"
                                type="datetime-local"
                                :value="optional($company->cmp_sub_end_date)->format('Y-m-d\TH:i')"
                            />
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-form.input-label
                                for="cmp_suspend_reason"
                                value="Suspend Reason"
                            />
                            <x-form.input-text
                                name="cmp_suspend_reason"
                                id="cmp_suspend_reason"
                                :value="$company->cmp_suspend_reason"
                            />
                        </div>
                    </div>
                </x-card.section-card>
            </div>

            {{-- Row 4 : Memo --}}
            {{-- Row 5 : System Info --}}
            @if ($isEdit)
                <div class="col-12">
                    <x-card.section-card title="System Info">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <x-form.input-label value="cmp_id" />
                                <x-form.input-text
                                    name="view_cmp_id"
                                    :value="$company->cmp_id"
                                    readonly
                                />
                            </div>

                            <div class="col-md-3 mb-3">
                                <x-form.input-label value="cmp_version" />
                                <x-form.input-text
                                    name="view_cmp_version"
                                    :value="$company->cmp_version"
                                    readonly
                                />
                            </div>

                            <div class="col-md-3 mb-3">
                                <x-form.input-label value="cmp_hit" />
                                <x-form.input-text
                                    name="view_cmp_hit"
                                    :value="$company->cmp_hit"
                                    readonly
                                />
                            </div>

                            <div class="col-md-3 mb-3">
                                <x-form.input-label value="cmp_viewedby" />
                                <x-form.input-text
                                    name="view_cmp_viewedby"
                                    :value="$company->cmp_viewedby"
                                    readonly
                                />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-form.input-label value="cmp_createdon" />
                                <x-form.input-text
                                    name="view_cmp_createdon"
                                    :value="optional($company->cmp_createdon)->format('Y-m-d H:i:s')"
                                    readonly
                                />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-form.input-label value="cmp_createdby" />
                                <x-form.input-text
                                    name="view_cmp_createdby"
                                    :value="$company->cmp_createdby"
                                    readonly
                                />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-form.input-label value="cmp_modifiedon" />
                                <x-form.input-text
                                    name="view_cmp_modifiedon"
                                    :value="optional($company->cmp_modifiedon)->format('Y-m-d H:i:s')"
                                    readonly
                                />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-form.input-label value="cmp_modifiedby" />
                                <x-form.input-text
                                    name="view_cmp_modifiedby"
                                    :value="$company->cmp_modifiedby"
                                    readonly
                                />
                            </div>
                        </div>
                    </x-card.section-card>
                </div>
            @endif

            {{-- Action buttons --}}
            <div class="col-12">
                <div class="text-end mt-2">
                    <a
                        href="{{ route('setting.company.index') }}"
                        class="btn btn-secondary"
                    >
                        Back
                    </a>
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        {{ $isEdit ? 'Save Changes' : 'Create Company' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function previewLogo(input) {
            const file = input.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                $('#logoPreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }

        $(function() {
            $.validator.addMethod('greaterThanStart', function(value, element) {
                const start = $('input[name="cmp_sub_start_date"]').val();
                if (!value || !start) return true;
                return new Date(value) >= new Date(start);
            }, 'End date must be greater than or equal to start date.');

            $('#logoInput').on('change', function() {
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
                    cmp_company_name: {
                        required: true,
                        maxlength: 255
                    },
                    cmp_reg_no: {
                        maxlength: 255
                    },
                    cmp_contact_email: {
                        email: true,
                        maxlength: 255
                    },
                    cmp_url: {
                        maxlength: 255
                    },
                    cmp_logo_size: {
                        digits: true,
                        min: 1,
                        max: 999
                    },
                    cmp_sub_end_date: {
                        greaterThanStart: true
                    }
                },
                messages: {
                    cmp_company_name: {
                        required: 'Company name is required.'
                    }
                },
                submitHandler: function(form, event) {
                    event.preventDefault();

                    const formData = new FormData(form);

                    appAjax({
                        url: '{{ $isEdit ? route('setting.company.update', $company->cmp_id) : route('setting.company.store') }}',
                        method: 'POST',
                        data: formData,
                        onSuccess: function(res) {
                            Swal.fire('Success', res.message, 'success').then(() => {
                                window.location.href = res.redirect_url;
                            });
                        },
                        onError: function(xhr) {
                            $('.invalid-feedback.server-error').remove();

                            let msg = xhr.responseJSON?.message || 'Save failed.';

                            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                                const errors = xhr.responseJSON.errors;

                                $.each(errors, function(key, value) {
                                    const input = $('[name="' + key + '"]');
                                    input.addClass('is-invalid');

                                    if (input.next('.server-error').length === 0) {
                                        input.after(
                                            '<div class="invalid-feedback server-error d-block">' +
                                            value[0] + '</div>'
                                        );
                                    }
                                });

                                msg = 'Please check the form.';
                            }

                            Swal.fire('Error', msg, 'error');
                        }
                    });

                    return false;
                }
            });
        });
    </script>
@endpush
