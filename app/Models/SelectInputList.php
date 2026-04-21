<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class SelectInputList extends Model implements AuditableContract
{
    use Auditable;
    protected $table = 'select_input_lists';

    protected $fillable = [
        'data_type',
        'select_value',
    ];
}
