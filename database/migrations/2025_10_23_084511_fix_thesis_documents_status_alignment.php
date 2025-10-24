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
        Schema::table('thesis_documents', function (Blueprint $table) {
            // Add back the status column that the code expects
            $table->enum('status', ['pending', 'approved', 'returned_for_revision', 'under_review'])
                  ->default('pending')
                  ->after('uploaded_files');
        });
        
        // Copy data from overall_status to status for backward compatibility
        DB::statement("UPDATE thesis_documents SET status = overall_status WHERE overall_status IS NOT NULL");
        
        // Set default status for any null values
        DB::statement("UPDATE thesis_documents SET status = 'pending' WHERE status IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis_documents', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};