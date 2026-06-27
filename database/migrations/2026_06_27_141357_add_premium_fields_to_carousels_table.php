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
        Schema::table('carousels', function (Blueprint $table) {
            $table->string('collection_tag')->nullable()->after('sub_heading');
            $table->string('seo_alt_text')->nullable()->after('button_link');
            $table->boolean('pinned')->default(false)->after('sort_order');
            $table->dateTime('start_date')->nullable()->after('is_active');
            $table->dateTime('end_date')->nullable()->after('start_date');
            
            $table->json('layout_settings')->nullable()->after('seo_alt_text');
            $table->json('design_settings')->nullable()->after('layout_settings');
            $table->json('animation_settings')->nullable()->after('design_settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carousels', function (Blueprint $table) {
            $table->dropColumn([
                'collection_tag',
                'seo_alt_text',
                'pinned',
                'start_date',
                'end_date',
                'layout_settings',
                'design_settings',
                'animation_settings'
            ]);
        });
    }
};
