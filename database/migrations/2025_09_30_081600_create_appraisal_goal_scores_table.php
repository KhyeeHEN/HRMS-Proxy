<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appraisal_goal_scores', function (Blueprint $table) {
            $table->id();
            
            // Link to the main appraisal
            $table->foreignId('appraisal_id')->constrained('appraisals')->onDelete('cascade');
            // Link to the specific KPI goal (assuming your goals are in a 'kpi_goals' table)
            $table->foreignId('kpi_goal_id')->constrained('kpi_goals')->onDelete('cascade'); 
            
            // The three user input scores
            $table->decimal('staff_score', 3, 1)->nullable()->comment('Score input by staff member (1.0 to 5.0)');
            $table->decimal('appraiser_1_score', 3, 1)->nullable()->comment('Score input by Appraiser 1 (1.0 to 5.0)');
            $table->decimal('appraiser_2_score', 3, 1)->nullable()->comment('Score input by Appraiser 2 (1.0 to 5.0)');
            
            // Calculated fields
            $table->decimal('average_score', 3, 1)->nullable()->comment('Average of Appraiser 1 and Appraiser 2 scores');
            $table->decimal('weighted_score', 5, 2)->nullable()->comment('Average Score * (Weightage / 100)');

            $table->unique(['appraisal_id', 'kpi_goal_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appraisal_goal_scores');
    }
};