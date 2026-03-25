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
        Schema::create('memos', function (Blueprint $table) {
            $table->id();

            // Polymorphic relation
            $table->string('memoable_type', 150);
            $table->unsignedBigInteger('memoable_id');

            // Memo content
            $table->longText('content');

            // Audit
            $table->string('created_by', 150)->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['memoable_type', 'memoable_id'], 'idx_memos_memoable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memos');
    }
};
