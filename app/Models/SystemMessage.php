<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemMessage extends Model
{
    protected $table = 'system_message';

    protected $primaryKey = 'msg_id';

    public $incrementing = true;

    protected $keyType = 'int';

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
    ];

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'msg_company_profile_id', 'cmp_id');
    }
}