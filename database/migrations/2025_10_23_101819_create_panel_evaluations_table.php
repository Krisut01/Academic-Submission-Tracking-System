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
        Schema::create('panel_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_assignment_id')->constrained('panel_assignments')->onDelete('cascade');
            $table->foreignId('thesis_document_id')->constrained('thesis_documents')->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->enum('evaluator_role', ['panel_chair', 'panel_member', 'adviser'])->default('panel_member');
            $table->enum('overall_status', ['pending', 'approved', 'rejected', 'conditional'])->default('pending');
            $table->json('evaluation_criteria')->nullable(); // Store detailed evaluation scores
            $table->text('evaluation_summary')->nullable();
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('recommendations')->nullable();
            $table->decimal('overall_score', 5, 2)->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();
            
            // Ensure one evaluation per evaluator per assignment
            $table->unique(['panel_assignment_id', 'evaluator_id'], 'unique_panelist_evaluation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panel_evaluations');
    }
};
