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
        Schema::table('panel_assignments', function (Blueprint $table) {
            $table->enum('defense_type', ['proposal_defense', 'final_defense', 'redefense', 'oral_defense', 'thesis_defense'])->default('final_defense')->after('defense_instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panel_assignments', function (Blueprint $table) {
            $table->dropColumn('defense_type');
        });
    }
};
