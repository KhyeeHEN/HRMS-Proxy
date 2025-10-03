<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- Ensure DB is imported

return new class extends Migration
{
    public function up(): void
    {
        // 1. Temporarily disable foreign key checks (optional but good practice)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. DROP the foreign key constraint from the 'kpis' table FIRST.
        if (Schema::hasColumn('kpis', 'appraisal_id')) {
            Schema::table('kpis', function (Blueprint $table) {
                // Use the exact foreign key name from the error message.
                $table->dropForeign(['appraisal_id']); 
                // OR use the explicit name if you know it, as given in the error:
                // $table->dropForeign('kpis_appraisal_id_foreign'); 
            });
        }

        // 3. Drop the old appraisals table
        Schema::dropIfExists('appraisals');

        // 4. Recreate the appraisals table with the new, comprehensive schema
        Schema::create('appraisals', function (Blueprint $table) {
            $table->id();
            
            // Core Links & Metadata
            $table->foreignId('appraisee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appraiser_1_id')->constrained('users')->onDelete('cascade')->comment('The primary manager/appraiser');
            $table->foreignId('appraiser_2_id')->nullable()->constrained('users')->onDelete('set null')->comment('The secondary manager/appraiser (e.g., Department Head)');

            $table->enum('status', ['draft', 'in progress', 'submitted', 'finalized', 'acknowledged'])->default('draft');
            $table->integer('year'); 
            $table->date('review_period_start')->nullable();
            $table->date('review_period_end')->nullable();

            // --- SCORING FIELDS ---
            $table->text('kpi_goal_comments')->nullable();
            $table->decimal('section_1_score', 5, 2)->nullable()->comment('Final weighted score for KPI objectives');
            $table->text('org_core_competency_comments')->nullable();
            $table->decimal('section_2a_score', 5, 2)->nullable()->comment('Final score for Organizational Core Competencies');
            $table->text('job_family_competency_comments')->nullable();
            $table->decimal('section_2b_score', 5, 2)->nullable()->comment('Final score for Job Family Competencies');
            $table->decimal('section_3_overall_score', 5, 2)->nullable()->comment('Final score combined from Sections 1, 2a, and 2b');
            
            // --- OTHER SECTIONS ---
            $table->text('special_projects_comment')->nullable();
            $table->text('major_achievements_comment')->nullable();
            $table->enum('promotion_potential', ['High', 'Low', 'Not Ready'])->nullable();
            $table->string('promotion_now_comment')->nullable();
            $table->string('promotion_1_2_years_comment')->nullable();
            $table->string('promotion_after_2_years_comment')->nullable();
            $table->text('personal_growth_comment')->nullable();

            // --- Comments & Acknowledgement ---
            $table->text('appraisee_comments')->nullable();
            $table->timestamp('appraisee_signed_at')->nullable();
            $table->text('appraiser_1_comments')->nullable();
            $table->timestamp('appraiser_1_signed_at')->nullable();
            $table->text('appraiser_2_comments')->nullable();
            $table->timestamp('appraiser_2_signed_at')->nullable();
            
            $table->timestamps();
        });

        // 5. RE-ADD the foreign key constraint to the 'kpis' table.
        if (Schema::hasColumn('kpis', 'appraisal_id')) {
            Schema::table('kpis', function (Blueprint $table) {
                // Ensure the 'appraisal_id' column exists before adding the constraint.
                // Assuming it was already present and nullable:
                $table->foreign('appraisal_id')->references('id')->on('appraisals')->onDelete('set null');
            });
        }
        
        // 6. Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    // The down method remains the same, but it should also drop the FK first for a clean rollback
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if (Schema::hasColumn('kpis', 'appraisal_id')) {
            Schema::table('kpis', function (Blueprint $table) {
                // Drop the foreign key constraint
                $table->dropForeign(['appraisal_id']);
            });
        }

        Schema::dropIfExists('appraisals');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};