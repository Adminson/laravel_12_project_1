@extends('layouts.app')

@section('content')
    <x-card.section-card title="Audit Log Monitoring" class="mt-0">
        <div class="mb-3">
            <p class="text-muted mb-0">
                View all audit activity recorded in the system.
            </p>
        </div>

        <div class="table-responsive">
            <table id="audit_full_list_table" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th style="width: 80px;">No</th>
                        <th style="width: 170px;">Created Date</th>
                        <th style="width: 120px;">Event</th>
                        <th style="width: 180px;">Module</th>
                        <th style="width: 100px;">Record ID</th>
                        <th style="width: 180px;">Staff Name</th>
                        <th>Old Values</th>
                        <th>New Values</th>
                    </tr>
                </thead>
            </table>
        </div>
    </x-card.section-card>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('#audit_full_list_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('setting.audit.audit-full-list-data') }}",
                order: [[1, 'desc']],
                pageLength: 25,
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'event',
                        name: 'event'
                    },
                    {
                        data: 'module',
                        name: 'module'
                    },
                    {
                        data: 'record_id',
                        name: 'record_id'
                    },
                    {
                        data: 'staff_name',
                        name: 'staff_name'
                    },
                    {
                        data: 'old_values_html',
                        name: 'old_values',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'new_values_html',
                        name: 'new_values',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush