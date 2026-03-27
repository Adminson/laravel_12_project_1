<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Audit\AuditLogFormatter;
use App\Services\Audit\AuditLogResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Yajra\DataTables\Facades\DataTables;

class AuditLogController extends Controller
{
    public function list(
        Request $request,
        string|int $id,
        string $type,
        AuditLogResolver $resolver,
        AuditLogFormatter $formatter
    ): JsonResponse {
        $model = $resolver->resolve($type, $id);

        $audits = $model->audits()
            ->with('user')
            ->latest()
            ->get();

        return response()->json([
            'data' => $formatter->formatCollection($audits),
        ]);
    }

    public function fullList()
    {
        return view('admin.audit.full-list');
    }

    public function fullListData(Request $request, AuditLogFormatter $formatter)
    {
        $query = Audit::query()
            ->with(['user'])
            ->select([
                'audits.id',
                'audits.user_id',
                'audits.user_type',
                'audits.event',
                'audits.auditable_id',
                'audits.auditable_type',
                'audits.old_values',
                'audits.new_values',
                'audits.url',
                'audits.ip_address',
                'audits.user_agent',
                'audits.tags',
                'audits.created_at',
            ]);

        return DataTables::eloquent($query)
            ->addIndexColumn()

            ->editColumn('created_at', function (Audit $audit) {
                return optional($audit->created_at)->format('d-M-Y h:i A');
            })

            ->editColumn('event', function (Audit $audit) use ($formatter) {
                return $formatter->formatEventBadge($audit->event);
            })

            ->addColumn('module', function (Audit $audit) {
                return $this->formatModuleName($audit->auditable_type);
            })

            ->addColumn('record_id', function (Audit $audit) {
                return $audit->auditable_id ?? '-';
            })

            ->addColumn('staff_name', function (Audit $audit) {
                return $audit->user?->name ?? $audit->user?->email ?? 'System';
            })

            ->addColumn('old_values_html', function (Audit $audit) use ($formatter) {
                return $formatter->formatValuesForTable($audit->old_values ?? []);
            })

            ->addColumn('new_values_html', function (Audit $audit) use ($formatter) {
                return $formatter->formatValuesForTable($audit->new_values ?? []);
            })

            ->editColumn('ip_address', function (Audit $audit) {
                return $audit->ip_address ?: '-';
            })

            ->filterColumn('module', function ($query, $keyword) {
                $query->where('auditable_type', 'like', "%{$keyword}%");
            })

            ->filterColumn('staff_name', function ($query, $keyword) {
                $query->whereHas('user', function ($userQuery) use ($keyword) {
                    $userQuery->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                });
            })

            ->orderColumn('module', function ($query, $order) {
                $query->orderBy('auditable_type', $order);
            })

            ->orderColumn('record_id', function ($query, $order) {
                $query->orderBy('auditable_id', $order);
            })

            ->rawColumns([
                'event',
                'old_values_html',
                'new_values_html',
            ])
            ->make(true);
    }

    protected function formatModuleName(?string $auditableType): string
    {
        return match (class_basename($auditableType)) {
            'CompanyProfile' => 'Company Profile',
            'SystemMessage' => 'System Message',
            'UiConfiguration' => 'UI Configuration',
            default => class_basename($auditableType ?? '-'),
        };
    }
}
