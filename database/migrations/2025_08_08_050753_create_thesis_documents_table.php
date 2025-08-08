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
            $table->string('student_id', 20);
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'returned_for_revision', 'under_review'])->default('pending');
            $table->json('document_metadata')->nullable(); // Store document-specific metadata
            $table->json('uploaded_files')->nullable(); // Store file paths and metadata as JSON
            $table->text('comments')->nullable(); // Faculty/Admin comments
            $table->date('submission_date');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('review_comments')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'document_type']);
            $table->index(['status', 'submission_date']);
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
