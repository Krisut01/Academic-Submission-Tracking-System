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
            // Separate academic and administrative approval
            $table->enum('academic_status', ['pending', 'approved', 'returned_for_revision'])->default('pending')->after('status');
            $table->foreignId('academic_approved_by')->nullable()->constrained('users')->onDelete('set null')->after('reviewed_by');
            $table->timestamp('academic_approved_at')->nullable()->after('reviewed_at');
            
            // Administrative approval (for panel assignments)
            $table->enum('admin_status', ['pending', 'approved', 'scheduled'])->nullable()->after('academic_status');
            $table->foreignId('admin_approved_by')->nullable()->constrained('users')->onDelete('set null')->after('academic_approved_by');
            $table->timestamp('admin_approved_at')->nullable()->after('academic_approved_at');
            
            // Keep original status for backward compatibility but rename for clarity
            $table->renameColumn('status', 'overall_status');
        });
        
        // Update panel assignments to include defense results
        Schema::table('panel_assignments', function (Blueprint $table) {
            // Defense evaluation fields
            $table->enum('defense_result', ['passed', 'failed', 'conditional', 'pending'])->default('pending')->after('result');
            $table->decimal('defense_grade', 5, 2)->nullable()->after('final_grade');
            $table->json('individual_evaluations')->nullable()->after('panel_feedback'); // Store each panel member's evaluation
            
            // Rename existing result to status for clarity
            $table->renameColumn('result', 'legacy_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis_documents', function (Blueprint $table) {
            $table->dropColumn([
                'academic_status',
                'academic_approved_by',
                'academic_approved_at',
                'admin_status', 
                'admin_approved_by',
                'admin_approved_at'
            ]);
            $table->renameColumn('overall_status', 'status');
        });
        
        Schema::table('panel_assignments', function (Blueprint $table) {
            $table->dropColumn([
                'defense_result',
                'defense_grade',
                'individual_evaluations'
            ]);
            $table->renameColumn('legacy_result', 'result');
        });
    }
};