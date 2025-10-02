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
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
             $table->foreignId('appraisal_id')->nullable()->constrained('appraisals')->onDelete('set null');
            $table->foreignId('staff_id')->constrained('users');
            $table->foreignId('manager_id')->constrained('users');
            $table->string('goal');
            $table->text('description');
            $table->decimal('weightage', 5, 2);
            $table->year('year');
            $table->enum('status', ['draft', 'for review', 'accepted'])->default('draft');
            $table->boolean('accepted')->default(false);
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpis');
    }
};
