<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appraisal_goal_scores', function (Blueprint $table) {
            // Update individual score columns to support values up to 100.0 (e.g., 99.9 or 100.0)
            // decimal(4, 1) supports up to 999.9, which is safe for 100.0
            $table->decimal('staff_score', 4, 1)->nullable()->change();
            $table->decimal('appraiser_1_score', 4, 1)->nullable()->change();
            $table->decimal('appraiser_2_score', 4, 1)->nullable()->change();
            $table->decimal('average_score', 4, 1)->nullable()->change();
            
            // The 'weighted_score' and 'section_1_score' columns already have a large enough scale (5, 2)
            // to support up to 100.00, so they don't need to be changed here.
        });
    }

    public function down(): void
    {
        // Revert to previous settings if rollback is needed
        Schema::table('appraisal_goal_scores', function (Blueprint $table) {
            $table->decimal('staff_score', 3, 1)->nullable()->change(); 
            $table->decimal('appraiser_1_score', 3, 1)->nullable()->change();
            $table->decimal('appraiser_2_score', 3, 1)->nullable()->change();
            $table->decimal('average_score', 3, 1)->nullable()->change();
        });
    }
};