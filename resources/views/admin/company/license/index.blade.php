@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a
                class="nav-link"
                href="{{ route('setting.company.edit', $company->cmp_id) }}"
            >
                Company
            </a>
        </li>

        <li class="nav-item">
            <a
                class="nav-link"
                href="{{ route('setting.system_message.index', $company->cmp_id) }}"
            >
                Messages
            </a>
        </li>

        <li class="nav-item">
            <a
                class="nav-link active"
                href="{{ route('setting.license.index', $company->cmp_id) }}"
            >
                License
            </a>
        </li>
    </ul>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">License</h4>
        <x-ui.link
            :href="route('setting.company.edit', $company->cmp_id)"
            variant="secondary"
        >
            Back
        </x-ui.link>
    </div>

    <div class="card license-display-card">
        <div class="card-body">
            {{-- Row 1 --}}
            <div class="row g-4">
                <div class="col-lg-6 d-flex">
                    <div class="license-panel w-100">
                        <div class="license-panel-title">License To</div>

                        <table class="table table-sm table-borderless license-info-table mb-0">
                            <tbody>
                                <tr>
                                    <th>Company</th>
                                    <td>
                                        {{ $company->cmp_company_name ?: '-' }}
                                        @if ($company->cmp_reg_no)
                                            <span class="text-muted">({{ $company->cmp_reg_no }})</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{!! nl2br(e($company->cmp_address ?: '-')) !!}</td>
                                </tr>
                                <tr>
                                    <th>Tel</th>
                                    <td>{{ $company->cmp_tel ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Fax</th>
                                    <td>{{ $company->cmp_fax ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Url</th>
                                    <td>{{ $company->cmp_url ?: '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-6 d-flex">
                    <div class="license-panel w-100">
                        <div class="license-panel-title">License Status</div>

                        <table class="table table-sm table-borderless license-info-table mb-0">
                            <tbody>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge text-bg-{{ $licenseStatus['type'] }}">
                                            {{ $licenseStatus['text'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Effective</th>
                                    <td>{{ optional($company->cmp_sub_start_date)->format('d-m-Y') ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Expiry</th>
                                    <td>{{ optional($company->cmp_sub_end_date)->format('d-m-Y') ?: '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Row 2 --}}
            <div class="row g-4 mt-1">
                <div class="col-12">
                    <div class="license-panel">
                        <div class="license-panel-title">Contact</div>

                        <table class="table table-sm table-borderless license-info-table mb-0">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $company->cmp_contact_person ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Mobile</th>
                                    <td>{{ $company->cmp_mobile ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $company->cmp_contact_email ?: '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .license-display-card {
            border: 1px solid #d9d9d9;
        }

        .license-panel {
            border: 1px solid #d9d9d9;
            padding: 0.85rem;
            background: #fff;
            position: relative;
        }

        .license-panel-title {
            position: absolute;
            top: -12px;
            left: 12px;
            padding: 0 8px;
            background: #fff;
            color: #0d6efd;
            font-weight: 600;
        }

        .license-info-table th {
            width: 140px;
            font-weight: 700;
            color: #495057;
            white-space: nowrap;
        }

        .license-info-table th,
        .license-info-table td {
            padding: 0.35rem 0.5rem;
            vertical-align: top;
            border: 0;
        }
    </style>
@endpush
