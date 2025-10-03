<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Define the available enum options
        $potentialEnums = ['High', 'Low', 'Not Ready'];
        
        Schema::table('appraisals', function (Blueprint $table) use ($potentialEnums) {
            
            // 1. Drop the old ambiguous column (if it exists)
            $table->dropColumn('promotion_potential'); // Uncomment if you are sure you want to drop the old one

            // 2. Add the three new assessment columns
            $table->enum('promotion_potential_now', $potentialEnums)->nullable()->after('major_achievements_comment');
            $table->enum('promotion_potential_1_2_years', $potentialEnums)->nullable()->after('promotion_potential_now');
            $table->enum('promotion_potential_after_2_years', $potentialEnums)->nullable()->after('promotion_potential_1_2_years');

            // NOTE: Assuming your comment columns already exist:
            // 'promotion_now_comment', 'promotion_1_2_years_comment', 'promotion_after_2_years_comment'
        });
    }

    public function down(): void
    {
        // Define the available enum options for safety
        $potentialEnums = ['High', 'Low', 'Not Ready'];

        Schema::table('appraisals', function (Blueprint $table) use ($potentialEnums) {
            // Drop the new columns
            $table->dropColumn([
                'promotion_potential_now',
                'promotion_potential_1_2_years',
                'promotion_potential_after_2_years',
            ]);
            
            // If you dropped the old column in 'up', you may want to re-add a placeholder here
            $table->enum('promotion_potential', $potentialEnums)->nullable();
        });
    }
};