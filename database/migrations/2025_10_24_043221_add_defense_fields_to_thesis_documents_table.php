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
            // Add missing defense-related columns
            $table->decimal('defense_grade', 5, 2)->nullable();
            $table->foreignId('defense_confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('defense_confirmed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis_documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('defense_confirmed_by');
            $table->dropColumn(['defense_grade', 'defense_confirmed_at']);
        });
    }
};