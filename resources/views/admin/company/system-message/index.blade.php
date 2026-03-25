@extends('layouts.app')

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
                class="table table-bordered"
                id="systemMessageTable"
            >
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Suspend Login</th>
                        <th>Start</th>
                        <th>Before / After</th>
                        <th>Date Type</th>
                        <th>Term</th>
                        <th>Date 1</th>
                        <th>Date 2</th>
                        <th>Email Enabled</th>
                        <th>Email</th>
                        <th>Version</th>
                        <th>Hit</th>
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
                            $alertStyle = $alertStyles[$systemMessage->msg_type] ?? $alertStyles['blue'];

                            $formattedDescription = str_replace(
                                ['[date1]', '[date2]', '[program]', '{{ date1 }}', '{{ date2 }}', '{{ program }}'],
                                [
                                    optional($systemMessage->msg_start_date)->format('d-M-Y h:i A'),
                                    optional($systemMessage->msg_end_date)->format('d-M-Y h:i A'),
                                    $company->cmp_company_name,
                                    optional($systemMessage->msg_start_date)->format('d-M-Y h:i A'),
                                    optional($systemMessage->msg_end_date)->format('d-M-Y h:i A'),
                                    $company->cmp_company_name,
                                ],
                                $systemMessage->msg_description
                            );
                        @endphp

                        <div
                            class="alert {{ $alertStyle['class'] }} d-flex align-items-center mb-3"
                            role="alert"
                        >
                            <span class="alert-icon rounded">
                                <i class="icon-base ti {{ $alertStyle['icon'] }} icon-md"></i>
                            </span>

                            <div>
                                <strong>{{ $systemMessage->msg_title }}</strong>
                                <div>{!! $formattedDescription !!}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let systemMessageTable;

        $(function() {
            systemMessageTable = $('#systemMessageTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('setting.system_message.list', $company->cmp_id) }}',
                    type: 'GET'
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'msg_title',
                        name: 'msg_title'
                    },
                    {
                        data: 'msg_type',
                        name: 'msg_type'
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
                        data: 'msg_enable_email',
                        name: 'msg_enable_email'
                    },
                    {
                        data: 'msg_email',
                        name: 'msg_email',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'msg_version',
                        name: 'msg_version'
                    },
                    {
                        data: 'msg_hit',
                        name: 'msg_hit'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
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
        });
    </script>
@endpush