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
            $table->timestamp('completed_at')->nullable()->after('status');
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('set null')->after('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panel_assignments', function (Blueprint $table) {
            $table->dropForeign(['completed_by']);
            $table->dropColumn(['completed_at', 'completed_by']);
        });
    }
};