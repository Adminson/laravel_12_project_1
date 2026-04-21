<?php

namespace App\Console\Commands;

use App\Jobs\SendSystemMessageEmailJob;
use App\Models\SystemMessage;
use App\Models\SystemMessageEmailLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DispatchSystemMessageEmailsCommand extends Command
{
    protected $signature = 'scheduler:dispatch-alert-emails';
    protected $description = 'Dispatch scheduled system message emails';

    // php artisan scheduler:dispatch-alert-emails

    public function handle(): int
    {
        $now = now();

        SystemMessage::query()
            ->with('companyProfile')
            ->where('msg_enable_email', true)
            ->whereNotNull('msg_email_date')
            ->whereNotNull('msg_email')
            ->orderBy('msg_id')
            ->chunkById(200, function ($messages) use ($now) {
                foreach ($messages as $systemMessage) {
                    Log::channel('email')->info("✅ systemMessage");
                    if (! $systemMessage->canAutoSendEmail()) {
                        continue;
                    }

                    foreach ($systemMessage->getEmailScheduleDates() as $schedule) {
                        Log::channel('email')->info("✅ schedule");
                        $sendAt = $schedule['send_at'];

                        if (! $sendAt || $sendAt->gt($now)) {
                            continue;
                        }

                        foreach ($systemMessage->getEmailRecipients() as $recipientEmail) {
                            Log::channel('email')->info("✅ recipientEmail");
                            $alreadySent = SystemMessageEmailLog::query()
                                ->where('system_message_id', $systemMessage->msg_id)
                                ->where('recipient_email', $recipientEmail)
                                ->where('scheduled_offset_day', $schedule['offset_day'])
                                ->where('scheduled_for', $sendAt)
                                ->where('trigger_type', 'scheduled')
                                ->where('status', 'sent')
                                ->exists();

                            if ($alreadySent) {
                                continue;
                            }

                            SendSystemMessageEmailJob::dispatch(
                                systemMessageId: $systemMessage->msg_id,
                                recipientEmail: $recipientEmail,
                                scheduledOffsetDay: $schedule['offset_day'],
                                scheduledFor: $sendAt->toDateTimeString(),
                                triggerType: 'scheduled'
                            );
                        }
                    }
                }
            }, 'msg_id', 'msg_id');

        return self::SUCCESS;
    }
}
