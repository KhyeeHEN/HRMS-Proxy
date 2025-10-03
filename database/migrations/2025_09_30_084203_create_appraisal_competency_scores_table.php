<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appraisal_competency_scores', function (Blueprint $table) {
            $table->id();
            
            // Links
            $table->foreignId('appraisal_id')->constrained('appraisals')->onDelete('cascade');
            
            // Attribute Identification
            $table->enum('section_type', ['2a', '2b'])->comment('Organizational Core (2a) or Job Family (2b)');
            $table->string('attribute_key', 50)->comment('The key identifier for the competency (e.g., quality_focused)');
            
            // Appraiser Input Scores (0-10 scale)
            $table->decimal('staff_score', 3, 1)->nullable();
            $table->decimal('appraiser_1_score', 3, 1)->nullable();
            $table->decimal('appraiser_2_score', 3, 1)->nullable();
            
            // Calculated Fields
            $table->decimal('average_score', 3, 1)->nullable(); // (A1 + A2) / 2
            $table->decimal('weighted_score', 5, 2)->nullable(); // Average * 0.2
            
            $table->unique(['appraisal_id', 'attribute_key']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appraisal_competency_scores');
    }
};
