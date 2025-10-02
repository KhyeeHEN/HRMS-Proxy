<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_goal_trackings', function (Blueprint $table) {
            $table->id();

            // Link to the specific goal being tracked.
            $table->foreignId('kpi_goal_id')->constrained('kpi_goals')->onDelete('cascade');

            // The ID of the KPI owner (staff)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // The staff member's persistent entry
            $table->text('achievement')->nullable();

            // The manager's persistent assessment/feedback
            $table->text('manager_comment')->nullable();

            // NOTE: tracking_date column is REMOVED

            $table->timestamps();

            // Ensure only ONE persistent record per goal per staff member
            $table->unique(['kpi_goal_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_goal_trackings');
    }
};
