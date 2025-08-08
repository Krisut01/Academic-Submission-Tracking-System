<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AcademicFormController extends Controller
{
    /**
     * Check if the authenticated user is a student
     */
    private function ensureUserIsStudent()
    {
        if (Auth::user()->role !== 'student') {
            abort(403, 'Unauthorized access. Only students can access this resource.');
        }
    }

    /**
     * Display the form submission page
     */
    public function index()
    {
        $this->ensureUserIsStudent();
        return view('student.forms.index');
    }

    /**
     * Show the form creation page
     */
    public function create(Request $request)
    {
        $this->ensureUserIsStudent();
        $formType = $request->get('type', 'registration');
        
        // Validate form type
        if (!in_array($formType, ['registration', 'clearance', 'evaluation'])) {
            return redirect()->route('student.forms.index')
                ->with('error', 'Invalid form type selected.');
        }

        return view('student.forms.create', compact('formType'));
    }

    /**
     * Store the submitted form
     */
    public function store(Request $request)
    {
        $this->ensureUserIsStudent();
        $request->validate([
            'form_type' => ['required', Rule::in(['registration', 'clearance', 'evaluation'])],
            'student_id' => 'required|string|max:20',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'remarks' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        // Handle file uploads
        $uploadedFiles = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('academic-forms/' . Auth::id(), 'public');
                $uploadedFiles[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientMimeType(),
                ];
            }
        }

        // Collect form-specific data
        $formData = $this->collectFormData($request);

        // Create the academic form
        $academicForm = AcademicForm::create([
            'user_id' => Auth::id(),
            'form_type' => $request->form_type,
            'student_id' => $request->student_id,
            'title' => $request->title,
            'description' => $request->description,
            'form_data' => $formData,
            'uploaded_files' => $uploadedFiles,
            'remarks' => $request->remarks,
            'submission_date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        // Fire event for cross-system updates
        event(new \App\Events\FormSubmitted($academicForm, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'submission_method' => 'web_form',
            'file_count' => count($uploadedFiles),
        ]));

        return redirect()->route('student.forms.history')
            ->with('success', 'Form submitted successfully! Your submission is now under review.');
    }

    /**
     * Display submission history
     */
    public function history()
    {
        $this->ensureUserIsStudent();
        $forms = AcademicForm::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.forms.history', compact('forms'));
    }

    /**
     * Show a specific form submission
     */
    public function show(AcademicForm $form)
    {
        $this->ensureUserIsStudent();
        // Ensure the form belongs to the authenticated user
        if ($form->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this form.');
        }

        return view('student.forms.show', compact('form'));
    }

    /**
     * Download uploaded file
     */
    public function downloadFile(AcademicForm $form, $fileIndex)
    {
        $this->ensureUserIsStudent();
        // Ensure the form belongs to the authenticated user
        if ($form->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this file.');
        }

        $files = $form->uploaded_files ?? [];
        
        if (!isset($files[$fileIndex])) {
            abort(404, 'File not found.');
        }

        $file = $files[$fileIndex];
        
        if (!Storage::disk('public')->exists($file['path'])) {
            abort(404, 'File not found on server.');
        }

        return response()->download(storage_path('app/public/' . $file['path']), $file['original_name']);
    }

    /**
     * Collect form-specific data based on form type
     */
    private function collectFormData(Request $request): array
    {
        $baseData = [
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'program' => $request->program,
            'year_level' => $request->year_level,
        ];

        switch ($request->form_type) {
            case 'registration':
                return array_merge($baseData, [
                    'subjects' => $request->subjects ?? [],
                    'units_total' => $request->units_total,
                    'previous_gpa' => $request->previous_gpa,
                    'scholarship_status' => $request->scholarship_status,
                ]);

            case 'clearance':
                return array_merge($baseData, [
                    'clearance_type' => $request->clearance_type,
                    'reason' => $request->reason,
                    'expected_graduation' => $request->expected_graduation,
                    'outstanding_obligations' => $request->outstanding_obligations ?? [],
                ]);

            case 'evaluation':
                return array_merge($baseData, [
                    'evaluation_type' => $request->evaluation_type,
                    'completed_subjects' => $request->completed_subjects ?? [],
                    'grades' => $request->grades ?? [],
                    'thesis_status' => $request->thesis_status,
                    'special_requirements' => $request->special_requirements ?? [],
                ]);

            default:
                return $baseData;
        }
    }
}
