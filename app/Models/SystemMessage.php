<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class SystemMessage extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'system_message';
    protected $primaryKey = 'msg_id';

    const CREATED_AT = 'msg_createdon';
    const UPDATED_AT = 'msg_modifiedon';

    protected $fillable = [
        'msg_company_profile_id',
        'msg_title',
        'msg_description',
        'msg_date_reference',
        'msg_color',
        'msg_suspend_login',
        'msg_start_day',
        'msg_before_after',
        'msg_date_type',
        'msg_term',
        'msg_start_date',
        'msg_end_date',
        'msg_enable_email',
        'msg_last_date_sent_email',
        'msg_email_date',
        'msg_email',
        'msg_createdby',
        'msg_modifiedby',
        'msg_version',
        'msg_viewedon',
        'msg_viewedby',
        'msg_hit',
    ];

    protected $casts = [
        'msg_suspend_login' => 'boolean',
        'msg_start_date' => 'datetime',
        'msg_end_date' => 'datetime',
        'msg_enable_email' => 'boolean',
        'msg_last_date_sent_email' => 'datetime',
        'msg_email' => 'array',
        'msg_createdon' => 'datetime',
        'msg_modifiedon' => 'datetime',
        'msg_viewedon' => 'datetime',
        'msg_version' => 'integer',
        'msg_hit' => 'integer',
        'msg_start_day' => 'integer',
        'msg_term' => 'integer',
    ];

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'msg_company_profile_id', 'cmp_id');
    }

    /**
     * Resolve actual reference date based on:
     * - msg_date_reference: subscribe_date | message_date
     * - msg_date_type: date1 | date2
     *
     * Mapping:
     * subscribe_date + date1 => company cmp_sub_start_date
     * subscribe_date + date2 => company cmp_sub_end_date
     * message_date   + date1 => msg_start_date
     * message_date   + date2 => msg_end_date
     */
    public function getReferenceDate(): ?Carbon
    {
        $reference = strtolower((string) $this->msg_date_reference);
        $dateType = strtolower((string) $this->msg_date_type);

        $company = $this->relationLoaded('companyProfile')
            ? $this->companyProfile
            : $this->companyProfile()->first();

        if ($reference === 'subscribe_date') {
            if (! $company) {
                return null;
            }

            return match ($dateType) {
                'date2' => $company->cmp_sub_end_date?->copy(),
                default => $company->cmp_sub_start_date?->copy(),
            };
        }

        return match ($dateType) {
            'date2' => $this->msg_end_date?->copy(),
            default => $this->msg_start_date?->copy(),
        };
    }

    /**
     * Start showing alert from this datetime.
     *
     * before => reference_date - msg_start_day
     * after  => reference_date + msg_start_day
     */
    public function getAlertShowFrom(): ?Carbon
    {
        $referenceDate = $this->getReferenceDate();

        if (! $referenceDate) {
            return null;
        }

        $days = max(0, (int) ($this->msg_start_day ?? 0));
        $beforeAfter = strtolower((string) $this->msg_before_after);

        return $beforeAfter === 'after'
            ? $referenceDate->copy()->addDays($days)
            : $referenceDate->copy()->subDays($days);
    }

    /**
     * End showing alert at this datetime.
     *
     * msg_term > 0 => show_from + term days
     * msg_term = 0 => unlimited until deleted
     */
    public function getAlertShowUntil(): ?Carbon
    {
        $showFrom = $this->getAlertShowFrom();

        if (! $showFrom) {
            return null;
        }

        $term = max(0, (int) ($this->msg_term ?? 0));

        if ($term === 0) {
            return null;
        }

        return $showFrom->copy()->addDays($term);
    }

    /**
     * True if alert should be visible at given datetime.
     */
    public function isAlertActive(?Carbon $checkDate = null): bool
    {
        $checkDate ??= now();

        $showFrom = $this->getAlertShowFrom();
        $showUntil = $this->getAlertShowUntil();

        if (! $showFrom) {
            return false;
        }

        if ($showUntil === null) {
            return $checkDate->gte($showFrom);
        }

        return $checkDate->gte($showFrom) && $checkDate->lte($showUntil);
    }

    /**
     * Optional helper for UI/debugging.
     */
    public function getReferenceDateLabel(): string
    {
        $reference = strtolower((string) $this->msg_date_reference);
        $dateType = strtolower((string) $this->msg_date_type);

        return match ($reference . ':' . $dateType) {
            'subscribe_date:date1' => 'Subscribe Start Date',
            'subscribe_date:date2' => 'Subscribe End Date',
            'message_date:date1' => 'Message Date 1',
            'message_date:date2' => 'Message Date 2',
            default => 'Unknown Reference Date',
        };
    }
}