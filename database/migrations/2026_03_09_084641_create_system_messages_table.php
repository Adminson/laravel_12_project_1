<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_message', function (Blueprint $table) {
            $table->bigIncrements('msg_id');

            $table->unsignedBigInteger('msg_company_profile_id');

            $table->foreign('msg_company_profile_id')
                ->references('cmp_id')
                ->on('company_profile')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('msg_title', 255);
            $table->longText('msg_description');

            $table->string('msg_type', 20)->default('blue');
            $table->boolean('msg_suspend_login')->default(false);

            $table->string('msg_start_day', 50)->nullable();
            $table->string('msg_before_after', 20)->default('before');
            $table->string('msg_date_type', 20)->default('date1');
            $table->integer('msg_term')->default(0);

            $table->dateTime('msg_start_date')->nullable();
            $table->dateTime('msg_end_date')->nullable();

            $table->boolean('msg_enable_email')->default(false);
            $table->dateTime('msg_last_date_sent_email')->nullable();
            $table->string('msg_email_date', 500)->nullable();
            $table->json('msg_email')->nullable();

            $table->dateTime('msg_createdon')->nullable();
            $table->string('msg_createdby',150)->nullable();

            $table->dateTime('msg_modifiedon')->nullable();
            $table->string('msg_modifiedby',150)->nullable();

            $table->unsignedInteger('msg_version')->default(1);
            $table->dateTime('msg_viewedon')->nullable();
            $table->string('msg_viewedby',150)->nullable();
            $table->unsignedInteger('msg_hit')->default(0);

            $table->index(['msg_start_date', 'msg_end_date'], 'idx_msg_active_date');
            $table->index(['msg_type'], 'idx_msg_type');
            $table->index(['msg_enable_email'], 'idx_msg_enable_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_message');
    }
};
