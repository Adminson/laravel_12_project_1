@extends('layouts.app')

@section('title', 'User Setting')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">User List</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="userTable" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <br />
            <br />
            <br />

            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row g-6 mb-6">
                    <div class="col-md">
                        <div class="card">
                            <h5 class="card-header">jQuery Validation</h5>
                            <div class="card-body">
                                <form id="userValidationForm" novalidate>
                                    <div class="mb-6">
                                        <label class="form-label" for="bs-validation-name">Name</label>
                                        <input type="text" class="form-control" id="bs-validation-name" name="name" placeholder="John Doe" />
                                    </div>

                                    <div class="mb-6">
                                        <label class="form-label" for="bs-validation-email">Email</label>
                                        <input type="email" id="bs-validation-email" name="email" class="form-control" placeholder="john.doe@example.com" />
                                    </div>

                                    <div class="mb-6 form-password-toggle">
                                        <label class="form-label" for="bs-validation-password">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="bs-validation-password" name="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                            <span class="input-group-text cursor-pointer" id="basic-default-password4">
                                                <i class="icon-base ti tabler-eye-off"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="form-label" for="bs-validation-country">Country</label>
                                        <select class="form-select" id="bs-validation-country" name="country">
                                            <option value="">Select Country</option>
                                            <option value="usa">USA</option>
                                            <option value="uk">UK</option>
                                            <option value="france">France</option>
                                            <option value="australia">Australia</option>
                                            <option value="spain">Spain</option>
                                        </select>
                                    </div>

                                    <div class="mb-6">
                                        <label class="form-label" for="bs-validation-dob">DOB</label>
                                        <input type="text" class="form-control flatpickr-validation" id="bs-validation-dob" name="dob" />
                                    </div>

                                    <div class="mb-6">
                                        <label class="form-label" for="bs-validation-upload-file">Profile pic</label>
                                        <input type="file" class="form-control" id="bs-validation-upload-file" name="profile_pic" />
                                    </div>

                                    <div class="mb-6">
                                        <label class="d-block form-label">Gender</label>

                                        <div class="form-check mb-2">
                                            <input type="radio" id="bs-validation-radio-male" name="gender" value="male" class="form-check-input" />
                                            <label class="form-check-label" for="bs-validation-radio-male">Male</label>
                                        </div>

                                        <div class="form-check">
                                            <input type="radio" id="bs-validation-radio-female" name="gender" value="female" class="form-check-input" />
                                            <label class="form-check-label" for="bs-validation-radio-female">Female</label>
                                        </div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="form-label" for="bs-validation-bio">Bio</label>
                                        <textarea class="form-control" id="bs-validation-bio" name="bio" rows="3"></textarea>
                                    </div>

                                    <div class="mb-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="bs-validation-checkbox" name="terms" value="1" />
                                            <label class="form-check-label" for="bs-validation-checkbox">
                                                Agree to our terms and conditions
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="bootstrapValidationSwitch" name="related_emails" value="1" />
                                            <label class="form-check-label" for="bootstrapValidationSwitch">
                                                Send me related emails
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br />
            <br />
            <br />
            <div class="container py-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Date Range Picker + jQuery Validation Demo</h4>
                    </div>
                    <div class="card-body">
                        <form id="dateTimeValidationForm" novalidate>
                            <div class="row g-3">
                                <!-- 1. Single Date Time -->
                                <div class="col-md-6">
                                    <label for="single_datetime" class="form-label">Single Date Time</label>
                                    <input type="text" class="form-control" id="single_datetime" name="single_datetime" placeholder="YYYY-MM-DD HH:mm">
                                </div>

                                <!-- 2. Range Date Time -->
                                <div class="col-md-6">
                                    <label for="range_datetime" class="form-label">Range Date Time</label>
                                    <input type="text" class="form-control" id="range_datetime" name="range_datetime" placeholder="YYYY-MM-DD HH:mm - YYYY-MM-DD HH:mm">
                                </div>

                                <!-- 3. Single Time -->
                                <div class="col-md-6">
                                    <label for="single_time" class="form-label">Single Time</label>
                                    <input type="text" class="form-control" id="single_time" name="single_time" placeholder="HH:mm">
                                </div>

                                <!-- 4. Single Date -->
                                <div class="col-md-6">
                                    <label for="single_date" class="form-label">Single Date</label>
                                    <input type="text" class="form-control" id="single_date" name="single_date" placeholder="YYYY-MM-DD">
                                </div>

                                <!-- 5. Single Date Range -->
                                <div class="col-md-6">
                                    <label for="single_date_range" class="form-label">Single Date Range</label>
                                    <input type="text" class="form-control" id="single_date_range" name="single_date_range" placeholder="YYYY-MM-DD - YYYY-MM-DD">
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <br />
        <br />
        <br />
        <br />

    @endsection

    @push('scripts')
        <style>
            label.error {
                display: block;
                width: 100%;
                margin-top: 0.25rem;
                font-size: 0.875em;
                color: var(--bs-danger);
            }
        </style>

        <script>
            $(document).ready(function() {
                $('#userTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('setting.user.list') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    order: [
                        [3, 'desc']
                    ],
                });

                $('#userValidationForm').validate({
                    ignore: [],

                    rules: {
                        name: {
                            required: true,
                            minlength: 3
                        },
                        email: {
                            required: true,
                            email: true
                        },
                        password: {
                            required: true,
                            minlength: 6
                        },
                        country: {
                            required: true
                        },
                        dob: {
                            required: true
                        },
                        profile_pic: {
                            required: true,
                            extension: "png|jpg|jpeg|pdf"
                        },
                        gender: {
                            required: true
                        },
                        bio: {
                            required: true,
                            minlength: 10
                        },
                        terms: {
                            required: true
                        },
                        related_emails: {
                            required: true
                        }
                    },

                    messages: {
                        name: {
                            required: 'Please enter your name.',
                            minlength: 'Name must be at least 3 characters.'
                        },
                        email: {
                            required: 'Please enter your email.',
                            email: 'Please enter a valid email.'
                        },
                        password: {
                            required: 'Please enter your password.',
                            minlength: 'Password must be at least 6 characters.'
                        },
                        country: {
                            required: 'Please select your country.'
                        },
                        dob: {
                            required: 'Please enter your DOB.'
                        },
                        profile_pic: {
                            required: 'Please upload your profile pic.',
                            extension: 'Please upload a valid file (.png, .jpg, .jpeg, .pdf).'
                        },
                        gender: {
                            required: 'Please select your gender.'
                        },
                        bio: {
                            required: 'Please enter your bio.',
                            minlength: 'Bio must be at least 10 characters.'
                        },
                        terms: {
                            required: 'You must agree before submitting.'
                        },
                        related_emails: {
                            required: 'Please enable this switch.'
                        }
                    },

                    errorElement: 'label',
                    errorClass: 'error',

                    highlight: function(element) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    },

                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    },

                    errorPlacement: function(error, element) {
                        if (element.attr('type') === 'radio') {
                            error.insertAfter(element.closest('.mb-6').find('.form-check').last());
                        } else if (element.attr('type') === 'checkbox' && element.closest('.form-switch').length) {
                            error.insertAfter(element.closest('.form-check'));
                        } else if (element.attr('type') === 'checkbox') {
                            error.insertAfter(element.closest('.form-check'));
                        } else if (element.parent().hasClass('input-group')) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    },

                    submitHandler: function(form) {
                        alert('Submitted!!!');
                        // form.submit();
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                // -----------------------------
                // Bootstrap Date Range Picker
                // -----------------------------

                // 1. Single Date Time
                $('#single_datetime').daterangepicker({
                    singleDatePicker: true,
                    timePicker: true,
                    timePicker24Hour: true,
                    autoUpdateInput: false,
                    locale: {
                        format: 'YYYY-MM-DD HH:mm',
                        cancelLabel: 'Clear'
                    }
                });

                $('#single_datetime').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm')).trigger('change');
                });

                $('#single_datetime').on('cancel.daterangepicker', function() {
                    $(this).val('').trigger('change');
                });

                // 2. Range Date Time
                $('#range_datetime').daterangepicker({
                    timePicker: true,
                    timePicker24Hour: true,
                    autoUpdateInput: false,
                    locale: {
                        format: 'YYYY-MM-DD HH:mm',
                        cancelLabel: 'Clear'
                    }
                });

                $('#range_datetime').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(
                        picker.startDate.format('YYYY-MM-DD HH:mm') +
                        ' - ' +
                        picker.endDate.format('YYYY-MM-DD HH:mm')
                    ).trigger('change');
                });

                $('#range_datetime').on('cancel.daterangepicker', function() {
                    $(this).val('').trigger('change');
                });

                // 3. Single Time
                $('#single_time').daterangepicker({
                    singleDatePicker: true,
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerIncrement: 5,
                    autoUpdateInput: false,
                    autoApply: true,
                    locale: {
                        format: 'HH:mm'
                    }
                });

                $('#single_time').on('show.daterangepicker', function(ev, picker) {
                    picker.container.find('.calendar-table').hide();
                });

                $('#single_time').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('HH:mm')).trigger('change');
                });

                // 4. Single Date
                $('#single_date').daterangepicker({
                    singleDatePicker: true,
                    autoUpdateInput: false,
                    locale: {
                        format: 'YYYY-MM-DD',
                        cancelLabel: 'Clear'
                    }
                });

                $('#single_date').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD')).trigger('change');
                });

                $('#single_date').on('cancel.daterangepicker', function() {
                    $(this).val('').trigger('change');
                });

                // 5. Single Date Range
                $('#single_date_range').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        format: 'YYYY-MM-DD',
                        cancelLabel: 'Clear'
                    }
                });

                $('#single_date_range').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(
                        picker.startDate.format('YYYY-MM-DD') +
                        ' - ' +
                        picker.endDate.format('YYYY-MM-DD')
                    ).trigger('change');
                });

                $('#single_date_range').on('cancel.daterangepicker', function() {
                    $(this).val('').trigger('change');
                });

                // -----------------------------
                // jQuery Validation custom methods
                // -----------------------------
                $.validator.addMethod('validSingleDateTime', function(value, element) {
                    return this.optional(element) || moment(value, 'YYYY-MM-DD HH:mm', true).isValid();
                }, 'Please enter a valid date time in format YYYY-MM-DD HH:mm.');

                $.validator.addMethod('validRangeDateTime', function(value, element) {
                    if (this.optional(element)) {
                        return true;
                    }

                    const parts = value.split(' - ');
                    if (parts.length !== 2) {
                        return false;
                    }

                    const start = moment(parts[0], 'YYYY-MM-DD HH:mm', true);
                    const end = moment(parts[1], 'YYYY-MM-DD HH:mm', true);

                    return start.isValid() && end.isValid() && end.isSameOrAfter(start);
                }, 'Please enter a valid date time range.');

                $.validator.addMethod('validSingleTime', function(value, element) {
                    return this.optional(element) || moment(value, 'HH:mm', true).isValid();
                }, 'Please enter a valid time in format HH:mm.');

                $.validator.addMethod('validSingleDate', function(value, element) {
                    return this.optional(element) || moment(value, 'YYYY-MM-DD', true).isValid();
                }, 'Please enter a valid date in format YYYY-MM-DD.');

                $.validator.addMethod('validSingleDateRange', function(value, element) {
                    if (this.optional(element)) {
                        return true;
                    }

                    const parts = value.split(' - ');
                    if (parts.length !== 2) {
                        return false;
                    }

                    const start = moment(parts[0], 'YYYY-MM-DD', true);
                    const end = moment(parts[1], 'YYYY-MM-DD', true);

                    return start.isValid() && end.isValid() && end.isSameOrAfter(start);
                }, 'Please enter a valid date range.');

                // -----------------------------
                // jQuery Validation
                // -----------------------------
                $('#dateTimeValidationForm').validate({
                    ignore: [],
                    rules: {
                        single_datetime: {
                            required: true,
                            validSingleDateTime: true
                        },
                        range_datetime: {
                            required: true,
                            validRangeDateTime: true
                        },
                        single_time: {
                            required: true,
                            validSingleTime: true
                        },
                        single_date: {
                            required: true,
                            validSingleDate: true
                        },
                        single_date_range: {
                            required: true,
                            validSingleDateRange: true
                        }
                    },
                    messages: {
                        single_datetime: {
                            required: 'Please select single date time.'
                        },
                        range_datetime: {
                            required: 'Please select range date time.'
                        },
                        single_time: {
                            required: 'Please select single time.'
                        },
                        single_date: {
                            required: 'Please select single date.'
                        },
                        single_date_range: {
                            required: 'Please select single date range.'
                        }
                    },
                    errorElement: 'label',
                    errorClass: 'error',
                    highlight: function(element) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element);
                    },
                    submitHandler: function(form) {
                        alert('Validation passed');
                        // form.submit();
                    }
                });
            });
        </script>


    @endpush
