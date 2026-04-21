<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendSystemMessageEmailJob;
use App\Models\CompanyProfile;
use App\Models\SystemMessage;
use App\Models\SystemMessageEmailLog;
use App\Services\Audit\AuditLogFormatter;
use App\Services\SystemMessageAlertFormatter;
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

    public function index(CompanyProfile $companyProfile, SystemMessageAlertFormatter $alertFormatter)
    {
        $now = now();

        $activeSystemMessages = $companyProfile->systemMessages()
            ->with('companyProfile')
            ->latest('msg_id')
            ->get()
            ->filter(fn(SystemMessage $systemMessage) => $systemMessage->isAlertActive($now))
            ->values();

        $formattedAlertMessages = $alertFormatter->formatCollection(
            $activeSystemMessages,
            $companyProfile
        );

        return view('admin.company.system-message.index', [
            'company' => $companyProfile,
            'activeSystemMessages' => $activeSystemMessages,
            'formattedAlertMessages' => $formattedAlertMessages,
        ]);
    }

    public function list(Request $request, CompanyProfile $companyProfile, SystemMessageAlertFormatter $alertFormatter): JsonResponse
    {
        $systemMessages = $companyProfile->systemMessages()
            ->with('companyProfile')
            ->latest('msg_id')
            ->get()
            ->values();

        $data = $systemMessages->map(function ($row, $index) use ($companyProfile, $alertFormatter) {
            $editUrl = route('setting.system_message.edit', [
                'company_profile' => $companyProfile->cmp_id,
                'system_message' => $row->msg_id,
            ]);

            $deleteUrl = route('setting.system_message.delete', [
                'company_profile' => $companyProfile->cmp_id,
                'system_message' => $row->msg_id,
            ]);

            $formattedAlert = $alertFormatter->format($row, $companyProfile);

            $messageHtml = '
            <div class="system-message-alert-wrapper">
                <div class="system-message-alert-title">' . e($formattedAlert['title'] ?? $row->msg_title) . '</div>

                <div class="alert ' . e($formattedAlert['style_class'] ?? 'alert-primary') . ' d-flex align-items-center mb-2 system-message-alert-box" role="alert">
                    <span class="alert-icon rounded me-2">
                        <i class="icon-base ti ' . e($formattedAlert['style_icon'] ?? 'ti-info-circle') . ' icon-md"></i>
                    </span>

                    <div class="flex-grow-1">
                        ' . ($formattedAlert['formatted_description'] ?? e($row->msg_description)) . '
                    </div>
                </div>

                <div class="small text-muted system-message-alert-meta">
                    Show From: ' . e($formattedAlert['show_from_text'] ?? (optional($row->msg_start_date)->format('d/m/Y h:i A') ?: '-')) . '<br>
                    Show Until: ' . e($formattedAlert['show_until_text'] ?? (optional($row->msg_end_date)->format('d/m/Y h:i A') ?: '-')) . '
                </div>
            </div>
        ';

            return [
                'DT_RowIndex' => $index + 1,
                'message_html' => $messageHtml,
                'msg_date_reference' => $row->msg_date_reference,
                'msg_color' => $row->msg_color,
                'msg_suspend_login' => $row->msg_suspend_login ? 'Yes' : 'No',
                'msg_start_day' => $row->msg_start_day,
                'msg_before_after' => $row->msg_before_after,
                'msg_date_type' => $row->msg_date_type,
                'msg_term' => $row->msg_term,
                'msg_start_date' => optional($row->msg_start_date)->format('d-M-Y h:i A'),
                'msg_end_date' => optional($row->msg_end_date)->format('d-M-Y h:i A'),
                'action' => '
                <a href="' . $editUrl . '" class="btn btn-sm btn-warning mb-1">Edit</a>
                <button type="button"
                    class="btn btn-sm btn-danger btn-delete-message"
                    data-url="' . $deleteUrl . '">
                    Delete
                </button>
            ',
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
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

    public function editOld(CompanyProfile $companyProfile, SystemMessage $systemMessage)
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

    public function edit(
        CompanyProfile $companyProfile,
        SystemMessage $systemMessage,
        SystemMessageAlertFormatter $alertFormatter
    ) {
        abort_unless(
            (int) $systemMessage->msg_company_profile_id === (int) $companyProfile->cmp_id,
            404
        );

        $formattedAlertMessages = $alertFormatter->formatCollection(
            collect([$systemMessage]),
            $companyProfile
        );

        $systemMessage->load('memos');
        // dd($systemMessage->load('memos'));

        return view('admin.company.system-message.edit', [
            'company' => $companyProfile,
            'systemMessage' => $systemMessage,
            'isEdit' => true,
            'formattedAlertMessages' => $formattedAlertMessages,
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

    public function emailLogList(
        Request $request,
        CompanyProfile $companyProfile,
        SystemMessage $systemMessage
    ): JsonResponse {
        abort_unless(
            (int) $systemMessage->msg_company_profile_id === (int) $companyProfile->cmp_id,
            404
        );

        $query = SystemMessageEmailLog::query()
            ->where('system_message_id', $systemMessage->msg_id)
            ->select([
                'id',
                'recipient_email',
                'scheduled_offset_day',
                'scheduled_for',
                'trigger_type',
                'status',
                'sent_at',
                'error_message',
                'created_at',
            ])
            ->latest('id');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('scheduled_offset_day', function (SystemMessageEmailLog $row) {
                if (is_null($row->scheduled_offset_day)) {
                    return '-';
                }

                return $row->scheduled_offset_day > 0
                    ? '+' . $row->scheduled_offset_day
                    : (string) $row->scheduled_offset_day;
            })
            ->editColumn('scheduled_for', function (SystemMessageEmailLog $row) {
                return $row->scheduled_for
                    ? \Carbon\Carbon::parse($row->scheduled_for)->format('d-M-Y h:i:s A')
                    : '-';
            })
            ->editColumn('sent_at', function (SystemMessageEmailLog $row) {
                return $row->sent_at
                    ? \Carbon\Carbon::parse($row->sent_at)->format('d-M-Y h:i:s A')
                    : '-';
            })
            ->editColumn('trigger_type', function (SystemMessageEmailLog $row) {
                return $row->trigger_type
                    ? ucfirst($row->trigger_type)
                    : '-';
            })
            ->editColumn('status', function (SystemMessageEmailLog $row) {
                $status = strtolower((string) $row->status);

                $badgeClass = match ($status) {
                    'sent' => 'success',
                    'queued' => 'warning',
                    'failed' => 'danger',
                    default => 'secondary',
                };

                return '<span class="badge bg-' . $badgeClass . '">' . e(ucfirst($status ?: 'unknown')) . '</span>';
            })
            ->editColumn('error_message', function (SystemMessageEmailLog $row) {
                if (blank($row->error_message)) {
                    return '-';
                }

                return '<span class="text-danger">' . e($row->error_message) . '</span>';
            })
            ->rawColumns(['status', 'error_message'])
            ->toJson();
    }
    
    protected function validateMessage(Request $request): array
    {
        return $request->validate([
            'msg_title' => ['required', 'string', 'max:255'],
            'msg_description' => ['required', 'string'],
            'msg_date_reference' => ['required', Rule::in(['message_date', 'subscribe_date'])],
            'msg_color' => ['required', Rule::in(['blue', 'orange', 'red'])],

            'msg_suspend_login' => ['required', 'boolean'],
            'msg_start_day' => ['nullable', 'string', 'max:50'],
            'msg_before_after' => ['required', Rule::in(['before', 'after'])],
            'msg_date_type' => ['required', Rule::in(['date1', 'date2'])],
            'msg_term' => ['required', 'integer'],

            'msg_start_date' => ['nullable', 'date'],
            'msg_end_date' => ['nullable', 'date', 'after_or_equal:msg_start_date'],

            'msg_enable_email' => ['nullable', 'boolean'],
            'msg_email_date' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^\s*-?\d+\s*(,\s*-?\d+\s*)*$/',
            ],
            'msg_email' => [
                Rule::requiredIf(fn() => $request->boolean('msg_enable_email')),
                'nullable',
                'string',
                'max:2000',
            ],
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
        $message->msg_date_reference = $validated['msg_date_reference'];
        $message->msg_color = $validated['msg_color'];

        $message->msg_suspend_login = $request->boolean('msg_suspend_login');
        $message->msg_start_day = $validated['msg_start_day'] ?? null;
        $message->msg_before_after = $validated['msg_before_after'];
        $message->msg_date_type = $validated['msg_date_type'];
        $message->msg_term = (int) $validated['msg_term'];

        $message->msg_start_date = $validated['msg_start_date'] ?? null;
        $message->msg_end_date = $validated['msg_end_date'] ?? null;

        $message->msg_enable_email = $request->boolean('msg_enable_email');

        $message->msg_email_date = ! empty($validated['msg_email_date'])
            ? collect(explode(',', $validated['msg_email_date']))
            ->map(fn($value) => trim($value))
            ->filter(fn($value) => $value !== '' && preg_match('/^-?\d+$/', $value))
            ->map(fn($value) => (int) $value)
            ->unique()
            ->sort()
            ->implode(',')
            : null;

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

    public function resendEmail(
        CompanyProfile $companyProfile,
        SystemMessage $systemMessage
    ): JsonResponse {
        abort_unless(
            (int) $systemMessage->msg_company_profile_id === (int) $companyProfile->cmp_id,
            404
        );

        $systemMessage->loadMissing('companyProfile');

        if (! $systemMessage->msg_enable_email) {
            return response()->json([
                'status' => false,
                'message' => 'Email notification is disabled for this message.',
            ], 422);
        }

        $recipients = $systemMessage->getEmailRecipients();

        if (empty($recipients)) {
            return response()->json([
                'status' => false,
                'message' => 'No recipient email found.',
            ], 422);
        }

        foreach ($recipients as $recipientEmail) {
            SendSystemMessageEmailJob::dispatch(
                systemMessageId: $systemMessage->msg_id,
                recipientEmail: $recipientEmail,
                scheduledOffsetDay: null,
                scheduledFor: null,
                triggerType: 'manual'
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Manual email has been queued successfully.',
        ]);
    }
}
