<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Services\Audit\AuditLogFormatter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class CompanyAdminController extends Controller
{
    public function auditList(
        Request $request,
        CompanyProfile $companyProfile,
        AuditLogFormatter $formatter
    ): JsonResponse {
        $audits = $companyProfile->audits()
            ->with('user')
            ->latest()
            ->get();

        $rows = $formatter->formatCollection($audits);

        return response()->json([
            'data' => $rows,
        ]);
    }

    public function index()
    {
        return view('admin.company.index');
    }

    public function list(Request $request): JsonResponse
    {
        $query = CompanyProfile::query()->latest('cmp_id');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('cmp_sub_start_date', function ($row) {
                return optional($row->cmp_sub_start_date)->format('Y-m-d H:i:s');
            })
            ->editColumn('cmp_sub_end_date', function ($row) {
                return optional($row->cmp_sub_end_date)->format('Y-m-d H:i:s');
            })
            ->editColumn('cmp_suspend_login', function ($row) {
                return $row->cmp_suspend_login ? 'Yes' : 'No';
            })
            ->addColumn('action', function ($row) {
                $editUrl          = route('setting.company.edit', $row->cmp_id);
                $systemMessageUrl = route('setting.system_message.index', $row->cmp_id);

                return '
                    <div class="d-flex gap-1 flex-wrap">
                        <a href="' . $editUrl . '"
                            class="btn btn-sm btn-warning"
                            title="Edit">
                            <i class="icon-base ti tabler-pencil me-1"></i>Edit
                        </a>
                        <a href="' . $systemMessageUrl . '"
                            class="btn btn-sm btn-info"
                            title="System Message">
                            <i class="icon-base ti tabler-message me-1"></i>Msg
                        </a>
                        <button type="button"
                            class="btn btn-sm btn-danger btn-delete-company"
                            data-id="' . $row->cmp_id . '"
                            title="Delete">
                            <i class="icon-base ti tabler-trash me-1"></i>Delete
                        </button>
                    </div>
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
        $companyProfile->load('memos');

        // dd($companyProfile);

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
                'redirect_url' => route('setting.company.edit', $company->cmp_id),
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
                'redirect_url' => route('setting.company.edit', $companyProfile->cmp_id),
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
            'cmp_company_name' => ['required', 'string', 'max:255'],
            'cmp_reg_no' => ['nullable', 'string', 'max:255'],
            'cmp_address' => ['nullable', 'string'],

            'cmp_contact_person' => ['nullable', 'string', 'max:255'],
            'cmp_contact_email' => ['nullable', 'email', 'max:255'],
            'cmp_mobile' => ['nullable', 'string', 'max:50'],
            'cmp_tel' => ['nullable', 'string', 'max:50'],
            'cmp_fax' => ['nullable', 'string', 'max:50'],
            'cmp_url' => ['nullable', 'string', 'max:255'],

            'cmp_pdf_logo_folder' => ['nullable', 'string', 'max:255'],
            'cmp_pdf_header' => ['nullable', 'in:logo_only,text_only,logo_and_text'],
            'cmp_pdf_footer' => ['nullable', 'in:show,hide'],

            'cmp_header_title' => ['nullable', 'string', 'max:255'],
            'cmp_header_text' => ['nullable', 'string'],
            'cmp_footer_text' => ['nullable', 'string'],
            'cmp_logo_size' => ['nullable', 'integer', 'min:1', 'max:999'],

            'logo' => ['nullable', 'image', 'max:2048'],

            'cmp_sub_start_date' => ['nullable', 'date'],
            'cmp_sub_end_date' => ['nullable', 'date', 'after_or_equal:cmp_sub_start_date'],

            'cmp_active' => ['nullable', 'boolean'],
            'cmp_lock' => ['nullable', 'boolean'],
            'cmp_suspend_login' => ['nullable', 'boolean'],
            'cmp_suspend_reason' => ['nullable', 'string'],
        ]);
    }

    protected function saveCompanyData(CompanyProfile $company, array $validated, Request $request): void
    {
        $authUser = auth()->user();
        $authName = $authUser->name ?? $authUser->email ?? 'system';

        $company->cmp_company_name = $validated['cmp_company_name'];
        $company->cmp_reg_no = $validated['cmp_reg_no'] ?? null;
        $company->cmp_address = $validated['cmp_address'] ?? null;

        $company->cmp_contact_person = $validated['cmp_contact_person'] ?? null;
        $company->cmp_contact_email = $validated['cmp_contact_email'] ?? null;
        $company->cmp_mobile = $validated['cmp_mobile'] ?? null;
        $company->cmp_tel = $validated['cmp_tel'] ?? null;
        $company->cmp_fax = $validated['cmp_fax'] ?? null;
        $company->cmp_url = $validated['cmp_url'] ?? null;

        $company->cmp_pdf_logo_folder = $validated['cmp_pdf_logo_folder'] ?? '/images/epm/logo/';
        $company->cmp_pdf_header = $validated['cmp_pdf_header'] ?? 'logo_and_text';
        $company->cmp_pdf_footer = $validated['cmp_pdf_footer'] ?? 'show';

        $company->cmp_header_title = $validated['cmp_header_title'] ?? null;
        $company->cmp_header_text = $validated['cmp_header_text'] ?? null;
        $company->cmp_footer_text = $validated['cmp_footer_text'] ?? null;
        $company->cmp_logo_size = $validated['cmp_logo_size'] ?? 12;

        $company->cmp_sub_start_date = $validated['cmp_sub_start_date'] ?? null;
        $company->cmp_sub_end_date = $validated['cmp_sub_end_date'] ?? null;

        $company->cmp_active = $request->boolean('cmp_active');
        $company->cmp_lock = $request->boolean('cmp_lock');
        $company->cmp_suspend_login = $request->boolean('cmp_suspend_login');

        if ($company->cmp_suspend_login) {
            $company->cmp_suspend_reason = $validated['cmp_suspend_reason'] ?? null;
        } else {
            $company->cmp_suspend_reason = null;
        }

        if (!$company->exists) {
            $company->cmp_createdby = $authName;
            $company->cmp_version = 1;
        } else {
            $company->cmp_version = ((int) $company->cmp_version) + 1;
        }

        $company->cmp_modifiedby = $authName;

        if ($request->hasFile('logo')) {
            if ($company->cmp_logo_path && Storage::disk('public')->exists($company->cmp_logo_path)) {
                Storage::disk('public')->delete($company->cmp_logo_path);
            }

            $file = $request->file('logo');
            $filename = uniqid('company_') . '.' . $file->getClientOriginalExtension();

            $company->cmp_logo_path = $file->storeAs('company', $filename, 'public');
        }

        $company->save();
    }

    public function testPdf(CompanyProfile $companyProfile)
    {
        
        $allowedHeaderModes = ['logo_only', 'text_only', 'logo_and_text'];

        $headerMode = in_array($companyProfile->cmp_pdf_header, $allowedHeaderModes, true)
            ? $companyProfile->cmp_pdf_header
            : 'logo_and_text';

        $footerMode = $companyProfile->cmp_pdf_footer === 'hide'
            ? 'hide'
            : 'show';

        $logoSizePercent = (int) ($companyProfile->cmp_logo_size ?? 12);
        $logoSizePercent = max(1, min(100, $logoSizePercent));

        $showHeader = in_array($headerMode, $allowedHeaderModes, true);
        $showFooter = $footerMode === 'show';

        $logoDataUri = $this->resolvePdfLogoDataUriFromCompany($companyProfile);

        $pdf = Pdf::loadView('admin.company.pdf.test', [
            'headerTitle' => $companyProfile->cmp_header_title ?? '',
            'headerText' => $companyProfile->cmp_header_text ?? '',
            'footerText' => $companyProfile->cmp_footer_text ?? '',
            'contentText' => 'This a test',
            'headerMode' => $headerMode,
            'footerMode' => $footerMode,
            'showHeader' => $showHeader,
            'showFooter' => $showFooter,
            'logoDataUri' => $logoDataUri,
            'logoSizePercent' => $logoSizePercent,
            'company' => $companyProfile,
        ])->setPaper('a4', 'portrait');
        // dd('1');
        return $pdf->stream('company-' . $companyProfile->cmp_id . '-test.pdf');
    }

    protected function resolvePdfLogoDataUriFromCompany(CompanyProfile $companyProfile): ?string
    {
        if (empty($companyProfile->cmp_logo_path)) {
            return null;
        }

        if (!Storage::disk('public')->exists($companyProfile->cmp_logo_path)) {
            return null;
        }

        $absolutePath = Storage::disk('public')->path($companyProfile->cmp_logo_path);
        $mimeType = mime_content_type($absolutePath) ?: 'image/png';

        return $this->fileToDataUri($absolutePath, $mimeType);
    }

    protected function fileToDataUri(string $path, string $mimeType): ?string
    {
        if (!is_file($path)) {
            return null;
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            return null;
        }

        return 'data:' . $mimeType . ';base64,' . base64_encode($contents);
    }
}
