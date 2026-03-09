<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\SystemMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class SystemMessageAdminController extends Controller
{
    public function index(CompanyProfile $companyProfile)
    {
        return view('admin.company.system-message', [
            'company' => $companyProfile,
        ]);
    }

    public function list(Request $request, CompanyProfile $companyProfile): JsonResponse
    {
        $query = $companyProfile->systemMessages()->latest('id');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('start_date', function ($row) {
                return optional($row->start_date)->format('Y-m-d H:i:s');
            })
            ->editColumn('end_date', function ($row) {
                return optional($row->end_date)->format('Y-m-d H:i:s');
            })
            ->editColumn('enable_email', function ($row) {
                return $row->enable_email ? 'Yes' : 'No';
            })
            ->editColumn('email', function ($row) {
                return $row->email ? implode(';', $row->email) : '';
            })
            ->addColumn('action', function ($row) {
                return '
                <button type="button" class="btn btn-sm btn-warning btn-edit-message" data-id="' . $row->id . '">Edit</button>
                <button type="button" class="btn btn-sm btn-danger btn-delete-message" data-id="' . $row->id . '">Delete</button>
            ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function show(SystemMessage $systemMessage): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => [
                'id' => $systemMessage->id,
                'company_profile_id' => $systemMessage->company_profile_id,
                'title' => $systemMessage->title,
                'description' => $systemMessage->description,
                'type' => $systemMessage->type,
                'start_date' => optional($systemMessage->start_date)->format('Y-m-d\TH:i'),
                'end_date' => optional($systemMessage->end_date)->format('Y-m-d\TH:i'),
                'enable_email' => $systemMessage->enable_email,
                'email' => $systemMessage->email ? implode(';', $systemMessage->email) : '',
            ],
        ]);
    }

    public function store(Request $request, CompanyProfile $companyProfile): JsonResponse
    {
        $validated = $this->validateMessage($request);

        DB::beginTransaction();
        try {
            $message = new SystemMessage();
            $this->saveMessageData($message, $companyProfile->id, $validated, $request);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'System message created successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, SystemMessage $systemMessage): JsonResponse
    {
        $validated = $this->validateMessage($request);

        DB::beginTransaction();
        try {
            $this->saveMessageData($systemMessage, $systemMessage->company_profile_id, $validated, $request);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'System message updated successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(SystemMessage $systemMessage): JsonResponse
    {
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type' => ['required', Rule::in(['blue', 'orange', 'red'])],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'enable_email' => ['nullable', 'boolean'],
            'email' => ['nullable', 'string'],
        ]);
    }

    protected function saveMessageData(SystemMessage $message, int $companyProfileId, array $validated, Request $request): void
    {
        $message->company_profile_id = $companyProfileId;
        $message->title = $validated['title'];
        $message->description = $validated['description'];
        $message->type = $validated['type'];
        $message->start_date = $validated['start_date'] ?? null;
        $message->end_date = $validated['end_date'] ?? null;
        $message->enable_email = $request->boolean('enable_email');

        if ($message->enable_email && !empty($validated['email'])) {
            $emails = collect(explode(';', $validated['email']))
                ->map(fn($email) => trim($email))
                ->filter()
                ->unique()
                ->values()
                ->all();

            foreach ($emails as $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    abort(response()->json([
                        'status' => false,
                        'message' => "Invalid email format: {$email}",
                    ], 422));
                }
            }

            $message->email = $emails;
        } else {
            $message->email = null;
        }

        $message->save();
    }
}
