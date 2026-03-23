<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyProfile extends Model
{
    use SoftDeletes;

    protected $table = 'company_profile';
    protected $primaryKey = 'cmp_id';

    const CREATED_AT = 'cmp_createdon';
    const UPDATED_AT = 'cmp_modifiedon';
    const DELETED_AT = 'cmp_deletedon';

    protected $fillable = [
        'cmp_active',
        'cmp_lock',
        'cmp_createdby',
        'cmp_modifiedby',
        'cmp_version',
        'cmp_viewedon',
        'cmp_viewedby',
        'cmp_hit',

        'cmp_company_name',
        'cmp_reg_no',
        'cmp_address',

        'cmp_contact_person',
        'cmp_contact_email',
        'cmp_mobile',
        'cmp_tel',
        'cmp_fax',
        'cmp_url',

        'cmp_pdf_logo_folder',
        'cmp_pdf_header',
        'cmp_pdf_footer',

        'cmp_header_title',
        'cmp_header_text',
        'cmp_footer_text',
        'cmp_logo_path',
        'cmp_logo_size',

        'cmp_sub_start_date',
        'cmp_sub_end_date',
        'cmp_suspend_login',
        'cmp_suspend_reason',
    ];

    protected $casts = [
        'cmp_active' => 'boolean',
        'cmp_lock' => 'boolean',
        'cmp_viewedon' => 'datetime',
        'cmp_hit' => 'integer',
        'cmp_logo_size' => 'integer',
        'cmp_sub_start_date' => 'datetime',
        'cmp_sub_end_date' => 'datetime',
        'cmp_suspend_login' => 'boolean',
        'cmp_createdon' => 'datetime',
        'cmp_modifiedon' => 'datetime',
        'cmp_deletedon' => 'datetime',
    ];

    public function memos(): MorphMany
    {
        return $this->morphMany(Memo::class, 'memoable')->latest();
    }

    public function systemMessages(): HasMany
    {
        return $this->hasMany(SystemMessage::class, 'company_profile_id', 'cmp_id');
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->cmp_logo_path) {
            return null;
        }

        return asset('storage/' . $this->cmp_logo_path);
    }
}
