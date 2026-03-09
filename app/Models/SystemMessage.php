<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemMessage extends Model
{
    protected $table = 'system_message';

    protected $fillable = [
        'company_profile_id',
        'title',
        'description',
        'type',
        'start_date',
        'end_date',
        'enable_email',
        'email',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'enable_email' => 'boolean',
        'email' => 'array',
    ];

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }
}