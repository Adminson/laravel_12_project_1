<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('select_input_lists', function (Blueprint $table) {
            $table->id();
            $table->string('data_type', 100);
            $table->string('select_value', 255);

            // Optional but recommended (prevents duplicate values per data_type)
            $table->unique(['data_type', 'select_value']);

            // Optional (remove if you truly don't want it)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('selectInputLists');
    }
};
