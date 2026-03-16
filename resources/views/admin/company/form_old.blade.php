@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="{{ $isEdit ? route('company_setting_edit', $company->id) : route('company_setting_create') }}">
                Company Details
            </a>
        </li>
        @if ($isEdit)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('setting.system_message.index', $company->id) }}">
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
                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control" value="{{ $company->company_name }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Reg No</label>
                        <input type="text" name="reg_no" class="form-control" value="{{ $company->reg_no }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact</label>
                        <input type="text" name="contact" class="form-control" value="{{ $company->contact }}">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ $company->address }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Header Info</label>
                        <textarea name="header_info" class="form-control" rows="3">{{ $company->header_info }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Footer Info</label>
                        <textarea name="footer_info" class="form-control" rows="3">{{ $company->footer_info }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" id="logoInput" class="form-control" accept="image/*">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Image Preview</label>
                        <div>
                            <img id="logoPreview" src="{{ $company->logo_url ?? 'https://placehold.co/600x400' }}" alt="Logo Preview" style="max-width: 180px; max-height: 120px; border:1px solid #ddd; padding:4px;">
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="card mt-5">
            <div class="card-body">

                <div class="row">
                    <div class="divider divider-info">
                        <h4 class="divider-text">Subscription</h4>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Subscription Start Date</label>
                        <input type="datetime-local" name="sub_start_date" class="form-control" value="{{ optional($company->sub_start_date)->format('Y-m-d\TH:i') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Subscription End Date</label>
                        <input type="datetime-local" name="sub_end_date" class="form-control" value="{{ optional($company->sub_end_date)->format('Y-m-d\TH:i') }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="suspend_login" id="suspend_login" class="form-check-input" value="1" {{ $company->suspend_login ? 'checked' : '' }}>
                            <label for="suspend_login" class="form-check-label">Suspend Login</label>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Suspend Reason</label>
                        <input type="text" name="suspend_reason" id="suspend_reason" class="form-control" value="{{ $company->suspend_reason }}" {{ $company->suspend_login ? '' : 'disabled' }}>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('company_setting_index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Save Changes' : 'Create Company' }}</button>
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
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                $('#logoPreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }

        $(function() {
            console.log('Company form script loaded');
            console.log('appAjax:', typeof window.appAjax);
            console.log('validate plugin:', typeof $.fn.validate);

            $.validator.addMethod('greaterThanStart', function(value, element) {
                const start = $('input[name="sub_start_date"]').val();
                if (!value || !start) return true;
                return new Date(value) >= new Date(start);
            }, 'End date must be greater than or equal to start date.');

            toggleSuspendReason();

            $('#suspend_login').on('change', function() {
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

                    const formData = new FormData(form);

                    appAjax({
                        url: '{{ $isEdit ? route('company_setting_update', $company->id) : route('company_setting_store') }}',
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
