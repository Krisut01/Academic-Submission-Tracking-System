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
            $table->foreignId('secretary_id')->nullable()->constrained('users')->onDelete('set null')->after('panel_chair_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panel_assignments', function (Blueprint $table) {
            $table->dropForeign(['secretary_id']);
            $table->dropColumn('secretary_id');
        });
    }
};