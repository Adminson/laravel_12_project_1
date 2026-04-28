<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['user', 'staff', 'admin'])->default('user')->after('email');
            $table->boolean('account_lock')->default(false)->after('user_type');
            $table->string('mobile_no', 25)->nullable()->after('account_lock');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'account_lock', 'mobile_no']);
        });
    }
};
