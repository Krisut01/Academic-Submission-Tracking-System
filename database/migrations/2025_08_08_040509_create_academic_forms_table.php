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
        Schema::create('academic_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('form_type', ['registration', 'clearance', 'evaluation']);
            $table->string('student_id', 20);
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'under_review'])->default('pending');
            $table->json('form_data')->nullable(); // Store form fields as JSON
            $table->json('uploaded_files')->nullable(); // Store file paths as JSON
            $table->text('remarks')->nullable();
            $table->date('submission_date');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('review_comments')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'form_type']);
            $table->index(['status', 'submission_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_forms');
    }
};
