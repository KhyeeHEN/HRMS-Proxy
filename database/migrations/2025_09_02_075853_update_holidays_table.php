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
        Schema::table('holidays', function (Blueprint $table) {
                        $table->tinyInteger('month')->after('name');
            $table->tinyInteger('day')->after('month');
            $table->dropColumn('dateh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('holidays', function (Blueprint $table) {
               $table->date('dateh')->after('name');
            $table->dropColumn(['month', 'day']);
        });
    }
};
