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
            // Add individual weightage columns for each of the 5 goals
            $table->decimal('weightage_1', 5, 2)->nullable()->after('key_goal_1');
            $table->decimal('weightage_2', 5, 2)->nullable()->after('key_goal_2');
            $table->decimal('weightage_3', 5, 2)->nullable()->after('key_goal_3');
            $table->decimal('weightage_4', 5, 2)->nullable()->after('key_goal_4');
            $table->decimal('weightage_5', 5, 2)->nullable()->after('key_goal_5');
            $table->renameColumn('weightage', 'total_weightage');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kpis', function (Blueprint $table) {
            // Drop the new columns
            $table->dropColumn(['weightage_1', 'weightage_2', 'weightage_3', 'weightage_4', 'weightage_5']);
            $table->renameColumn('total_weightage', 'weightage');
        });
    }
};