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
        Schema::table('kpis', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn('goal');
            $table->dropColumn('description');

            // Add new atomic columns
            for ($i = 1; $i <= 5; $i++) {
                $table->string('key_goal_' . $i)->nullable();
            }

            for ($i = 1; $i <= 5; $i++) {
                $table->string('indicator_measurement_' . $i)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kpis', function (Blueprint $table) {
            // Revert to the original columns
            for ($i = 1; $i <= 5; $i++) {
                $table->dropColumn('key_goal_' . $i);
                $table->dropColumn('indicator_measurement_' . $i);
            }

            $table->string('goal')->nullable();
            $table->text('description')->nullable();
        });
    }
};