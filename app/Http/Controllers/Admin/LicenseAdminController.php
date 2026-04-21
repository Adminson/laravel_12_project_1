<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\View\View;

class LicenseAdminController extends Controller
{
    public function index(CompanyProfile $company_profile): View
    {
        $company = $company_profile;

        $licenseStatus = $this->resolveLicenseStatus($company);

        return view('admin.company.license.index', [
            'company' => $company,
            'licenseStatus' => $licenseStatus,
        ]);
    }

    protected function resolveLicenseStatus(CompanyProfile $company): array
    {
        if ($company->cmp_suspend_login) {
            return [
                'text' => 'Suspended',
                'type' => 'warning',
            ];
        }

        if (blank($company->cmp_sub_end_date)) {
            return [
                'text' => '-',
                'type' => 'secondary',
            ];
        }

        if ($company->cmp_sub_end_date->isPast()) {
            return [
                'text' => 'Expired',
                'type' => 'danger',
            ];
        }

        return [
            'text' => 'Active',
            'type' => 'success',
        ];
    }
}