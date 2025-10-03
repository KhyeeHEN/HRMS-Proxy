<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appraisals', function (Blueprint $table) {
            // Signature Flags (1 if signed)
            $table->boolean('appraisee_signed')->default(false)->after('appraisee_comments');
            $table->boolean('appraiser1_signed')->default(false)->after('appraiser_1_comments');
            $table->boolean('appraiser2_signed')->default(false)->after('appraiser_2_comments');

            // Submission/Lock Flags
            $table->boolean('appraisee_submitted')->default(false)->after('appraisee_signed');
            $table->boolean('appraiser1_submitted')->default(false)->after('appraiser1_signed');
            $table->boolean('appraiser2_submitted')->default(false)->after('appraiser2_signed');
        });
    }

    public function down(): void
    {
        Schema::table('appraisals', function (Blueprint $table) {
            $table->dropColumn([
                'appraisee_signed',
                'appraiser1_signed',
                'appraiser2_signed',
                'appraisee_submitted',
                'appraiser1_submitted',
                'appraiser2_submitted',
            ]);
        });
    }
};