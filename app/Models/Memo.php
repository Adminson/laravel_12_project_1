<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Memo extends Model
{
    protected $table = 'memos';

    protected $fillable = [
        'memoable_type',
        'memoable_id',
        'content',
        'created_by', //by name
    ];

    /**
     * Related parent model.
     * Example: Company, User, Payment, Configuration
     */
    public function memoable(): MorphTo
    {
        return $this->morphTo();
    }
}