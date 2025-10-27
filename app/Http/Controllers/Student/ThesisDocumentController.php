<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ThesisDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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
     * Display the student's defense schedule
     */
    public function defense(Request $request)
    {
        $this->ensureUserIsStudent();
        
        $user = Auth::user();
        
        
        // Check if specific assignment ID is provided in query parameter
        if ($request->has('assignment')) {
            $panelAssignment = \App\Models\PanelAssignment::where('id', $request->assignment)
                ->where('student_id', $user->id)
                ->with(['panelChair', 'secretary', 'thesisDocument'])
                ->first();
                
            if (!$panelAssignment) {
                abort(404, 'Panel assignment not found or you do not have access to it.');
            }
        } else {
            // Get the student's panel assignment (defense schedule)
            $panelAssignment = \App\Models\PanelAssignment::where('student_id', $user->id)
                ->with(['panelChair', 'secretary', 'thesisDocument'])
                ->first();
        }
            
        // Get panel members details
        $panelMembers = collect();
        if ($panelAssignment && !empty($panelAssignment->panel_member_ids)) {
            $panelMembers = \App\Models\User::whereIn('id', $panelAssignment->panel_member_ids)
                ->select('id', 'name', 'email')
                ->get();
        }
        
        
        return view('student.thesis.defense', compact('panelAssignment', 'panelMembers'));
    }

    /**
     * Display panel assignments for the student
     */
    public function panelAssignments()
    {
        $this->ensureUserIsStudent();
        
        $user = Auth::user();
        
        // Get panel assignments for this student
        $panelAssignments = \App\Models\PanelAssignment::where('student_id', $user->id)
            ->with(['panelChair', 'secretary', 'reviews.reviewer', 'thesisDocument'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.thesis.panel-assignments', compact('panelAssignments'));
    }

    /**
     * Show a specific panel assignment for the student
     */
    public function showPanelAssignment(\App\Models\PanelAssignment $panelAssignment)
    {
        $this->ensureUserIsStudent();
        
        $user = Auth::user();
        
        // Check if this panel assignment belongs to the student
        if ($panelAssignment->student_id !== $user->id) {
            abort(403, 'You do not have permission to view this panel assignment.');
        }

        // Load related data
        $panelAssignment->load(['panelChair', 'secretary', 'reviews.reviewer', 'thesisDocument']);

        return view('student.thesis.panel-assignment-show', compact('panelAssignment'));
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

        // Clear any old session data to prevent form contamination
        if ($documentType === 'panel_assignment') {
            $request->session()->forget(['title', 'description', 'panel_justification', 'required_specializations', 'special_requirements', 'comments', 'remarks']);
        }

        return view('student.thesis.create', compact('documentType'));
    }

    /**
     * Store the submitted document
     */
    public function store(Request $request)
    {
        $this->ensureUserIsStudent();
        
        // Debug: Log the incoming request data
        Log::info('Thesis submission attempt', [
            'document_type' => $request->document_type,
            'student_id' => $request->student_id,
            'has_files' => $request->hasFile('files'),
            'user_id' => Auth::id(),
            'all_input' => $request->all(), // Log all input for debugging
        ]);
        
        // Check for suspicious data that might indicate form contamination
        $suspiciousText = "in the faculty where the adviser is assigned of the students, make sure that the adviser is authoroize to download t he document of the students whenver the adviser is reviewing it";
        $fieldsToCheck = ['title', 'description', 'panel_justification', 'required_specializations', 'special_requirements', 'comments', 'remarks'];
        
        foreach ($fieldsToCheck as $field) {
            if ($request->has($field) && str_contains($request->input($field), $suspiciousText)) {
                Log::warning('Suspicious data detected in form submission', [
                    'field' => $field,
                    'value' => $request->input($field),
                    'user_id' => Auth::id(),
                ]);
                
                return back()->with('error', 'Please fill out the form fields with proper information. Some fields contain invalid data.')
                    ->withInput();
            }
        }
        
        // Base validation rules
        $rules = [
            'document_type' => ['required', Rule::in(['proposal', 'approval_sheet', 'panel_assignment', 'final_manuscript'])],
            'student_id' => 'required|string|max:20',
            'full_name' => 'required|string|max:255',
            'course_program' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'submission_date' => 'required|date',
            'adviser_id' => 'nullable|exists:users,id',
            'adviser_name' => 'nullable|string|max:255',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // Changed from required to nullable
            'comments' => 'nullable|string',
            'remarks' => 'nullable|string',
        ];

        // Document-specific validation rules
        switch ($request->document_type) {
            case 'proposal':
                $rules = array_merge($rules, [
                    'abstract' => 'required|string|min:200',
                    'research_area' => 'required|string',
                    'methodology' => 'required|string',
                    'keywords' => 'required|string',
                    'research_objectives' => 'required|string',
                    'expected_outcomes' => 'required|string',
                    'expected_start_date' => 'required|date',
                    'expected_completion_date' => 'required|date|after:expected_start_date',
                ]);
                
                // Add conditional validation for custom research area
                if ($request->research_area === 'Other') {
                    $rules['custom_research_area'] = 'required|string|max:255';
                }
                break;

            case 'approval_sheet':
                $rules = array_merge($rules, [
                    'panel_chair' => 'required|string|max:255',
                    'panel_member_1' => 'required|string|max:255',
                    'panel_member_2' => 'required|string|max:255',
                    'approval_date' => 'required|date',
                    'approval_status' => 'required|string',
                    'defense_date' => 'required|date',
                    'defense_time' => 'required',
                    'defense_venue' => 'required|string|max:255',
                    'committee_recommendations' => 'required|string',
                ]);
                break;

            case 'panel_assignment':
                Log::info('Panel assignment validation rules being applied', [
                    'defense_type' => $request->defense_type,
                    'required_specializations' => $request->required_specializations,
                    'preferred_date_1' => $request->preferred_date_1,
                    'preferred_time' => $request->preferred_time,
                    'panel_justification' => $request->panel_justification,
                ]);
                
                $rules = array_merge($rules, [
                    'defense_type' => 'required|string',
                    'required_specializations' => 'required|string',
                    'preferred_date_1' => 'required|date',
                    'preferred_time' => 'required|string',
                    'panel_justification' => 'required|string',
                    // Optional fields for panel assignment
                    'preferred_date_2' => 'nullable|date',
                    'preferred_date_3' => 'nullable|date',
                    'preferred_venue' => 'nullable|string',
                    'special_requirements' => 'nullable|string',
                ]);
                break;

            case 'final_manuscript':
                $rules = array_merge($rules, [
                    'final_revisions_completed' => 'required|boolean',
                    'manuscript_status' => 'required|string',
                    'total_pages' => 'required|integer|min:1',
                    'total_chapters' => 'required|integer|min:1',
                    'chapters_completed' => 'required|array|min:1',
                    'has_plagiarism_report' => 'required|boolean',
                    'formatting_compliance' => 'required|array|min:6',
                ]);
                
                // Conditional validation for plagiarism report
                if ($request->has_plagiarism_report) {
                    $rules['plagiarism_percentage'] = 'required|numeric|min:0|max:100';
                }
                break;
        }

        try {
            $validatedData = $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors for debugging
            Log::error('Validation failed for thesis submission', [
                'errors' => $e->errors(),
                'document_type' => $request->document_type,
                'input_data' => $request->all(),
                'failed_rules' => array_keys($e->errors()),
            ]);
            
            return back()->withErrors($e->errors())->withInput();
        }

        // Handle file uploads with proper naming convention
        $uploadedFiles = [];
        if ($request->hasFile('files')) {
            $fileNamingPrefix = $this->generateFileNamingPrefix($request->student_id, $request->document_type, $request->submission_date);
            
            foreach ($request->file('files') as $index => $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileName = "{$fileNamingPrefix}_" . ($index + 1) . "_{$originalName}.{$extension}";
                
                $path = $file->storeAs('thesis-documents/' . Auth::id(), $fileName, 'public');
                $uploadedFiles[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'stored_name' => $fileName,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientMimeType(),
                    'uploaded_at' => now()->toISOString(),
                ];
            }
        }

        // Collect document-specific metadata
        $documentMetadata = $this->collectDocumentMetadata($request);
        
        // Collect panel members for approval sheet
        $panelMembers = [];
        if ($request->document_type === 'approval_sheet') {
            // Panel Chair
            if ($request->panel_chair_id || $request->panel_chair) {
                $panelMembers['chair'] = [
                    'id' => $request->panel_chair_id,
                    'name' => $request->panel_chair,
                ];
            }
            
            // Panel Member 1
            if ($request->panel_member_1_id || $request->panel_member_1) {
                $panelMembers['member_1'] = [
                    'id' => $request->panel_member_1_id,
                    'name' => $request->panel_member_1,
                ];
            }
            
            // Panel Member 2
            if ($request->panel_member_2_id || $request->panel_member_2) {
                $panelMembers['member_2'] = [
                    'id' => $request->panel_member_2_id,
                    'name' => $request->panel_member_2,
                ];
            }
            
            // Add additional panel members if provided
            for ($i = 3; $i <= 10; $i++) {
                $memberIdField = "panel_member_{$i}_id";
                $memberNameField = "panel_member_{$i}";
                
                if ($request->has($memberIdField) || ($request->has($memberNameField) && !empty($request->input($memberNameField)))) {
                    $panelMembers["member_{$i}"] = [
                        'id' => $request->input($memberIdField),
                        'name' => $request->input($memberNameField),
                    ];
                }
            }
        }

        // Handle preferred panel members for panel assignment
        if ($request->document_type === 'panel_assignment') {
            $panelMembers = [];
            if ($request->preferred_panel_chair_id || $request->preferred_panel_chair) {
                $panelMembers['preferred_chair'] = [
                    'id' => $request->preferred_panel_chair_id,
                    'name' => $request->preferred_panel_chair,
                ];
            }
            if ($request->preferred_panel_member_1_id || $request->preferred_panel_member_1) {
                $panelMembers['preferred_member_1'] = [
                    'id' => $request->preferred_panel_member_1_id,
                    'name' => $request->preferred_panel_member_1,
                ];
            }
            if ($request->preferred_panel_member_2_id || $request->preferred_panel_member_2) {
                $panelMembers['preferred_member_2'] = [
                    'id' => $request->preferred_panel_member_2_id,
                    'name' => $request->preferred_panel_member_2,
                ];
            }
        }

        // Generate file naming prefix for storage
        $fileNamingPrefix = $this->generateFileNamingPrefix($request->student_id, $request->document_type, $request->submission_date);

        try {
            // Create the thesis document with form-specific data
            $thesisDocumentData = [
                'user_id' => Auth::id(),
                'document_type' => $request->document_type,
                'student_id' => $request->student_id,
                'full_name' => $request->full_name,
                'course_program' => $request->course_program,
                'title' => $request->title,
                'description' => $request->description,
                'abstract' => $request->abstract,
                'research_area' => $request->research_area === 'Other' ? $request->custom_research_area : $request->research_area,
                'adviser_name' => $request->adviser_name,
                'adviser_id' => $request->adviser_id,
                'panel_members' => $panelMembers,
                'approval_date' => $request->approval_date,
                'defense_date' => $request->defense_date,
                'defense_type' => $request->defense_type,
                'defense_venue' => $request->defense_venue,
                'requested_schedule' => $this->buildRequestedSchedule($request),
                'final_revisions_completed' => $request->final_revisions_completed ?? false,
                'has_plagiarism_report' => $request->has_plagiarism_report ?? false,
                'plagiarism_percentage' => $request->plagiarism_percentage,
                'document_metadata' => $documentMetadata,
                'uploaded_files' => $uploadedFiles,
                'comments' => $request->comments,
                'remarks' => $request->remarks,
                'submission_date' => $request->submission_date,
                'file_naming_prefix' => $fileNamingPrefix,
                'status' => 'pending',
                'version_number' => 1,
                'status_history' => [[
                    'status' => 'pending',
                    'changed_by' => Auth::user()->name,
                    'changed_at' => now()->toISOString(),
                    'reason' => 'Initial submission',
                ]],
            ];

            // Add form-specific data for panel assignment
            if ($request->document_type === 'panel_assignment') {
                $thesisDocumentData = array_merge($thesisDocumentData, [
                    'required_specializations' => $request->required_specializations,
                    'preferred_dates' => array_filter([
                        $request->preferred_date_1,
                        $request->preferred_date_2,
                        $request->preferred_date_3,
                    ]),
                    'preferred_time' => $request->preferred_time,
                    'preferred_venue' => $request->preferred_venue,
                    'special_requirements' => $request->special_requirements,
                    'panel_justification' => $request->panel_justification,
                ]);
            }

            // Create the thesis document
            $thesisDocument = ThesisDocument::create($thesisDocumentData);

            Log::info('Thesis document created successfully', [
                'id' => $thesisDocument->id,
                'document_type' => $thesisDocument->document_type,
                'student_id' => $thesisDocument->student_id,
            ]);

            // Notify adviser if assigned (wrapped in try-catch to prevent failure)
            if ($thesisDocument->adviser_id) {
                try {
                    $this->notifyAdviser($thesisDocument);
                } catch (\Exception $e) {
                    Log::warning('Failed to notify adviser', [
                        'thesis_id' => $thesisDocument->id,
                        'adviser_id' => $thesisDocument->adviser_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Create panel assignment and notify panel members for panel assignment requests
            if ($request->document_type === 'panel_assignment') {
                try {
                    $this->createPanelAssignmentAndNotify($thesisDocument);
                } catch (\Exception $e) {
                    Log::warning('Failed to create panel assignment and notify panel members', [
                        'thesis_id' => $thesisDocument->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Fire event for cross-system updates
            event(new \App\Events\ThesisSubmitted($thesisDocument, [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'submission_method' => 'web_form',
                'file_count' => count($uploadedFiles),
                'document_type' => $request->document_type,
            ]));

            $successMessage = $this->getSuccessMessage($request->document_type);

            return redirect()->route('student.thesis.history')
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            Log::error('Failed to create thesis document', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'document_type' => $request->document_type,
            ]);
            
            return back()->with('error', 'Failed to submit thesis document. Please try again.')
                ->withInput();
        }
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
     * Show the edit form for a document that was returned for revision
     */
    public function edit(ThesisDocument $document)
    {
        $this->ensureUserIsStudent();
        
        // Ensure the document belongs to the authenticated user
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this document.');
        }

        // Only allow editing if the document was returned for revision
        if ($document->status !== 'returned_for_revision') {
            return redirect()->route('student.thesis.show', $document)
                ->with('error', 'This document cannot be edited. Only documents returned for revision can be updated.');
        }

        return view('student.thesis.edit', compact('document'));
    }

    /**
     * Update a document that was returned for revision
     */
    public function update(Request $request, ThesisDocument $document)
    {
        $this->ensureUserIsStudent();
        
        // Ensure the document belongs to the authenticated user
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this document.');
        }

        // Only allow updating if the document was returned for revision
        if ($document->status !== 'returned_for_revision') {
            return redirect()->route('student.thesis.show', $document)
                ->with('error', 'This document cannot be updated. Only documents returned for revision can be updated.');
        }

        // Validate the request based on document type
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ];

        // Add document-specific validation rules
        switch ($document->document_type) {
            case 'proposal':
                $rules = array_merge($rules, [
                    'abstract' => 'required|string|min:200',
                    'research_area' => 'required|string',
                    'methodology' => 'required|string',
                    'keywords' => 'required|string',
                    'research_objectives' => 'required|string',
                    'expected_outcomes' => 'required|string',
                    'expected_start_date' => 'required|date',
                    'expected_completion_date' => 'required|date|after:expected_start_date',
                ]);
                
                // Add conditional validation for custom research area
                if ($request->research_area === 'Other') {
                    $rules['custom_research_area'] = 'required|string|max:255';
                }
                break;
        }

        $request->validate($rules);

        try {
            // Handle new file uploads
            $uploadedFiles = $document->uploaded_files ?? [];
            if ($request->hasFile('files')) {
                $fileNamingPrefix = $this->generateFileNamingPrefix($document->student_id, $document->document_type, $document->submission_date);
                
                foreach ($request->file('files') as $index => $file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $fileName = "{$fileNamingPrefix}_" . ($index + 1) . "_{$originalName}.{$extension}";
                    
                    $path = $file->storeAs('thesis-documents/' . Auth::id(), $fileName, 'public');
                    $uploadedFiles[] = [
                        'original_name' => $file->getClientOriginalName(),
                        'stored_name' => $fileName,
                        'path' => $path,
                        'size' => $file->getSize(),
                        'type' => $file->getClientMimeType(),
                        'uploaded_at' => now()->toISOString(),
                    ];
                }
            }
            
            // Validate that at least one file remains
            if (empty($uploadedFiles)) {
                return back()->with('error', 'At least one file must be attached to the document.')
                    ->withInput();
            }

            // Prepare update data
            $updateData = [
                'title' => $request->title,
                'description' => $request->description,
                'uploaded_files' => $uploadedFiles,
                'status' => 'pending', // Reset to pending for re-review
                'review_comments' => null, // Clear previous review comments
                'reviewed_by' => null,
                'reviewed_at' => null,
                'version_number' => $document->version_number + 1, // Increment version
            ];

            // Add document-specific fields
            switch ($document->document_type) {
                case 'proposal':
                    $updateData = array_merge($updateData, [
                        'abstract' => $request->abstract,
                        'research_area' => $request->research_area === 'Other' ? $request->custom_research_area : $request->research_area,
                        'methodology' => $request->methodology,
                        'keywords' => $request->keywords,
                        'research_objectives' => $request->research_objectives,
                        'expected_outcomes' => $request->expected_outcomes,
                        'expected_start_date' => $request->expected_start_date,
                        'expected_completion_date' => $request->expected_completion_date,
                    ]);
                    break;
            }

            // Update the document
            $document->update($updateData);

            // Log the revision activity
            \App\Models\ActivityLog::logActivity(
                'thesis_document_revised',
                'Document Revised',
                $document,
                ['old_status' => 'returned_for_revision', 'new_status' => 'pending'],
                ['revision_reason' => 'Student updated document based on faculty feedback'],
                null,
                'Document revised and resubmitted for review',
                Auth::id()
            );

            // Notify faculty about the resubmission
            if ($document->adviser_id) {
                try {
                    \App\Models\Notification::createForUser(
                        $document->adviser_id,
                        'thesis_resubmitted',
                        'Document Resubmitted for Review',
                        "Student {$document->full_name} has resubmitted their {$document->document_type} for review after making revisions.",
                        [
                            'document_id' => $document->id,
                            'student_name' => $document->full_name,
                            'document_type' => $document->document_type,
                            'thesis_title' => $document->title,
                            'url' => route('faculty.thesis.show', $document)
                        ],
                        get_class($document),
                        $document->id,
                        'normal'
                    );
                } catch (\Exception $e) {
                    Log::warning('Failed to notify adviser about resubmission', [
                        'document_id' => $document->id,
                        'adviser_id' => $document->adviser_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return redirect()->route('student.thesis.show', $document)
                ->with('success', 'Document has been updated and resubmitted for review. Your adviser will be notified.');

        } catch (\Exception $e) {
            Log::error('Failed to update thesis document', [
                'error' => $e->getMessage(),
                'document_id' => $document->id,
                'user_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'Failed to update document. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove a specific file from the document
     */
    public function removeFile(ThesisDocument $document, $fileIndex)
    {
        $this->ensureUserIsStudent();
        
        // Ensure the document belongs to the authenticated user
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this document.');
        }

        // Only allow removing files if the document was returned for revision
        if ($document->status !== 'returned_for_revision') {
            return response()->json(['error' => 'This document cannot be edited. Only documents returned for revision can be updated.'], 403);
        }

        $uploadedFiles = $document->uploaded_files ?? [];
        
        if (!isset($uploadedFiles[$fileIndex])) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        try {
            // Delete the physical file
            $filePath = $uploadedFiles[$fileIndex]['path'] ?? null;
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            
            // Remove from array
            unset($uploadedFiles[$fileIndex]);
            
            // Re-index the array to maintain sequential indices
            $uploadedFiles = array_values($uploadedFiles);
            
            // Update the document
            $document->update(['uploaded_files' => $uploadedFiles]);
            
            // Log the file removal activity
            \App\Models\ActivityLog::logActivity(
                'thesis_file_removed',
                'File Removed',
                $document,
                ['removed_file' => $uploadedFiles[$fileIndex]['original_name'] ?? 'Unknown'],
                ['file_index' => $fileIndex],
                null,
                'File removed from document during revision',
                Auth::id()
            );
            
            return response()->json([
                'success' => true,
                'message' => 'File removed successfully.',
                'remaining_files' => count($uploadedFiles)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to remove file from thesis document', [
                'error' => $e->getMessage(),
                'document_id' => $document->id,
                'file_index' => $fileIndex,
                'user_id' => Auth::id(),
            ]);
            
            return response()->json(['error' => 'Failed to remove file. Please try again.'], 500);
        }
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
     * Collect document-specific metadata
     */
    private function collectDocumentMetadata(Request $request): array
    {
        $baseData = [
            'submission_date' => $request->submission_date,
            'course_program' => $request->course_program,
            'adviser_name' => $request->adviser_name,
            'adviser_id' => $request->adviser_id,
        ];

        switch ($request->document_type) {
            case 'proposal':
                return array_merge($baseData, [
                    'abstract' => $request->abstract,
                    'research_area' => $request->research_area,
                    'methodology' => $request->methodology,
                    'keywords' => explode(',', $request->keywords ?? ''),
                    'research_objectives' => $request->research_objectives,
                    'expected_outcomes' => $request->expected_outcomes,
                    'expected_start_date' => $request->expected_start_date,
                    'expected_completion_date' => $request->expected_completion_date,
                ]);

            case 'approval_sheet':
                return array_merge($baseData, [
                    'approval_status' => $request->approval_status,
                    'defense_time' => $request->defense_time,
                    'committee_recommendations' => $request->committee_recommendations,
                    'final_grade' => $request->final_grade,
                    'approval_validity' => $request->approval_validity,
                ]);

            case 'panel_assignment':
                return array_merge($baseData, [
                    'required_specializations' => $request->required_specializations,
                    'preferred_dates' => array_filter([
                        $request->preferred_date_1,
                        $request->preferred_date_2,
                        $request->preferred_date_3,
                    ]),
                    'preferred_time' => $request->preferred_time,
                    'preferred_venue' => $request->preferred_venue,
                    'special_requirements' => $request->special_requirements,
                    'panel_justification' => $request->panel_justification,
                ]);

            case 'final_manuscript':
                return array_merge($baseData, [
                    'manuscript_status' => $request->manuscript_status,
                    'total_pages' => $request->total_pages,
                    'total_chapters' => $request->total_chapters,
                    'word_count' => $request->word_count,
                    'chapters_completed' => $request->chapters_completed ?? [],
                    'plagiarism_tool' => $request->plagiarism_tool,
                    'plagiarism_check_date' => $request->plagiarism_check_date,
                    'formatting_compliance' => $request->formatting_compliance ?? [],
                    'manuscript_notes' => $request->manuscript_notes,
                ]);

            default:
                return $baseData;
        }
    }

    /**
     * Generate file naming prefix according to convention
     */
    private function generateFileNamingPrefix(string $studentId, string $documentType, string $submissionDate): string
    {
        $date = \Carbon\Carbon::parse($submissionDate)->format('Y-m-d');
        return "{$studentId}_{$documentType}_{$date}";
    }

    /**
     * Build requested schedule data for panel assignment
     */
    private function buildRequestedSchedule(Request $request): ?array
    {
        if ($request->document_type !== 'panel_assignment') {
            return null;
        }

        $schedule = [
            'preferred_dates' => array_filter([
                $request->preferred_date_1,
                $request->preferred_date_2,
                $request->preferred_date_3,
            ]),
            'preferred_time' => $request->preferred_time,
            'preferred_venue' => $request->preferred_venue,
            'special_requirements' => $request->special_requirements,
        ];

        return $schedule;
    }

    /**
     * Get success message based on document type
     */
    private function getSuccessMessage(string $documentType): string
    {
        return match($documentType) {
            'proposal' => 'Thesis proposal submitted successfully! Your proposal is now under review by your adviser.',
            'approval_sheet' => 'Approval sheet submitted successfully! The approval documentation has been recorded.',
            'panel_assignment' => 'Panel assignment request submitted successfully! The administration will review your request and assign panel members.',
            'final_manuscript' => 'Final manuscript submitted successfully! Your completed thesis is now under final review.',
            default => 'Thesis document submitted successfully! Your submission is now under review.',
        };
    }

    /**
     * Notify adviser about thesis submission
     */
    private function notifyAdviser(ThesisDocument $thesisDocument): void
    {
        if ($thesisDocument->adviser_id) {
            // Check if Notification model and table exist
            if (!class_exists('\App\Models\Notification')) {
                Log::info('Notification model not available, skipping adviser notification');
                return;
            }

            try {
                // Create notification for adviser
                \App\Models\Notification::create([
                    'user_id' => $thesisDocument->adviser_id,
                    'type' => 'thesis_submission',
                    'title' => 'New Thesis Document Submission',
                    'message' => "Student {$thesisDocument->full_name} has submitted a {$thesisDocument->document_type} for review.",
                    'data' => [
                        'thesis_document_id' => $thesisDocument->id,
                        'student_name' => $thesisDocument->full_name,
                        'document_type' => $thesisDocument->document_type,
                        'thesis_title' => $thesisDocument->title,
                    ],
                    'related_model_type' => 'ThesisDocument',
                    'related_model_id' => $thesisDocument->id,
                    'priority' => 'medium',
                ]);
                
                Log::info('Adviser notification created successfully', [
                    'adviser_id' => $thesisDocument->adviser_id,
                    'thesis_id' => $thesisDocument->id,
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to create adviser notification', [
                    'error' => $e->getMessage(),
                    'adviser_id' => $thesisDocument->adviser_id,
                ]);
            }
        }
    }

    /**
     * Create panel assignment and notify panel members
     */
    private function createPanelAssignmentAndNotify(ThesisDocument $thesisDocument): void
    {
        // Check if panel assignment already exists for this student
        $existingPanelAssignment = \App\Models\PanelAssignment::where('student_id', $thesisDocument->user_id)
            ->where('thesis_document_id', $thesisDocument->id)
            ->first();
            
        if ($existingPanelAssignment) {
            Log::info('Panel assignment already exists for this student and document', [
                'thesis_id' => $thesisDocument->id,
                'student_id' => $thesisDocument->user_id,
                'existing_panel_id' => $existingPanelAssignment->id,
            ]);
            return;
        }

        // Extract panel members from the thesis document
        $panelMembers = $thesisDocument->panel_members ?? [];
        $panelChairId = $thesisDocument->document_metadata['panel_chair_id'] ?? null;
        $secretaryId = $thesisDocument->document_metadata['secretary_id'] ?? null;
        
        if (empty($panelMembers) && !$panelChairId) {
            Log::warning('No panel members specified for panel assignment', [
                'thesis_id' => $thesisDocument->id,
            ]);
            return;
        }

        // Create panel assignment
        $panelAssignment = \App\Models\PanelAssignment::create([
            'student_id' => $thesisDocument->user_id,
            'thesis_document_id' => $thesisDocument->id,
            'thesis_title' => $thesisDocument->title,
            'thesis_description' => $thesisDocument->description,
            'panel_members' => $panelMembers,
            'panel_chair_id' => $panelChairId,
            'secretary_id' => $secretaryId,
            'defense_type' => $thesisDocument->defense_type,
            'defense_venue' => $thesisDocument->defense_venue,
            'defense_instructions' => $thesisDocument->document_metadata['defense_instructions'] ?? null,
            'status' => 'scheduled',
            'created_by' => $thesisDocument->user_id,
        ]);

        // Create review records for all panel members
        $this->createReviewRecords($panelAssignment, $panelMembers, $panelChairId, $secretaryId);

        // Notify all panel members
        $this->notifyPanelMembers($panelAssignment, $panelMembers, $panelChairId, $secretaryId);
    }

    /**
     * Create review records for panel members
     */
    private function createReviewRecords(\App\Models\PanelAssignment $panelAssignment, array $panelMembers, ?int $panelChairId, ?int $secretaryId): void
    {
        $reviewers = array_merge($panelMembers, [$panelChairId, $secretaryId]);
        $reviewers = array_filter(array_unique($reviewers));

        foreach ($reviewers as $reviewerId) {
            if (!$reviewerId) continue;

            $role = 'panel_member';
            if ($reviewerId === $panelChairId) {
                $role = 'panel_chair';
            } elseif ($reviewerId === $secretaryId) {
                $role = 'panel_member'; // Secretary is also a panel member
            }

            \App\Models\PanelAssignmentReview::create([
                'panel_assignment_id' => $panelAssignment->id,
                'thesis_document_id' => $panelAssignment->thesis_document_id,
                'reviewer_id' => $reviewerId,
                'reviewer_role' => $role,
                'status' => 'pending',
            ]);
        }
    }

    /**
     * Notify panel members about their assignment
     */
    private function notifyPanelMembers(\App\Models\PanelAssignment $panelAssignment, array $panelMembers, ?int $panelChairId, ?int $secretaryId): void
    {
        $allPanelMembers = array_merge($panelMembers, [$panelChairId, $secretaryId]);
        $allPanelMembers = array_filter(array_unique($allPanelMembers));

        if (empty($allPanelMembers)) {
            return;
        }

        $studentName = $panelAssignment->student->name;
        $defenseType = $panelAssignment->defense_type_label;
        
        $notification = [
            'title' => 'Panel Assignment - Review Required',
            'message' => "You have been assigned to review {$studentName}'s {$defenseType}. Please review the submitted documents and provide your feedback.",
            'data' => [
                'panel_assignment_id' => $panelAssignment->id,
                'student_name' => $studentName,
                'thesis_title' => $panelAssignment->thesis_title,
                'defense_type' => $panelAssignment->defense_type,
                'defense_type_label' => $defenseType,
                'url' => route('faculty.panel-assignments.show', $panelAssignment),
            ]
        ];

        foreach ($allPanelMembers as $memberId) {
            if (!$memberId) continue;

            \App\Models\Notification::createForUser(
                $memberId,
                'panel_assignment_review',
                $notification['title'],
                $notification['message'],
                $notification['data'],
                get_class($panelAssignment),
                $panelAssignment->id,
                'high'
            );
        }
    }

    /**
     * Notify admin about panel assignment request
     */
    private function notifyAdminForPanelRequest(ThesisDocument $thesisDocument): void
    {
        // Check if Notification model and table exist
        if (!class_exists('\App\Models\Notification')) {
            Log::info('Notification model not available, skipping admin notification');
            return;
        }

        try {
            $admins = \App\Models\User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'panel_assignment_request',
                    'title' => 'Panel Assignment Request',
                    'message' => "Student {$thesisDocument->full_name} has requested panel assignment for {$thesisDocument->document_type}.",
                    'data' => [
                        'thesis_document_id' => $thesisDocument->id,
                        'student_name' => $thesisDocument->full_name,
                        'document_type' => $thesisDocument->document_type,
                        'thesis_title' => $thesisDocument->title,
                    ],
                    'related_model_type' => 'ThesisDocument',
                    'related_model_id' => $thesisDocument->id,
                    'priority' => 'high',
                ]);
            }
            
            Log::info('Admin notifications created successfully', [
                'admin_count' => $admins->count(),
                'thesis_id' => $thesisDocument->id,
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to create admin notifications', [
                'error' => $e->getMessage(),
                'thesis_id' => $thesisDocument->id,
            ]);
        }
    }

    /**
     * Mark defense as completed by student
     */
    public function markDefenseCompleted(Request $request, \App\Models\PanelAssignment $panelAssignment)
    {
        // Ensure the student owns this panel assignment
        if ($panelAssignment->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this panel assignment.');
        }

        // Check if defense date has passed
        if ($panelAssignment->defense_date > now()) {
            return redirect()->back()
                ->withErrors(['defense' => 'Defense date has not yet passed. You can only mark defense as completed after the scheduled date.']);
        }

        // Update panel assignment status
        $panelAssignment->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by' => Auth::id(),
        ]);

        // Log the activity
        \App\Models\ActivityLog::logActivity(
            'defense_completed',
            'completed',
            $panelAssignment,
            null,
            [
                'panel_assignment_id' => $panelAssignment->id,
                'defense_type' => $panelAssignment->defense_type,
                'defense_date' => $panelAssignment->defense_date,
                'completed_at' => now(),
            ],
            [
                'panel_assignment_id' => $panelAssignment->id,
                'defense_type' => $panelAssignment->defense_type,
                'defense_date' => $panelAssignment->defense_date,
                'completed_at' => now(),
            ],
            'Student marked defense as completed',
            Auth::id()
        );

        // Send notification to admin about defense completion
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::createForUser(
                $admin->id,
                'defense_completed',
                'Defense Completed by Student',
                "Student {$panelAssignment->student->name} has marked their {$panelAssignment->defense_type_label} as completed.",
                [
                    'student_name' => $panelAssignment->student->name,
                    'defense_type' => $panelAssignment->defense_type_label,
                    'defense_date' => $panelAssignment->defense_date,
                    'completed_at' => now(),
                    'url' => route('admin.panel.show', $panelAssignment),
                ],
                'PanelAssignment',
                $panelAssignment->id,
                'high'
            );
        }

        return redirect()->route('student.thesis.defense')
            ->with('success', 'Defense marked as completed! You can now proceed to submit your approval sheet.');
    }
}
