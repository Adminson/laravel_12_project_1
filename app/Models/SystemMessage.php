<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class SystemMessage extends Model
{
    protected $table = 'system_message';
    protected $primaryKey = 'msg_id';

    const CREATED_AT = 'msg_createdon';
    const UPDATED_AT = 'msg_modifiedon';

    protected $fillable = [
        'msg_company_profile_id',
        'msg_title',
        'msg_description',
        'msg_type',
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
     * Base date used for timing calculation.
     * date1 = msg_start_date
     * date2 = msg_end_date
     */
    public function getTimingBaseDate(): ?Carbon
    {
        return match (strtolower((string) $this->msg_date_type)) {
            'date2' => $this->msg_end_date?->copy(),
            default => $this->msg_start_date?->copy(),
        };
    }

    /**
     * Start showing alert from this datetime.
     */
    public function getAlertShowFrom(): ?Carbon
    {
        $baseDate = $this->getTimingBaseDate();

        if (! $baseDate) {
            return null;
        }

        $days = (int) ($this->msg_start_day ?? 0);

        return match (strtolower((string) $this->msg_before_after)) {
            'after' => $baseDate->copy()->addDays($days),
            default => $baseDate->copy()->subDays($days),
        };
    }

    /**
     * End showing alert at this datetime.
     *
     * msg_term > 0  => show_from + term days
     * msg_term = 0  => unlimited until deleted
     */
    public function getAlertShowUntil(): ?Carbon
    {
        $showFrom = $this->getAlertShowFrom();

        if (! $showFrom) {
            return null;
        }

        $term = (int) ($this->msg_term ?? 0);

        if ($term <= 0) {
            return null; // unlimited
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

        // Unlimited
        if ($showUntil === null) {
            return $checkDate->gte($showFrom);
        }

        return $checkDate->gte($showFrom) && $checkDate->lte($showUntil);
    }
}