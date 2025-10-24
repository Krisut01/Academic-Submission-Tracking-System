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
        Schema::table('thesis_documents', function (Blueprint $table) {
            // Add preferred schedule fields for panel assignment requests
            $table->json('preferred_dates')->nullable()->after('requested_schedule');
            $table->string('preferred_time')->nullable()->after('preferred_dates');
            $table->string('preferred_venue')->nullable()->after('preferred_time');
            $table->text('special_requirements')->nullable()->after('preferred_venue');
            $table->text('required_specializations')->nullable()->after('special_requirements');
            $table->text('panel_justification')->nullable()->after('required_specializations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis_documents', function (Blueprint $table) {
            $table->dropColumn([
                'preferred_dates',
                'preferred_time',
                'preferred_venue',
                'special_requirements',
                'required_specializations',
                'panel_justification'
            ]);
        });
    }
};