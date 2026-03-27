<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
class Memo extends Model implements AuditableContract
{
    use Auditable;
    protected $table = 'memos';

    protected $fillable = [
        'memoable_type',
        'memoable_id',
        'content',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function memoable(): MorphTo
    {
        return $this->morphTo();
    }
}