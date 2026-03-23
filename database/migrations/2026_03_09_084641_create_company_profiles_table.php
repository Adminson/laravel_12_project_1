<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_profile', function (Blueprint $table) {
            $table->bigIncrements('cmp_id');

            // system / audit / legacy-like columns from image 1
            $table->boolean('cmp_active')->default(true);
            $table->boolean('cmp_lock')->default(false);
            $table->dateTime('cmp_createdon')->nullable();
            $table->string('cmp_createdby', 255)->nullable();
            $table->dateTime('cmp_modifiedon')->nullable();
            $table->string('cmp_modifiedby', 255)->nullable();
            $table->unsignedInteger('cmp_version')->default(1);
            $table->dateTime('cmp_viewedon')->nullable();
            $table->string('cmp_viewedby', 255)->nullable();
            $table->unsignedBigInteger('cmp_hit')->default(0);

            // main company details
            $table->string('cmp_company_name', 255);
            $table->string('cmp_reg_no', 255)->nullable();
            $table->text('cmp_address')->nullable();

            // contact info (image 4)
            $table->string('cmp_contact_person', 255)->nullable();
            $table->string('cmp_contact_email', 255)->nullable();
            $table->string('cmp_mobile', 50)->nullable();
            $table->string('cmp_tel', 50)->nullable();
            $table->string('cmp_fax', 50)->nullable();
            $table->string('cmp_url', 255)->nullable();

            // PDF parameters (image 2)
            $table->string('cmp_pdf_logo_folder', 255)->nullable()->default('/images/epm/logo/');
            $table->string('cmp_pdf_header', 50)->default('logo_and_text'); // logo_only | text_only | logo_and_text
            $table->string('cmp_pdf_footer', 20)->default('show'); // show | hide

            // header & footer (image 3)
            $table->string('cmp_header_title', 255)->nullable();
            $table->text('cmp_header_text')->nullable();
            $table->text('cmp_footer_text')->nullable();
            $table->string('cmp_logo_path', 255)->nullable();
            $table->integer('cmp_logo_size')->default(12)->nullable();

            // subscription
            $table->dateTime('cmp_sub_start_date')->nullable();
            $table->dateTime('cmp_sub_end_date')->nullable();
            $table->boolean('cmp_suspend_login')->default(false);
            $table->text('cmp_suspend_reason')->nullable();

            // soft delete using cmp prefix
            $table->softDeletes('cmp_deletedon');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profile');
    }
};