<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\ThesisDocument;
use App\Models\PanelAssignment;

class FixFacultyAssignments extends Command
{
    protected $signature = 'fix:faculty-assignments';
    protected $description = 'Fix faculty document assignments';

    public function handle()
    {
        $this->info('Checking and fixing faculty assignments...');
        
        // Get all faculty
        $faculty = User::where('role', 'faculty')->get();
        $this->info("Found {$faculty->count()} faculty members");
        
        // Get all documents
        $documents = ThesisDocument::all();
        $this->info("Found {$documents->count()} documents");
        
        // Check each document
        foreach ($documents as $document) {
            $this->line("Document {$document->id}: {$document->title}");
            $this->line("  - Adviser ID: {$document->adviser_id}");
            $this->line("  - Reviewed by: {$document->reviewed_by}");
            $this->line("  - Status: {$document->status}");
            
            // If no adviser assigned, assign the first faculty member
            if (!$document->adviser_id) {
                $firstFaculty = $faculty->first();
                if ($firstFaculty) {
                    $document->update(['adviser_id' => $firstFaculty->id]);
                    $this->info("  ✓ Assigned adviser: {$firstFaculty->name}");
                }
            }
            
            // If no reviewer assigned and document is pending, assign a faculty member
            if (!$document->reviewed_by && $document->status === 'pending') {
                $availableFaculty = $faculty->where('id', '!=', $document->adviser_id)->first();
                if ($availableFaculty) {
                    $document->update(['reviewed_by' => $availableFaculty->id]);
                    $this->info("  ✓ Assigned reviewer: {$availableFaculty->name}");
                }
            }
        }
        
        // Create a test panel assignment
        $student = User::where('role', 'student')->first();
        $facultyIds = $faculty->pluck('id')->toArray();
        
        if ($student && count($facultyIds) >= 3) {
            $panelAssignment = PanelAssignment::create([
                'student_id' => $student->id,
                'thesis_document_id' => $documents->first()->id,
                'thesis_title' => $documents->first()->title,
                'thesis_description' => 'Test panel assignment',
                'panel_members' => array_slice($facultyIds, 0, 3),
                'panel_chair_id' => $facultyIds[0],
                'secretary_id' => $facultyIds[1],
                'defense_date' => now()->addDays(7),
                'defense_venue' => 'Room 101',
                'defense_instructions' => 'Test defense',
                'defense_type' => 'proposal_defense',
                'status' => 'scheduled',
                'created_by' => 1,
            ]);
            
            $this->info("✓ Created test panel assignment for {$student->name}");
        }
        
        $this->info('Faculty assignments fixed!');
    }
}
