<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class SystemMessageEmailLog extends Model implements AuditableContract
{
    use Auditable;
    protected $fillable = [
        'system_message_id',
        'recipient_email',
        'scheduled_offset_day',
        'scheduled_for',
        'trigger_type',
        'status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function systemMessage(): BelongsTo
    {
        return $this->belongsTo(SystemMessage::class, 'system_message_id', 'msg_id');
    }
}