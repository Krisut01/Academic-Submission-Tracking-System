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
        // Update the defense_type enum to include new values
        DB::statement("ALTER TABLE panel_assignments MODIFY COLUMN defense_type ENUM('proposal_defense', 'final_defense', 'redefense', 'oral_defense', 'thesis_defense') DEFAULT 'final_defense'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE panel_assignments MODIFY COLUMN defense_type ENUM('final_defense', 'redefense') DEFAULT 'final_defense'");
    }
};
