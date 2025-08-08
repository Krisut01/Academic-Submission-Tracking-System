<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ThesisDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ThesisDocumentController extends Controller
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
     * Display the thesis documents main page
     */
    public function index()
    {
        $this->ensureUserIsStudent();
        return view('student.thesis.index');
    }

    /**
     * Show the document creation page
     */
    public function create(Request $request)
    {
        $this->ensureUserIsStudent();
        $documentType = $request->get('type', 'proposal');

        // Validate document type
        if (!in_array($documentType, ['proposal', 'approval_sheet', 'panel_assignment', 'final_manuscript'])) {
            return redirect()->route('student.thesis.index')
                ->with('error', 'Invalid document type selected.');
        }

        return view('student.thesis.create', compact('documentType'));
    }

    /**
     * Store the submitted document
     */
    public function store(Request $request)
    {
        $this->ensureUserIsStudent();
        $request->validate([
            'document_type' => ['required', Rule::in(['proposal', 'approval_sheet', 'panel_assignment', 'final_manuscript'])],
            'student_id' => 'required|string|max:20',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'comments' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        // Handle file uploads
        $uploadedFiles = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('thesis-documents/' . Auth::id(), 'public');
                $uploadedFiles[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientMimeType(),
                ];
            }
        }

        // Collect document-specific metadata
        $documentMetadata = $this->collectDocumentMetadata($request);

        // Create the thesis document
        $thesisDocument = ThesisDocument::create([
            'user_id' => Auth::id(),
            'document_type' => $request->document_type,
            'student_id' => $request->student_id,
            'title' => $request->title,
            'description' => $request->description,
            'document_metadata' => $documentMetadata,
            'uploaded_files' => $uploadedFiles,
            'comments' => $request->comments,
            'submission_date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        return redirect()->route('student.thesis.history')
            ->with('success', 'Thesis document submitted successfully! Your submission is now under review.');
    }

    /**
     * Display submission history
     */
    public function history(Request $request)
    {
        $this->ensureUserIsStudent();
        
        $query = ThesisDocument::where('user_id', Auth::id());

        // Apply filters
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('submission_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('submission_date', '<=', $request->date_to);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('student.thesis.history', compact('documents'));
    }

    /**
     * Show a specific document submission
     */
    public function show(ThesisDocument $document)
    {
        $this->ensureUserIsStudent();
        // Ensure the document belongs to the authenticated user
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this document.');
        }

        return view('student.thesis.show', compact('document'));
    }

    /**
     * Download uploaded file
     */
    public function downloadFile(ThesisDocument $document, $fileIndex)
    {
        $this->ensureUserIsStudent();
        // Ensure the document belongs to the authenticated user
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this file.');
        }

        $files = $document->uploaded_files ?? [];

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
     * Collect document-specific metadata based on document type
     */
    private function collectDocumentMetadata(Request $request): array
    {
        $baseData = [
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'program' => $request->program,
            'adviser' => $request->adviser,
        ];

        switch ($request->document_type) {
            case 'proposal':
                return array_merge($baseData, [
                    'research_title' => $request->research_title,
                    'research_area' => $request->research_area,
                    'methodology' => $request->methodology,
                    'keywords' => $request->keywords ?? [],
                    'expected_completion' => $request->expected_completion,
                ]);

            case 'approval_sheet':
                return array_merge($baseData, [
                    'panel_members' => $request->panel_members ?? [],
                    'defense_date' => $request->defense_date,
                    'venue' => $request->venue,
                    'approval_type' => $request->approval_type,
                ]);

            case 'panel_assignment':
                return array_merge($baseData, [
                    'panel_chair' => $request->panel_chair,
                    'panel_members' => $request->panel_members ?? [],
                    'assignment_date' => $request->assignment_date,
                    'specialization_area' => $request->specialization_area,
                ]);

            case 'final_manuscript':
                return array_merge($baseData, [
                    'total_pages' => $request->total_pages,
                    'chapters_completed' => $request->chapters_completed ?? [],
                    'plagiarism_check' => $request->plagiarism_check,
                    'similarity_percentage' => $request->similarity_percentage,
                    'final_grade' => $request->final_grade,
                ]);

            default:
                return $baseData;
        }
    }
}
