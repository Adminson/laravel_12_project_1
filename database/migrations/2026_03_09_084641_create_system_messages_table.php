<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_message', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('company_profile_id');
            $table->foreign('company_profile_id')
                ->references('cmp_id')
                ->on('company_profile')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('title', 255);
            $table->text('description');
            $table->enum('type', ['blue', 'orange', 'red'])->default('blue');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('enable_email')->default(false);
            $table->json('email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_message');
    }
};