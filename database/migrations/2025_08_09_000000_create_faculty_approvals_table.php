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
        Schema::create('faculty_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_document_id')->constrained()->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained('users')->onDelete('cascade');
            $table->enum('approval_status', ['pending', 'approved', 'returned_for_revision', 'under_review'])->default('pending');
            $table->text('approval_comments')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('faculty_role')->nullable(); // adviser, reviewer, panel_member, panel_chair, secretary
            $table->json('approval_metadata')->nullable(); // Store additional approval data
            $table->timestamps();
            
            // Ensure one approval per faculty per document
            $table->unique(['thesis_document_id', 'faculty_id']);
            
            // Indexes for performance
            $table->index(['thesis_document_id', 'approval_status']);
            $table->index(['faculty_id', 'approval_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_approvals');
    }
};

