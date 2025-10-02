<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change the column to include the new 'template' option and remove 'unassigned'
        DB::statement("ALTER TABLE kpis MODIFY COLUMN `status` ENUM('draft', 'for review', 'template', 'accepted', 'declined', 'archived') NOT NULL DEFAULT 'template'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the column to its original state if the migration is rolled back
        DB::statement("ALTER TABLE kpis MODIFY COLUMN `status` ENUM('draft', 'for review', 'accepted', 'declined') NOT NULL DEFAULT 'for review'");
    }
};