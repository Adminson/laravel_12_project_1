@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link active"
               href="{{ $isEdit ? route('setting.company.edit', $company->cmp_id) : route('setting.company.create') }}">
                Company Details
            </a>
        </li>
        @if ($isEdit)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('setting.system_message.index', $company->cmp_id) }}">
                    System Message
                </a>
            </li>
        @endif
    </ul>

    <form id="companyForm" method="POST" enctype="multipart/form-data" novalidate>
        @csrf

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">{{ $isEdit ? 'Edit Company' : 'Create Company' }}</h4>
            </div>

            <div class="card-body">
                <div class="divider divider-info">
                    <h4 class="divider-text">Company Details</h4>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-form.input-label for="cmp_company_name" value="Company Name" :required="true" />
                        <input type="text" name="cmp_company_name" class="form-control"
                               value="{{ old('cmp_company_name', $company->cmp_company_name) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Reg No</label>
                        <input type="text" name="cmp_reg_no" class="form-control"
                               value="{{ old('cmp_reg_no', $company->cmp_reg_no) }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="cmp_address" class="form-control" rows="3">{{ old('cmp_address', $company->cmp_address) }}</textarea>
                    </div>
                </div>

                <div class="divider divider-info mt-4">
                    <h4 class="divider-text">Contact Info</h4>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="cmp_contact_person" class="form-control"
                               value="{{ old('cmp_contact_person', $company->cmp_contact_person) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="cmp_contact_email" class="form-control"
                               value="{{ old('cmp_contact_email', $company->cmp_contact_email) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Mobile</label>
                        <input type="text" name="cmp_mobile" class="form-control"
                               value="{{ old('cmp_mobile', $company->cmp_mobile) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tel</label>
                        <input type="text" name="cmp_tel" class="form-control"
                               value="{{ old('cmp_tel', $company->cmp_tel) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fax</label>
                        <input type="text" name="cmp_fax" class="form-control"
                               value="{{ old('cmp_fax', $company->cmp_fax) }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">URL</label>
                        <input type="text" name="cmp_url" class="form-control"
                               value="{{ old('cmp_url', $company->cmp_url) }}">
                    </div>
                </div>

                <div class="divider divider-info mt-4">
                    <h4 class="divider-text">Parameters for PDF</h4>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Logo Folder</label>
                        <input type="text" name="cmp_pdf_logo_folder" class="form-control"
                               value="{{ old('cmp_pdf_logo_folder', $company->cmp_pdf_logo_folder ?? '/images/epm/logo/') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Header</label>
                        <select name="cmp_pdf_header" class="form-control">
                            <option value="logo_only" {{ old('cmp_pdf_header', $company->cmp_pdf_header ?? 'logo_and_text') == 'logo_only' ? 'selected' : '' }}>
                                Logo only
                            </option>
                            <option value="text_only" {{ old('cmp_pdf_header', $company->cmp_pdf_header ?? 'logo_and_text') == 'text_only' ? 'selected' : '' }}>
                                Text only
                            </option>
                            <option value="logo_and_text" {{ old('cmp_pdf_header', $company->cmp_pdf_header ?? 'logo_and_text') == 'logo_and_text' ? 'selected' : '' }}>
                                Logo and Text
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Footer</label>
                        <select name="cmp_pdf_footer" class="form-control">
                            <option value="show" {{ old('cmp_pdf_footer', $company->cmp_pdf_footer ?? 'show') == 'show' ? 'selected' : '' }}>
                                Show
                            </option>
                            <option value="hide" {{ old('cmp_pdf_footer', $company->cmp_pdf_footer ?? 'show') == 'hide' ? 'selected' : '' }}>
                                Hide
                            </option>
                        </select>
                    </div>
                </div>

                <div class="divider divider-info mt-4">
                    <h4 class="divider-text">Header & Footer</h4>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Header Title</label>
                        <input type="text" name="cmp_header_title" class="form-control"
                               value="{{ old('cmp_header_title', $company->cmp_header_title) }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Header Text</label>
                        <textarea name="cmp_header_text" class="form-control" rows="3">{{ old('cmp_header_text', $company->cmp_header_text) }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Footer Text</label>
                        <textarea name="cmp_footer_text" class="form-control" rows="3">{{ old('cmp_footer_text', $company->cmp_footer_text) }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" id="logoInput" class="form-control" accept="image/*">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Logo Size</label>
                        <input type="number" name="cmp_logo_size" class="form-control"
                               value="{{ old('cmp_logo_size', $company->cmp_logo_size ?? 12) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Image Preview</label>
                        <div>
                            <img id="logoPreview"
                                 src="{{ $company->logo_url ?? 'https://placehold.co/300x180?text=No+Logo' }}"
                                 alt="Logo Preview"
                                 style="max-width: 180px; max-height: 120px; border:1px solid #ddd; padding:4px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <div class="divider divider-info">
                    <h4 class="divider-text">Subscription & Status</h4>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Subscription Start Date</label>
                        <input type="datetime-local" name="cmp_sub_start_date" class="form-control"
                               value="{{ old('cmp_sub_start_date', optional($company->cmp_sub_start_date)->format('Y-m-d\TH:i')) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Subscription End Date</label>
                        <input type="datetime-local" name="cmp_sub_end_date" class="form-control"
                               value="{{ old('cmp_sub_end_date', optional($company->cmp_sub_end_date)->format('Y-m-d\TH:i')) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="cmp_active" id="cmp_active" class="form-check-input" value="1"
                                   {{ old('cmp_active', $company->exists ? (int) $company->cmp_active : 1) ? 'checked' : '' }}>
                            <label for="cmp_active" class="form-check-label">Active</label>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="cmp_lock" id="cmp_lock" class="form-check-input" value="1"
                                   {{ old('cmp_lock', $company->cmp_lock) ? 'checked' : '' }}>
                            <label for="cmp_lock" class="form-check-label">Lock</label>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="cmp_suspend_login" id="cmp_suspend_login" class="form-check-input" value="1"
                                   {{ old('cmp_suspend_login', $company->cmp_suspend_login) ? 'checked' : '' }}>
                            <label for="cmp_suspend_login" class="form-check-label">Suspend Login</label>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Suspend Reason</label>
                        <input type="text" name="cmp_suspend_reason" id="cmp_suspend_reason" class="form-control"
                               value="{{ old('cmp_suspend_reason', $company->cmp_suspend_reason) }}"
                               {{ old('cmp_suspend_login', $company->cmp_suspend_login) ? '' : 'disabled' }}>
                    </div>
                </div>

                @if ($isEdit)
                    <div class="divider divider-info mt-4">
                        <h4 class="divider-text">System Info</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">cmp_id</label>
                            <input type="text" class="form-control" value="{{ $company->cmp_id }}" readonly>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">cmp_version</label>
                            <input type="text" class="form-control" value="{{ $company->cmp_version }}" readonly>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">cmp_hit</label>
                            <input type="text" class="form-control" value="{{ $company->cmp_hit }}" readonly>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">cmp_viewedby</label>
                            <input type="text" class="form-control" value="{{ $company->cmp_viewedby }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">cmp_createdon</label>
                            <input type="text" class="form-control" value="{{ optional($company->cmp_createdon)->format('Y-m-d H:i:s') }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">cmp_createdby</label>
                            <input type="text" class="form-control" value="{{ $company->cmp_createdby }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">cmp_modifiedon</label>
                            <input type="text" class="form-control" value="{{ optional($company->cmp_modifiedon)->format('Y-m-d H:i:s') }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">cmp_modifiedby</label>
                            <input type="text" class="form-control" value="{{ $company->cmp_modifiedby }}" readonly>
                        </div>
                    </div>
                @endif

                <div class="text-end">
                    <a href="{{ route('setting.company.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? 'Save Changes' : 'Create Company' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function toggleSuspendReason() {
            if ($('#cmp_suspend_login').is(':checked')) {
                $('#cmp_suspend_reason').prop('disabled', false);
            } else {
                $('#cmp_suspend_reason').val('').prop('disabled', true);
            }
        }

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

            toggleSuspendReason();

            $('#cmp_suspend_login').on('change', function() {
                toggleSuspendReason();
            });

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
                                        input.after('<div class="invalid-feedback server-error d-block">' + value[0] + '</div>');
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