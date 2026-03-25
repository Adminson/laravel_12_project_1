<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\SystemMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class SystemMessageAdminController extends Controller
{
    public function index(CompanyProfile $companyProfile)
    {
        $now = now();

        $activeSystemMessages = $companyProfile->systemMessages()
            ->whereNotNull('msg_start_date')
            ->whereNotNull('msg_end_date')
            ->latest('msg_id')
            ->get();

        return view('admin.company.system-message.index', [
            'company' => $companyProfile,
            'activeSystemMessages' => $activeSystemMessages,
        ]);
    }


    public function list(Request $request, CompanyProfile $companyProfile): JsonResponse
    {
        $query = $companyProfile->systemMessages()->latest('msg_id');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('msg_description', function ($row) {
                return Str::limit(strip_tags($row->msg_description), 100);
            })
            ->editColumn('msg_suspend_login', function ($row) {
                return $row->msg_suspend_login ? 'Yes' : 'No';
            })
            ->editColumn('msg_start_date', function ($row) {
                return optional($row->msg_start_date)->format('d-M-Y h:i A');
            })
            ->editColumn('msg_end_date', function ($row) {
                return optional($row->msg_end_date)->format('d-M-Y h:i A');
            })
            ->editColumn('msg_enable_email', function ($row) {
                return $row->msg_enable_email ? 'Yes' : 'No';
            })
            ->editColumn('msg_email', function ($row) {
                return !empty($row->msg_email) ? implode(';', $row->msg_email) : '';
            })
            ->addColumn('action', function ($row) use ($companyProfile) {
                $editUrl = route('setting.system_message.edit', [
                    'company_profile' => $companyProfile->cmp_id,
                    'system_message' => $row->msg_id,
                ]);

                $deleteUrl = route('setting.system_message.delete', [
                    'company_profile' => $companyProfile->cmp_id,
                    'system_message' => $row->msg_id,
                ]);

                return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>
                    <button type="button"
                        class="btn btn-sm btn-danger btn-delete-message"
                        data-url="' . $deleteUrl . '">
                        Delete
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create(CompanyProfile $companyProfile)
    {
        return view('admin.company.system-message.create', [
            'company' => $companyProfile,
            'systemMessage' => new SystemMessage(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request, CompanyProfile $companyProfile): RedirectResponse
    {
        $validated = $this->validateMessage($request);

        DB::beginTransaction();

        try {
            $message = new SystemMessage();

            $this->saveMessageData(
                $message,
                (int) $companyProfile->cmp_id,
                $validated,
                $request
            );

            DB::commit();

            return redirect()
                ->route('setting.system_message.index', $companyProfile->cmp_id)
                ->with('success', 'System message created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('danger', $e->getMessage());
        }
    }

    public function edit(CompanyProfile $companyProfile, SystemMessage $systemMessage)
    {
        abort_unless(
            (int) $systemMessage->msg_company_profile_id === (int) $companyProfile->cmp_id,
            404
        );

        return view('admin.company.system-message.edit', [
            'company' => $companyProfile,
            'systemMessage' => $systemMessage,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, CompanyProfile $companyProfile, SystemMessage $systemMessage): RedirectResponse
    {
        abort_unless(
            (int) $systemMessage->msg_company_profile_id === (int) $companyProfile->cmp_id,
            404
        );

        $validated = $this->validateMessage($request);

        DB::beginTransaction();

        try {
            $this->saveMessageData(
                $systemMessage,
                (int) $companyProfile->cmp_id,
                $validated,
                $request
            );

            DB::commit();

            return back()->with('success', 'System message updated successfully.');
            return redirect()
                ->route('setting.system_message.index', $companyProfile->cmp_id)
                ->with('success', 'System message updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('danger', $e->getMessage());
        }
    }

    public function destroy(CompanyProfile $companyProfile, SystemMessage $systemMessage): JsonResponse
    {
        abort_unless(
            (int) $systemMessage->msg_company_profile_id === (int) $companyProfile->cmp_id,
            404
        );

        DB::beginTransaction();

        try {
            $systemMessage->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'System message deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    protected function validateMessage(Request $request): array
    {
        return $request->validate([
            'msg_title' => ['required', 'string', 'max:255'],
            'msg_description' => ['required', 'string'],
            'msg_type' => ['required', Rule::in(['blue', 'orange', 'red'])],

            'msg_suspend_login' => ['required', 'boolean'],
            'msg_start_day' => ['nullable', 'string', 'max:50'],
            'msg_before_after' => ['required', Rule::in(['before', 'after'])],
            'msg_date_type' => ['required', Rule::in(['date1', 'date2'])],
            'msg_term' => ['required', 'integer'],

            'msg_start_date' => ['nullable', 'date'],
            'msg_end_date' => ['nullable', 'date', 'after_or_equal:msg_start_date'],

            'msg_enable_email' => ['nullable', 'boolean'],
            'msg_last_date_sent_email' => ['nullable', 'date'],
            'msg_email_date' => ['nullable', 'string', 'max:500'],
            'msg_email' => ['nullable', 'string'],
        ]);
    }

    protected function saveMessageData(SystemMessage $message, int $companyProfileId, array $validated, Request $request): void
    {
        $isNew = ! $message->exists;
        $authUser = auth()->user();
        $authName = $authUser->name ?? $authUser->email ?? 'system';

        $message->msg_company_profile_id = $companyProfileId;
        $message->msg_title = $validated['msg_title'];
        $message->msg_description = $validated['msg_description'];
        $message->msg_type = $validated['msg_type'];

        $message->msg_suspend_login = $request->boolean('msg_suspend_login');
        $message->msg_start_day = $validated['msg_start_day'] ?? null;
        $message->msg_before_after = $validated['msg_before_after'];
        $message->msg_date_type = $validated['msg_date_type'];
        $message->msg_term = (int) $validated['msg_term'];

        $message->msg_start_date = $validated['msg_start_date'] ?? null;
        $message->msg_end_date = $validated['msg_end_date'] ?? null;

        $message->msg_enable_email = $request->boolean('msg_enable_email');
        $message->msg_last_date_sent_email = $validated['msg_last_date_sent_email'] ?? null;
        $message->msg_email_date = $validated['msg_email_date'] ?? null;

        if ($message->msg_enable_email && !empty($validated['msg_email'])) {
            $emails = collect(explode(';', $validated['msg_email']))
                ->map(fn($email) => trim($email))
                ->filter()
                ->unique()
                ->values()
                ->all();

            foreach ($emails as $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw ValidationException::withMessages([
                        'msg_email' => "Invalid email format: {$email}",
                    ]);
                }
            }

            $message->msg_email = $emails;
        } else {
            $message->msg_email = null;
        }

        if ($isNew) {
            $message->msg_createdby = $authName;
            $message->msg_version = 1;
            $message->msg_hit = $message->msg_hit ?? 0;
        } else {
            $message->msg_version = ((int) $message->msg_version ?: 1) + 1;
        }

        $message->msg_modifiedby = $authName;

        $message->save();
    }
}
