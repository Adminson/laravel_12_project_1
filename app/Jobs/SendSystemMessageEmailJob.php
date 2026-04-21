<?php

namespace App\Jobs;

use App\Mail\SystemMessageNotificationMail;
use App\Models\SystemMessage;
use App\Models\SystemMessageEmailLog;
use App\Services\SystemMessageAlertFormatter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendSystemMessageEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $systemMessageId,
        public string $recipientEmail,
        public ?int $scheduledOffsetDay = null,
        public ?string $scheduledFor = null,
        public string $triggerType = 'scheduled'
    ) {}

    public function handle(SystemMessageAlertFormatter $formatter): void
    {
        $systemMessage = SystemMessage::query()
            ->with('companyProfile')
            ->findOrFail($this->systemMessageId);

        $company = $systemMessage->companyProfile;

        if (! $company) {
            throw new \RuntimeException('Company profile not found for system message.');
        }

        $formatted = $formatter->format($systemMessage, $company);

        $log = $this->createOrGetEmailLog($systemMessage);

        if (! $log) {
            // scheduled duplicate already exists and was skipped
            return;
        }

        try {
            Mail::to($this->recipientEmail)->send(
                new SystemMessageNotificationMail(
                    subjectLine: $systemMessage->msg_title,
                    htmlBody: $formatted['formatted_description'] ?? ($systemMessage->msg_description ?? ''),
                    companyName: $company->cmp_company_name ?? config('app.name'),
                    logoUrl: !empty($company->cmp_logo_path)
                        ? asset('storage/' . $company->cmp_logo_path)
                        : null
                )
            );

            DB::transaction(function () use ($log, $systemMessage) {
                $now = now();

                $log->update([
                    'status' => 'sent',
                    'sent_at' => $now,
                    'error_message' => null,
                ]);

                $systemMessage->forceFill([
                    'msg_last_date_sent_email' => $now,
                ])->save();
            });
        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    protected function createOrGetEmailLog(SystemMessage $systemMessage): ?SystemMessageEmailLog
    {
        // Manual resend must always create a fresh row
        if ($this->triggerType === 'manual') {
            return SystemMessageEmailLog::query()->create([
                'system_message_id' => $systemMessage->msg_id,
                'recipient_email' => $this->recipientEmail,
                'scheduled_offset_day' => $this->scheduledOffsetDay,
                'scheduled_for' => $this->scheduledFor,
                'trigger_type' => $this->triggerType,
                'status' => 'queued',
            ]);
        }

        // Scheduled send must be idempotent
        try {
            return SystemMessageEmailLog::query()->create([
                'system_message_id' => $systemMessage->msg_id,
                'recipient_email' => $this->recipientEmail,
                'scheduled_offset_day' => $this->scheduledOffsetDay,
                'scheduled_for' => $this->scheduledFor,
                'trigger_type' => $this->triggerType,
                'status' => 'queued',
            ]);
        } catch (QueryException $e) {
            // duplicate unique key => already queued/sent before
            if ((string) $e->getCode() === '23000') {
                return null;
            }

            throw $e;
        }
    }
}
