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
        Schema::create('company_assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_id')->nullable();
            $table->string('pc_name')->nullable();
            $table->string('user')->nullable();
            $table->string('department')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->string('model')->nullable();
            $table->string('cpu')->nullable();
            $table->string('ram')->nullable();
            $table->string('hdd')->nullable();
            $table->string('ssd')->nullable();
            $table->string('os')->nullable();
            $table->string('os_key')->nullable();
            $table->string('office')->nullable();
            $table->string('office_key')->nullable();
            $table->string('office_login')->nullable();
            $table->string('antivirus')->nullable();
            $table->string('synology')->nullable();
            $table->year('dop')->nullable();
            $table->string('warranty_end')->nullable();
            $table->text('remarks')->nullable();
            $table->bigInteger('employee_id')->nullable();
            $table->timestamps();
    
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_assets');
    }
};
