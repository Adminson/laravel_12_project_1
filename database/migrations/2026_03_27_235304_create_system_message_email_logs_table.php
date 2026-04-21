<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_message_email_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('system_message_id');
            $table->string('recipient_email', 255);
            $table->integer('scheduled_offset_day')->nullable();
            $table->dateTime('scheduled_for')->nullable();
            $table->string('trigger_type', 20); // scheduled | manual
            $table->string('status', 20)->default('queued'); // queued | sent | failed
            $table->dateTime('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->foreign('system_message_id', 'fk_sm_email_logs_message')
                ->references('msg_id')
                ->on('system_message')
                ->cascadeOnDelete();

            $table->index(
                ['system_message_id', 'recipient_email'],
                'idx_sm_email_msg_recipient'
            );

            $table->index(
                ['scheduled_for'],
                'idx_sm_email_scheduled_for'
            );

            $table->index(
                ['trigger_type', 'status'],
                'idx_sm_email_trigger_status'
            );

            $table->unique(
                ['system_message_id', 'recipient_email', 'scheduled_offset_day', 'scheduled_for', 'trigger_type'],
                'uniq_sm_email_schedule'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_message_email_logs');
    }
};