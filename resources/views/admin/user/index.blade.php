@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">User List</h4>
                <button type="button" class="btn btn-primary" id="btnAddUser">
                    <i class="icon-base ti tabler-plus me-1"></i> Add User
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="userTable" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th style="width:60px;">No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile No</th>
                                <th>User Type</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th style="width:100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.user.form-modal')
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
        $(document).ready(function () {

            // ── DataTable ─────────────────────────────────────────────
            const userTable = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('setting.user.list') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name',        name: 'name' },
                    { data: 'email',       name: 'email' },
                    { data: 'mobile_no',   name: 'mobile_no' },
                    { data: 'user_type',   name: 'user_type' },
                    { data: 'account_lock', name: 'account_lock', orderable: false, searchable: false },
                    { data: 'created_at',  name: 'created_at' },
                    { data: 'action',      name: 'action', orderable: false, searchable: false },
                ],
                order: [[6, 'desc']],
            });

            // ── jQuery Validate shared config ─────────────────────────
            const validateDefaults = {
                ignore: [],
                errorElement: 'label',
                errorClass: 'error',
                highlight:   function (el) { $(el).addClass('is-invalid').removeClass('is-valid'); },
                unhighlight: function (el) { $(el).removeClass('is-invalid').addClass('is-valid'); },
                errorPlacement: function (error, element) {
                    if (element.parent().hasClass('input-group')) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
            };

            // ── Form validator instance ───────────────────────────────
            const userFormValidator = $('#userForm').validate($.extend(true, {}, validateDefaults, {
                rules: {
                    name:         { required: true, minlength: 2 },
                    email:        { required: true, email: true },
                    password:     { minlength: 8 },
                    user_type:    { required: true },
                    mobile_no:    { maxlength: 25 },
                },
                messages: {
                    name:      { required: 'Name is required.', minlength: 'At least 2 characters.' },
                    email:     { required: 'Email is required.', email: 'Enter a valid email.' },
                    password:  { minlength: 'Minimum 8 characters.' },
                    user_type: { required: 'Please select a user type.' },
                    mobile_no: { maxlength: 'Max 25 characters.' },
                },
                submitHandler: function (form) {
                    submitUserForm();
                },
            }));

            // ── Open modal: ADD ───────────────────────────────────────
            $('#btnAddUser').on('click', function () {
                resetModal('Add User', null);
                $('#userModal').modal('show');
            });

            // ── Open modal: EDIT ──────────────────────────────────────
            $(document).on('click', '.btn-edit-user', function () {
                const userId = $(this).data('id');
                $.get('{{ url('setting/user') }}/' + userId, function (data) {
                    resetModal('Edit User', data);
                    $('#userModal').modal('show');
                });
            });

            // ── Reset / populate modal ────────────────────────────────
            function resetModal(title, data) {
                $('#userModalLabel').text(title);
                userFormValidator.resetForm();
                $('#userForm')[0].reset();
                $('#userForm .is-invalid').removeClass('is-invalid');

                if (data) {
                    $('#userId').val(data.id);
                    $('#userName').val(data.name);
                    $('#userEmail').val(data.email);
                    $('#userType').val(data.user_type);
                    $('#userMobileNo').val(data.mobile_no);
                    $('#accountLock').prop('checked', data.account_lock == 1);
                    $('#passwordHint').show();
                    // password optional on edit — remove required rule
                    $('#userPassword').rules('add', { required: false });
                } else {
                    $('#userId').val('');
                    $('#passwordHint').hide();
                    $('#userPassword').rules('add', { required: true });
                }
            }

            // ── Submit form via AJAX ──────────────────────────────────
            function submitUserForm() {
                const userId = $('#userId').val();
                const isEdit = userId !== '';

                const url    = isEdit
                    ? '{{ url('setting/user') }}/' + userId
                    : '{{ route('setting.user.store') }}';
                const method = isEdit ? 'PUT' : 'POST';

                const formData = {
                    _token:       '{{ csrf_token() }}',
                    name:         $('#userName').val(),
                    email:        $('#userEmail').val(),
                    password:     $('#userPassword').val(),
                    user_type:    $('#userType').val(),
                    mobile_no:    $('#userMobileNo').val(),
                    account_lock: $('#accountLock').is(':checked') ? 1 : 0,
                };

                $.ajax({
                    url:    url,
                    type:   method,
                    data:   formData,
                    success: function (res) {
                        if (res.success) {
                            $('#userModal').modal('hide');
                            userTable.ajax.reload(null, false);
                            toastr.success(res.message);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function (field, messages) {
                                const input = $('#userForm [name="' + field + '"]');
                                input.addClass('is-invalid');
                                input.after('<label class="error">' + messages[0] + '</label>');
                            });
                        } else {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    },
                });
            }

        });
    </script>
@endpush
