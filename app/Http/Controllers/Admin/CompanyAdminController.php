<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CompanyAdminController extends Controller
{
    public function index()
    {
        return view('admin.company.index');
    }

    public function list(Request $request): JsonResponse
    {
        $query = CompanyProfile::query()->latest('id');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('sub_start_date', function ($row) {
                return optional($row->sub_start_date)->format('Y-m-d H:i:s');
            })
            ->editColumn('sub_end_date', function ($row) {
                return optional($row->sub_end_date)->format('Y-m-d H:i:s');
            })
            ->editColumn('suspend_login', function ($row) {
                return $row->suspend_login ? 'Yes' : 'No';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('setting.company.edit', $row->id);
                $systemMessageUrl = route('setting.system_message.index', $row->id);

                return '
                <a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>
                <a href="' . $systemMessageUrl . '" class="btn btn-sm btn-info">System Message</a>
                <button type="button" class="btn btn-sm btn-danger btn-delete-company" data-id="' . $row->id . '">Delete</button>
            ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create()
    {
        return view('admin.company.form', [
            'company' => new CompanyProfile(),
            'isEdit' => false,
        ]);
    }

    public function edit(CompanyProfile $companyProfile)
    {
        return view('admin.company.form', [
            'company' => $companyProfile,
            'isEdit' => true,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateCompany($request);

        DB::beginTransaction();
        try {
            $company = new CompanyProfile();
            $this->saveCompanyData($company, $validated, $request);
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Company created successfully.',
                'redirect_url' => route('setting.company.edit', $company->id),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, CompanyProfile $companyProfile): JsonResponse
    {
        $validated = $this->validateCompany($request);

        DB::beginTransaction();
        try {
            $this->saveCompanyData($companyProfile, $validated, $request);
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Company updated successfully.',
                'redirect_url' => route('setting.company.edit', $companyProfile->id),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(CompanyProfile $companyProfile): JsonResponse
    {
        DB::beginTransaction();
        try {
            $companyProfile->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Company deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    protected function validateCompany(Request $request): array
    {
        return $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'reg_no' => ['nullable', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'header_info' => ['nullable', 'string'],
            'footer_info' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'sub_start_date' => ['nullable', 'date'],
            'sub_end_date' => ['nullable', 'date', 'after_or_equal:sub_start_date'],
            'suspend_login' => ['nullable', 'boolean'],
            'suspend_reason' => ['nullable', 'string'],
        ]);
    }

    protected function saveCompanyData(CompanyProfile $company, array $validated, Request $request): void
    {
        $company->company_name = $validated['company_name'];
        $company->reg_no = $validated['reg_no'] ?? null;
        $company->contact = $validated['contact'] ?? null;
        $company->address = $validated['address'] ?? null;
        $company->header_info = $validated['header_info'] ?? null;
        $company->footer_info = $validated['footer_info'] ?? null;
        $company->sub_start_date = $validated['sub_start_date'] ?? null;
        $company->sub_end_date = $validated['sub_end_date'] ?? null;
        $company->suspend_login = $request->boolean('suspend_login');

        if ($company->suspend_login) {
            $company->suspend_reason = $validated['suspend_reason'] ?? null;
        } else {
            $company->suspend_reason = null;
        }

        if ($request->hasFile('logo')) {
            if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                Storage::disk('public')->delete($company->logo_path);
            }

            $file = $request->file('logo');
            $filename = uniqid('company_') . '.' . $file->getClientOriginalExtension();

            // saved physically at storage/app/public/company/XXXX.ext
            $company->logo_path = $file->storeAs('company', $filename, 'public');
        }

        $company->save();
    }
}
