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
        Schema::table('kpis', callback: function (Blueprint $table) {
            // Drop key goal columns (1 to 5)
            $table->dropColumn(['key_goal_1', 'key_goal_2', 'key_goal_3', 'key_goal_4', 'key_goal_5']);

            // Drop indicator measurement columns (1 to 5)
            $table->dropColumn(['indicator_measurement_1', 'indicator_measurement_2', 'indicator_measurement_3', 'indicator_measurement_4', 'indicator_measurement_5']);
            
            // Drop weightage columns (1 to 5)
            $table->dropColumn(['weightage_1', 'weightage_2', 'weightage_3', 'weightage_4', 'weightage_5']);
        });
    }

    /**
     * Reverse the migrations (optional but recommended for rollbacks).
     */
    public function down(): void
    {
        Schema::table('kpis', function (Blueprint $table) {
            // Re-add key goal columns
            $table->string('key_goal_1', 255)->nullable();
            $table->string('key_goal_2', 255)->nullable();
            $table->string('key_goal_3', 255)->nullable();
            $table->string('key_goal_4', 255)->nullable();
            $table->string('key_goal_5', 255)->nullable();

            // Re-add indicator measurement columns
            $table->string('indicator_measurement_1', 255)->nullable();
            $table->string('indicator_measurement_2', 255)->nullable();
            $table->string('indicator_measurement_3', 255)->nullable();
            $table->string('indicator_measurement_4', 255)->nullable();
            $table->string('indicator_measurement_5', 255)->nullable();
            
            // Re-add weightage columns
            $table->unsignedTinyInteger('weightage_1')->nullable();
            $table->unsignedTinyInteger('weightage_2')->nullable();
            $table->unsignedTinyInteger('weightage_3')->nullable();
            $table->unsignedTinyInteger('weightage_4')->nullable();
            $table->unsignedTinyInteger('weightage_5')->nullable();
        });
    }
};