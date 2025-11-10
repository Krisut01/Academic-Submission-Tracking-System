<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AcademicForm;

class FixAcademicFormDescriptions extends Command
{
    protected $signature = 'fix:form-descriptions';
    protected $description = 'Fix academic form descriptions that contain invalid date data';

    public function handle()
    {
        $this->info('Checking academic forms for invalid titles and descriptions...');

        $forms = AcademicForm::all();
        $fixed = 0;

        foreach ($forms as $form) {
            $needsFix = false;
            
            // Check if title contains invalid date/number data
            if ($form->title && (
                preg_match('/-?\d+\.\d+\s*days?\s*(away)?/', $form->title) ||
                preg_match('/Description.*-?\d+\.\d+/', $form->title)
            )) {
                $this->warn("Found invalid title in form ID {$form->id}: " . substr($form->title, 0, 100));
                
                // Replace with proper title based on form type
                $form->title = match($form->form_type) {
                    'registration' => 'Course Registration Form',
                    'clearance' => 'Clearance Form',
                    'evaluation' => 'Evaluation Form',
                    default => 'Academic Form'
                };
                
                $needsFix = true;
            }
            
            // Check if description contains invalid date/number data
            if ($form->description && (
                preg_match('/-?\d+\.\d+\s*days?\s*(away)?/', $form->description) ||
                preg_match('/Description.*-?\d+\.\d+/', $form->description) ||
                is_numeric(trim($form->description))
            )) {
                $this->warn("Found invalid description in form ID {$form->id}: " . substr($form->description, 0, 100));
                
                // Replace with proper description based on form type
                $form->description = match($form->form_type) {
                    'registration' => 'Course registration form for enrollment and subject selection.',
                    'clearance' => 'Clearance form for graduation requirements and final obligations.',
                    'evaluation' => 'Evaluation form for academic progress and thesis status assessment.',
                    default => 'Academic form submission'
                };
                
                $needsFix = true;
            }
            
            if ($needsFix) {
                $form->save();
                $fixed++;
                $this->info("✓ Fixed form ID {$form->id}");
            }
        }

        $this->info("\nTotal forms checked: {$forms->count()}");
        $this->info("Total forms fixed: {$fixed}");
        
        return 0;
    }
}

