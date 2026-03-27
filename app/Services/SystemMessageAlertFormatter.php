<?php

namespace App\Services;

use App\Models\CompanyProfile;
use App\Models\SystemMessage;
use Illuminate\Support\Collection;

class SystemMessageAlertFormatter
{
    protected const ALERT_STYLES = [
        'blue' => ['class' => 'alert-solid-success', 'icon' => 'tabler-check'],
        'red' => ['class' => 'alert-solid-danger', 'icon' => 'tabler-ban'],
        'orange' => ['class' => 'alert-solid-warning', 'icon' => 'tabler-bell'],
    ];

    public function formatCollection(
        Collection $systemMessages,
        CompanyProfile $companyProfile
    ): Collection {
        return $systemMessages->map(
            fn(SystemMessage $systemMessage) => $this->format($systemMessage, $companyProfile)
        );
    }

    public function format(
        SystemMessage $systemMessage,
        CompanyProfile $companyProfile
    ): array {
        $referenceDate = $systemMessage->getReferenceDate();
        $alertShowFrom = $systemMessage->getAlertShowFrom();
        $alertShowUntil = $systemMessage->getAlertShowUntil();

        $alertStyle = $this->resolveStyle($systemMessage->msg_color);

        return [
            'id' => $systemMessage->msg_id,
            'title' => $systemMessage->msg_title,
            'type' => $systemMessage->msg_color,
            'style_class' => $alertStyle['class'],
            'style_icon' => $alertStyle['icon'],

            'reference_label' => $systemMessage->getReferenceDateLabel(),
            'reference_date' => $referenceDate,
            'reference_date_text' => optional($referenceDate)->format('d/m/Y h:i A'),

            'show_from' => $alertShowFrom,
            'show_until' => $alertShowUntil,
            'show_from_text' => optional($alertShowFrom)->format('d/m/Y h:i A'),
            'show_until_text' => $alertShowUntil
                ? $alertShowUntil->format('d/m/Y h:i A')
                : 'Until deleted',

            'formatted_description' => $this->formatDescription($systemMessage, $companyProfile),
            'is_active_now' => $systemMessage->isAlertActive(now()),
            'model' => $systemMessage,
        ];
    }

    protected function resolveStyle(?string $type): array
    {
        return self::ALERT_STYLES[$type] ?? self::ALERT_STYLES['blue'];
    }

    protected function formatDescription(
        SystemMessage $systemMessage,
        CompanyProfile $companyProfile
    ): string {
        [$date1, $date2] = $this->resolveDescriptionDates($systemMessage, $companyProfile);

        $program = $companyProfile->cmp_company_name ?? '';

        return str_replace(
            [
                '[date1]',
                '[date2]',
                '[program]',
                '{{ date1 }}',
                '{{ date2 }}',
                '{{ program }}',
            ],
            [
                $date1,
                $date2,
                $program,
                $date1,
                $date2,
                $program,
            ],
            $systemMessage->msg_description ?? ''
        );
    }

    protected function resolveDescriptionDates(
        SystemMessage $systemMessage,
        CompanyProfile $companyProfile
    ): array {
        $dateReference = strtolower((string) $systemMessage->msg_date_reference);

        if ($dateReference === 'subscribe_date') {
            return [
                optional($companyProfile->cmp_sub_start_date)->format('d-M-Y h:i A') ?? '',
                optional($companyProfile->cmp_sub_end_date)->format('d-M-Y h:i A') ?? '',
            ];
        }

        return [
            optional($systemMessage->msg_start_date)->format('d-M-Y h:i A') ?? '',
            optional($systemMessage->msg_end_date)->format('d-M-Y h:i A') ?? '',
        ];
    }
}
