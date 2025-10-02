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
        Schema::create('kpi_goals', function (Blueprint $table) {
            $table->id();
            
            // Foreign key linking back to the KPI
            $table->foreignId('kpi_id')->constrained()->onDelete('cascade');

            // Goal Details
            $table->string('goal', 255);
            $table->string('measurement', 255);
            $table->unsignedTinyInteger('weightage')->nullable(); // 0 to 255 is enough for percentage

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_goals');
    }
};