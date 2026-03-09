<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_profile', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 255);
            $table->string('reg_no', 255)->nullable();
            $table->string('contact', 20)->nullable();
            $table->text('address')->nullable();
            $table->text('header_info')->nullable();
            $table->text('footer_info')->nullable();
            $table->string('logo_path', 255)->nullable();
            $table->dateTime('sub_start_date')->nullable();
            $table->dateTime('sub_end_date')->nullable();
            $table->boolean('suspend_login')->default(false);
            $table->text('suspend_reason')->nullable();
            $table->timestamps();
            $table->softDeletes(); // deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profile');
    }
};