<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyProfile extends Model
{
    use SoftDeletes;

    protected $table = 'company_profile';

    protected $fillable = [
        'company_name',
        'reg_no',
        'contact',
        'address',
        'header_info',
        'footer_info',
        'logo_path',
        'sub_start_date',
        'sub_end_date',
        'suspend_login',
        'suspend_reason',
    ];

    protected $casts = [
        'sub_start_date' => 'datetime',
        'sub_end_date' => 'datetime',
        'suspend_login' => 'boolean',
    ];

    public function systemMessages(): HasMany
    {
        return $this->hasMany(SystemMessage::class, 'company_profile_id');
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path) {
            return null;
        }

        return asset('storage/' . $this->logo_path);
    }
}
