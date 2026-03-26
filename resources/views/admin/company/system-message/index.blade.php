@extends('layouts.app')
@push('styles')
    <style>
        #systemMessageTable {
            width: 100% !important;
        }

        #systemMessageTable thead th {
            white-space: nowrap;
            vertical-align: middle;
        }

        #systemMessageTable tbody td {
            vertical-align: top !important;
        }

        #systemMessageTable .message-cell {
            min-width: 380px;
            max-width: 520px;
        }

        .system-message-alert-wrapper {
            min-width: 300px;
        }

        .system-message-alert-title {
            font-weight: 700;
            font-size: 1rem;
            color: #ff4d4f;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .system-message-alert-box {
            margin-bottom: 8px !important;
        }

        .system-message-alert-meta {
            line-height: 1.45;
        }

        .system-message-action .btn {
            min-width: 70px;
            margin-bottom: 6px;
        }
    </style>
@endpush
@section('content')
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a
                class="nav-link"
                href="{{ route('setting.company.edit', $company->cmp_id) }}"
            >
                Company Details
            </a>
        </li>
        <li class="nav-item">
            <a
                class="nav-link active"
                href="{{ route('setting.system_message.index', $company->cmp_id) }}"
            >
                System Message
            </a>
        </li>
    </ul>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">System Message - {{ $company->cmp_company_name }}</h4>
        </div>

        <a
            href="{{ route('setting.system_message.create', $company->cmp_id) }}"
            class="btn btn-primary"
        >
            Create
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <x-alert.alert-session />

            <table
                class="table table-bordered align-middle"
                id="systemMessageTable"
            >
                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th width="420">Message</th>
                        <th>Suspend Login</th>
                        <th>Alert Start</th>
                        <th>Before / After</th>
                        <th>Date Type</th>
                        <th>Term</th>
                        <th>Date 1</th>
                        <th>Date 2</th>
                        <th width="160">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            const table = $('#systemMessageTable').DataTable({
                processing: true,
                serverSide: false,
                searching: true,
                ordering: true,
                responsive: false,
                autoWidth: false,
                ajax: {
                    url: '{{ route('setting.system_message.list', $company->cmp_id) }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center align-middle',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'message_html',
                        name: 'message_html',
                        orderable: false,
                        searchable: true,
                        className: 'message-cell'
                    },
                    {
                        data: 'msg_suspend_login',
                        name: 'msg_suspend_login'
                    },
                    {
                        data: 'msg_start_day',
                        name: 'msg_start_day'
                    },
                    {
                        data: 'msg_before_after',
                        name: 'msg_before_after'
                    },
                    {
                        data: 'msg_date_type',
                        name: 'msg_date_type'
                    },
                    {
                        data: 'msg_term',
                        name: 'msg_term'
                    },
                    {
                        data: 'msg_start_date',
                        name: 'msg_start_date'
                    },
                    {
                        data: 'msg_end_date',
                        name: 'msg_end_date'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle system-message-action'
                    }
                ],
                order: [
                    [0, 'asc']
                ],
                pageLength: 10,
                language: {
                    emptyTable: 'No system messages found.'
                }
            });

            $(document).on('click', '.btn-delete-message', function() {
                const url = $(this).data('url');

                Swal.fire({
                    title: 'Delete system message?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    appAjax({
                        url: url,
                        method: 'DELETE',
                        onSuccess: function(res) {
                            Swal.fire({
                                title: 'Success',
                                text: res.message,
                                icon: 'success'
                            });

                            table.ajax.reload(null, false);
                        },
                        onError: function(xhr) {
                            Swal.fire({
                                title: 'Error',
                                text: xhr.responseJSON?.message ||
                                    'Delete failed.',
                                icon: 'error'
                            });
                        }
                    });
                });
            });
        });
    </script>
@endpush
