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
        Schema::create('panel_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('thesis_document_id')->constrained('thesis_documents')->onDelete('cascade');
            $table->string('thesis_title');
            $table->text('thesis_description')->nullable();
            
            // Panel Members (Multiple faculty can be assigned)
            $table->json('panel_members'); // Store array of faculty user IDs
            $table->foreignId('panel_chair_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Defense Schedule
            $table->datetime('defense_date')->nullable();
            $table->string('defense_venue')->nullable();
            $table->text('defense_instructions')->nullable();
            
            // Status and Results
            $table->enum('status', ['scheduled', 'completed', 'postponed', 'cancelled'])->default('scheduled');
            $table->enum('result', ['passed', 'failed', 'conditional', 'pending'])->nullable();
            $table->text('panel_feedback')->nullable();
            $table->decimal('final_grade', 5, 2)->nullable();
            
            // Notifications
            $table->boolean('student_notified')->default(false);
            $table->boolean('panel_notified')->default(false);
            $table->datetime('notification_sent_at')->nullable();
            
            // Audit fields
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['student_id', 'status']);
            $table->index(['defense_date']);
            $table->index(['status', 'defense_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panel_assignments');
    }
};
