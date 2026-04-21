@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Company List</h4>
            <a
                href="{{ route('setting.company.create') }}"
                class="btn btn-primary"
            >
                Create
            </a>
        </div>
        <div class="card-body">
            <table
                class="table table-bordered"
                id="companyTable"
                width="100%"
            >
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>Company Name</th>
                        <th>Reg No</th>
                        <th>Contact Person</th>
                        <th>Subscription Start</th>
                        <th>Subscription End</th>
                        <th width="120">Suspend</th>
                        <th width="220">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let companyTable;

        $(function() {
            companyTable = $('#companyTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('setting.company.list') }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'cmp_company_name',
                        name: 'cmp_company_name'
                    },
                    {
                        data: 'cmp_reg_no',
                        name: 'cmp_reg_no'
                    },
                    {
                        data: 'cmp_contact_person',
                        name: 'cmp_contact_person'
                    },
                    {
                        data: 'cmp_sub_start_date',
                        name: 'cmp_sub_start_date'
                    },
                    {
                        data: 'cmp_sub_end_date',
                        name: 'cmp_sub_end_date'
                    },
                    {
                        data: 'cmp_suspend_login',
                        name: 'cmp_suspend_login'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        $(document).on('click', '.btn-delete-company', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Delete company?',
                text: 'This will soft delete the company.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete'
            }).then((result) => {
                if (!result.isConfirmed) return;

                appAjax({
                    url: `/setting/company/${id}`,
                    method: 'DELETE',
                    onSuccess: function(res) {
                        Swal.fire({
                            title: 'Success',
                            text: res.message,
                            icon: 'success'
                        });
                        companyTable.ajax.reload(null, false);
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
    </script>
@endpush
