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
        Schema::create('panel_assignment_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_assignment_id')->constrained('panel_assignments')->onDelete('cascade');
            $table->foreignId('thesis_document_id')->constrained('thesis_documents')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->enum('reviewer_role', ['panel_chair', 'panel_member', 'adviser'])->default('panel_member');
            $table->enum('status', ['pending', 'approved', 'rejected', 'needs_revision'])->default('pending');
            $table->text('review_comments')->nullable();
            $table->text('recommendations')->nullable();
            $table->json('review_criteria')->nullable(); // Store specific review criteria scores
            $table->boolean('can_download_files')->default(true);
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            // Ensure one review per panelist per assignment
            $table->unique(['panel_assignment_id', 'reviewer_id'], 'unique_panelist_review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panel_assignment_reviews');
    }
};
