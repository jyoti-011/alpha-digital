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
            $table->string('status')->default('draft')->after('is_active');
        });

        // Copy existing data
        \DB::table('carousels')->update([
            'status' => \DB::raw("IF(is_active = 1, 'published', 'draft')")
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carousels', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
