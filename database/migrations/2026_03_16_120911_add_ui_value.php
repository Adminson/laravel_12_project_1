<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('ui_configurations')->insert([
            'id' => 1,
            'config_key' => 'TEST',
            'config_name' => 'ESG',
            'is_active' => 1,

            'date_format' => 'uk_dmy',
            'time_format' => '24h_2359',
            'date_separator' => '-',
            'currency_symbol' => null,
            'search_wildcard_enabled' => 1,

            'modal_frame_width' => 1048,
            'modal_frame_height' => 600,
            'modal_overlay_close_enabled' => 0,

            'label_font_family' => 'Arial, sans-serif',
            'label_font_size' => 14,
            'label_color' => '#000000',
            'label_font_weight' => 'normal',
            'label_position' => 'top',

            'input_font_family' => 'Arial, sans-serif',
            'input_font_size' => 14,
            'input_color' => '#000000',
            'input_margin' => 8,

            'created_at' => '2026-03-14 07:32:11',
            'updated_at' => '2026-03-16 11:51:58',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
