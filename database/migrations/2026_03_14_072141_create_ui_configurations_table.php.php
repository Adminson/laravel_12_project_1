<?php
// database/migrations/2026_03_14_000001_create_ui_configurations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ui_configurations', function (Blueprint $table) {
            $table->id();

            $table->string('config_key', 100)->unique();
            $table->string('config_name', 150);
            $table->boolean('is_active')->default(true);

            // Localization
            $table->string('date_format', 50)->default('uk_dmy');
            $table->string('time_format', 50)->default('24h_2359');
            $table->string('date_separator', 5)->default('-');
            $table->string('currency_symbol', 10)->nullable();
            $table->boolean('search_wildcard_enabled')->default(true);

            // Modal frame
            $table->unsignedSmallInteger('modal_frame_width')->default(1048);
            $table->unsignedSmallInteger('modal_frame_height')->default(600);
            $table->boolean('modal_overlay_close_enabled')->default(false);

            // Label
            $table->string('label_font_family', 100)->default('Arial, sans-serif');
            $table->unsignedTinyInteger('label_font_size')->default(14);
            $table->string('label_color', 20)->default('#000000');
            $table->string('label_font_weight', 20)->default('normal');
            $table->string('label_position', 30)->default('top');

            // Input
            $table->string('input_font_family', 100)->default('Arial, sans-serif');
            $table->unsignedTinyInteger('input_font_size')->default(14);
            $table->string('input_color', 20)->default('#000000');
            $table->unsignedTinyInteger('input_margin')->default(8);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ui_configurations');
    }
};
