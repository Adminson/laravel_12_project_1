<?php

namespace App\Services\Audit;

use OwenIt\Auditing\Models\Audit;

class AuditLogFormatter
{
    public function formatCollection(iterable $audits): array
    {
        $rows = [];

        foreach ($audits as $audit) {
            $rows[] = $this->format($audit);
        }

        return $rows;
    }

    public function format(Audit $audit): array
    {
        return [
            'created_date' => optional($audit->created_at)->format('d-M-Y h:i A'),
            'event' => $this->formatEventBadge($audit->event),
            'staff_name' => $audit->user?->name ?? $audit->user?->email ?? 'System',
            'old_values' => $this->formatValuesForTable($audit->old_values ?? []),
            'new_values' => $this->formatValuesForTable($audit->new_values ?? []),
        ];
    }

    public function formatEventBadge(?string $event): string
    {
        if (blank($event)) {
            return '<span class="badge bg-secondary">Unknown</span>';
        }

        $eventLower = strtolower($event);

        $badgeClass = match ($eventLower) {
            'created' => 'success',
            'updated' => 'warning',
            'deleted' => 'danger',
            'restored' => 'info',
            default => 'secondary',
        };

        return '<span class="badge bg-' . $badgeClass . '">' . e(ucfirst($eventLower)) . '</span>';
    }

    public function formatValuesForTable(array $values): string
    {
        if (empty($values)) {
            return '<span class="text-muted">No data available</span>';
        }

        $excludedKeys = [
            'created_at',
            'updated_at',
        ];

        $html = '<div class="audit-value-list">';

        foreach ($values as $key => $value) {
            if (in_array($key, $excludedKeys, true)) {
                continue;
            }

            $label = str($key)->replace('_', ' ')->title()->toString();

            if (is_bool($value)) {
                $value = $value ? 'Yes' : 'No';
            } elseif (is_null($value)) {
                $value = '-';
            } elseif (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            }

            $html .= '
                <div class="mb-1">
                    <span class="fw-semibold text-dark">' . e($label) . ':</span>
                    <span class="text-body">' . nl2br(e((string) $value)) . '</span>
                </div>
            ';
        }

        $html .= '</div>';

        return $html;
    }
}