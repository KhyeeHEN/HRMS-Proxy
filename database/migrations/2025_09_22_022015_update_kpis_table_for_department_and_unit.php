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
            // Remove the old foreign keys and columns
            $table->dropForeign(['staff_id']);
            $table->dropColumn('staff_id');

            // Add new columns
            $table->unsignedBigInteger('department_id')->after('id')->nullable();
            $table->string('unit')->after('department_id')->nullable();
            $table->unsignedBigInteger('assigned_to_staff_id')->nullable()->after('total_weightage');

            // Add foreign key constraint for the new assignment column
            $table->foreign('assigned_to_staff_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kpis', function (Blueprint $table) {
            // Re-add the original columns for rollback
            $table->unsignedBigInteger('staff_id')->after('id')->nullable();
            $table->foreign('staff_id')->references('id')->on('users');

            // Drop the new columns
            $table->dropColumn('department_id');
            $table->dropColumn('unit');
            $table->dropColumn('assigned_to_staff_id');
            $table->dropForeign(['assigned_to_staff_id']);
        });
    }
};
