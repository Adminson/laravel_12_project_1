<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Memo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MemoController extends Controller
{
    /**
     * Store a new memo under any supported module.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'memoable_type' => ['required', 'string', 'max:150'],
            'memoable_id'   => ['required', 'integer'],
            'content'       => ['required', 'string'],
        ]);

        $allowedTypes = $this->allowedMemoableTypes();

        if (! array_key_exists($validated['memoable_type'], $allowedTypes)) {
            return response()->json([
                'message' => 'Invalid memo module type.',
            ], 422);
        }

        $modelClass = $allowedTypes[$validated['memoable_type']];

        /** @var Model|null $record */
        $record = $modelClass::query()->find($validated['memoable_id']);

        if (! $record) {
            return response()->json([
                'message' => 'Referenced record not found.',
            ], 404);
        }

        $memo = $record->memos()->create([
            'content'    => trim($validated['content']),
            'created_by' => auth()->user()->name ?? 'System',
        ]);

        return response()->json([
            'message' => 'Memo saved successfully.',
            'data' => [
                'id'         => $memo->id,
                'content'    => e($memo->content),
                'created_by' => $memo->created_by,
                'created_at' => $memo->created_at?->format('Y-m-d H:i:s'),
                'created_at_human' => $memo->created_at?->diffForHumans(),
            ],
        ]);
    }

    /**
     * Delete memo.
     */
    public function destroy(Memo $memo): JsonResponse
    {
        $memo->delete();

        return response()->json([
            'message' => 'Memo deleted successfully.',
        ]);
    }

    /**
     * Allowed memo modules.
     */
    private function allowedMemoableTypes(): array
    {
        return [
            'company'       => \App\Models\CompanyProfile::class,
            'configuration' => \App\Models\UiConfiguration::class,
            'payment'       => \App\Models\Payment::class,
            'user'          => \App\Models\User::class,
        ];
    }
}