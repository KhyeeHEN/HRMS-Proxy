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
            $table->unsignedBigInteger('kpi_id_from_template')->nullable()->after('manager_id');
            $table->foreign('kpi_id_from_template')->references('id')->on('kpis')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kpis', function (Blueprint $table) {
            $table->dropForeign(['kpi_id_from_template']);
            $table->dropColumn('kpi_id_from_template');
        });
    }
};