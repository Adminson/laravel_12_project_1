<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectInputList extends Model
{
    protected $table = 'select_input_lists';

    protected $fillable = [
        'data_type',
        'select_value',
    ];
}
