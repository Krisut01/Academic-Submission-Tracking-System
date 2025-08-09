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
        Schema::create('thesis_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', ['proposal', 'approval_sheet', 'panel_assignment', 'final_manuscript']);
            
            // Basic Student Information
            $table->string('student_id', 20);
            $table->string('full_name'); // Added for explicit storage
            $table->string('course_program'); // Added for course/program
            $table->string('title');
            $table->text('description')->nullable();
            
            // Document-specific fields
            $table->text('abstract')->nullable(); // For proposals
            $table->string('research_area')->nullable(); // Research area/specialization
            $table->string('adviser_name')->nullable(); // Adviser name (dropdown or text)
            $table->foreignId('adviser_id')->nullable()->constrained('users')->onDelete('set null'); // Link to adviser user
            
            // Panel and approval related
            $table->json('panel_members')->nullable(); // Array of panel member names/IDs
            $table->date('approval_date')->nullable(); // Date of approval
            $table->date('defense_date')->nullable(); // Defense date
            $table->string('defense_type')->nullable(); // Proposal/Final Defense
            $table->text('defense_venue')->nullable(); // Defense venue
            $table->text('requested_schedule')->nullable(); // For panel assignment requests
            
            // Final manuscript specific
            $table->boolean('final_revisions_completed')->default(false); // Yes/No toggle
            $table->boolean('has_plagiarism_report')->default(false); // Has Turnitin report
            $table->decimal('plagiarism_percentage', 5, 2)->nullable(); // Plagiarism percentage
            
            // File and metadata storage
            $table->json('document_metadata')->nullable(); // Store document-specific metadata
            $table->json('uploaded_files')->nullable(); // Store file paths and metadata as JSON
            
            // Status and review information
            $table->enum('status', ['pending', 'approved', 'returned_for_revision', 'under_review'])->default('pending');
            $table->text('comments')->nullable(); // Student comments
            $table->text('remarks')->nullable(); // Student remarks/notes
            $table->date('submission_date');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('review_comments')->nullable(); // Faculty/Admin comments
            
            // Additional tracking
            $table->string('file_naming_prefix')->nullable(); // Auto-generated naming prefix
            $table->integer('version_number')->default(1); // Version tracking
            $table->json('status_history')->nullable(); // Track status changes
            
            $table->timestamps();

            $table->index(['user_id', 'document_type']);
            $table->index(['status', 'submission_date']);
            $table->index(['adviser_id', 'status']);
            $table->index(['student_id', 'document_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thesis_documents');
    }
};
