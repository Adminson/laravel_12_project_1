@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('company_setting_edit', $company->id) }}">
                Company Details
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('system_message_index', $company->id) }}">
                System Message
            </a>
        </li>
    </ul>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">System Message - {{ $company->company_name }}</h4>
            </div>
            <button type="button" class="btn btn-primary" id="btnCreateMessage">
                Create
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="systemMessageTable">
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Email Enabled</th>
                        <th>Email</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            @if ($activeSystemMessages->isNotEmpty())
                @php
                    $alertStyles = [
                        'blue' => ['class' => 'alert-solid-success', 'icon' => 'tabler-check'],
                        'red' => ['class' => 'alert-solid-danger', 'icon' => 'tabler-ban'],
                        'orange' => ['class' => 'alert-solid-warning', 'icon' => 'tabler-bell'],
                    ];
                @endphp

                <div class="mt-4">
                    @foreach ($activeSystemMessages as $systemMessage)
                        @php
                            $alertStyle = $alertStyles[$systemMessage->type] ?? $alertStyles['blue'];
                            $formattedDescription = str_replace(['{{ date1 }}', '{{ date2 }}'], [optional($systemMessage->start_date)->format('d-M-Y h:i A'), optional($systemMessage->end_date)->format('d-M-Y h:i A')], $systemMessage->description);
                        @endphp

                        <div class="alert {{ $alertStyle['class'] }} d-flex align-items-center mb-3" role="alert">
                            <span class="alert-icon rounded">
                                <i class="icon-base ti {{ $alertStyle['icon'] }} icon-md"></i>
                            </span>
                            <div>
                                <strong>{{ $systemMessage->title }}</strong>
                                <div>{{ $formattedDescription }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="systemMessageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="systemMessageForm">
                    @csrf
                    <input type="hidden" name="message_id" id="message_id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="messageModalTitle">Create System Message</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" name="description" id="description" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Message Type</label>
                                <select name="type" id="type" class="form-select">
                                    <option value="blue">Blue</option>
                                    <option value="orange">Orange</option>
                                    <option value="red">Red</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control">
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="enable_email" id="enable_email" class="form-check-input" value="1">
                                    <label class="form-check-label" for="enable_email">Enable Email</label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" name="email" id="email" class="form-control" disabled placeholder="aaa@gmail.com;bbb@gmail.com;ccc@gmail.com">
                                <small class="text-muted">Separate multiple emails with semicolon (;)</small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btnSaveMessage">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const systemMessageModal = new bootstrap.Modal(document.getElementById('systemMessageModal'));
        let systemMessageTable;

        function toggleEmailInput() {
            if ($('#enable_email').is(':checked')) {
                $('#email').prop('disabled', false);
            } else {
                $('#email').val('').prop('disabled', true);
            }
        }

        function resetMessageForm() {
            $('#systemMessageForm')[0].reset();
            $('#message_id').val('');
            $('#email').prop('disabled', true);
            $('#systemMessageForm').find('.is-invalid').removeClass('is-invalid');
            $('#systemMessageForm').find('.invalid-feedback').remove();
        }

        $(function() {
            systemMessageTable = $('#systemMessageTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('system_message_list', $company->id) }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    },
                    {
                        data: 'enable_email',
                        name: 'enable_email'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#btnCreateMessage').on('click', function() {
                resetMessageForm();
                $('#messageModalTitle').text('Create System Message');
                systemMessageModal.show();
            });

            $('#enable_email').on('change', function() {
                toggleEmailInput();
            });

            $(document).on('click', '.btn-edit-message', function() {
                const id = $(this).data('id');

                appAjax({
                    url: `/admin/system-message/item/${id}`,
                    method: 'GET',
                    onSuccess: function(res) {
                        resetMessageForm();

                        const data = res.data;
                        $('#messageModalTitle').text('Edit System Message');
                        $('#message_id').val(data.id);
                        $('#title').val(data.title);
                        $('#description').val(data.description);
                        $('#type').val(data.type);
                        $('#start_date').val(data.start_date);
                        $('#end_date').val(data.end_date);
                        $('#enable_email').prop('checked', data.enable_email);
                        $('#email').val(data.email);

                        toggleEmailInput();
                        systemMessageModal.show();
                    }
                });
            });

            $(document).on('click', '.btn-delete-message', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Delete system message?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    appAjax({
                        url: `/admin/system-message/item/${id}`,
                        method: 'DELETE',
                        onSuccess: function(res) {
                            Swal.fire({
                                title: 'Success',
                                text: res.message,
                                icon: 'success'
                            });
                            systemMessageTable.ajax.reload(null, false);
                        },
                        onError: function(xhr) {
                            Swal.fire({
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Delete failed.',
                                icon: 'error'
                            });
                        }
                    });
                });
            });

            $.validator.addMethod('multiEmailSemicolon', function(value, element) {
                if (!$('#enable_email').is(':checked')) return true;
                if (!value) return true;

                const emails = value.split(';').map(e => e.trim()).filter(e => e !== '');
                const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                for (let i = 0; i < emails.length; i++) {
                    if (!pattern.test(emails[i])) {
                        return false;
                    }
                }

                return true;
            }, 'Please enter valid email addresses separated by semicolon (;).');

            $('#systemMessageForm').validate({
                ignore: [],
                errorClass: 'is-invalid',
                validClass: 'is-valid',
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');

                    if (element.hasClass('form-check-input')) {
                        error.insertAfter(element.closest('.form-check'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                rules: {
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    description: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    email: {
                        multiEmailSemicolon: true
                    }
                },
                messages: {
                    title: {
                        required: 'Title is required.'
                    },
                    description: {
                        required: 'Description is required.'
                    }
                },
                submitHandler: function(form) {
                    const id = $('#message_id').val();
                    const url = id ?
                        `/admin/system-message/item/${id}/update` :
                        `{{ route('system_message_store', $company->id) }}`;

                    appAjax({
                        url: url,
                        method: 'POST',
                        data: $(form).serialize(),
                        onSuccess: function(res) {
                            Swal.fire({
                                title: 'Success',
                                text: res.message,
                                icon: 'success'
                            });
                            systemMessageModal.hide();
                            systemMessageTable.ajax.reload(null, false);
                        },
                        onError: function(xhr) {
                            let msg = xhr.responseJSON?.message || 'Save failed.';
                            Swal.fire({
                                title: 'Error',
                                text: msg,
                                icon: 'error'
                            });
                        }
                    });

                    return false;
                }
            });
        });
    </script>
@endpush
