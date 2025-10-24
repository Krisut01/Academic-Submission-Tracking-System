<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicForm;
use App\Models\ThesisDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RecordsController extends Controller
{
    /**
     * Check if the authenticated user is an admin
     */
    private function ensureUserIsAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Only administrators can access this resource.');
        }
    }

    /**
     * Display the RMT table with all records
     */
    public function index(Request $request)
    {
        $this->ensureUserIsAdmin();
        
        $activeTab = $request->get('tab', 'academic_forms');
        
        if ($activeTab === 'academic_forms') {
            return $this->academicFormsIndex($request);
        } else {
            return $this->thesisDocumentsIndex($request);
        }
    }

    /**
     * Academic Forms Index
     */
    private function academicFormsIndex(Request $request)
    {
        $query = AcademicForm::with(['user']);

        // Apply filters
        if ($request->filled('form_type')) {
            $query->where('form_type', $request->form_type);
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $forms = $query->orderBy('submission_date', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total_forms' => AcademicForm::count(),
            'pending_forms' => AcademicForm::where('status', 'pending')->count(),
            'approved_forms' => AcademicForm::where('status', 'approved')->count(),
            'registration_forms' => AcademicForm::where('form_type', 'registration')->count(),
            'clearance_forms' => AcademicForm::where('form_type', 'clearance')->count(),
            'evaluation_forms' => AcademicForm::where('form_type', 'evaluation')->count(),
        ];

        return view('admin.records.index', compact('forms', 'stats'))
            ->with('activeTab', 'academic_forms');
    }

    /**
     * Thesis Documents Index
     */
    private function thesisDocumentsIndex(Request $request)
    {
        $query = ThesisDocument::with(['user', 'reviewer']);

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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $documents = $query->orderBy('submission_date', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total_documents' => ThesisDocument::count(),
            'pending_documents' => ThesisDocument::where('status', 'pending')->count(),
            'approved_documents' => ThesisDocument::where('status', 'approved')->count(),
            'proposal_documents' => ThesisDocument::where('document_type', 'proposal')->count(),
            'approval_documents' => ThesisDocument::where('document_type', 'approval_sheet')->count(),
            'panel_documents' => ThesisDocument::where('document_type', 'panel_assignment')->count(),
            'final_documents' => ThesisDocument::where('document_type', 'final_manuscript')->count(),
        ];

        return view('admin.records.index', compact('documents', 'stats'))
            ->with('activeTab', 'thesis_documents');
    }

    /**
     * Show a specific academic form
     */
    public function showForm(AcademicForm $form)
    {
        $this->ensureUserIsAdmin();
        $form->load(['user']);
        return view('admin.records.show-form', compact('form'));
    }

    /**
     * Show a specific thesis document
     */
    public function showDocument(ThesisDocument $document)
    {
        $this->ensureUserIsAdmin();
        $document->load(['user', 'reviewer']);
        return view('admin.records.show-document', compact('document'));
    }

    /**
     * Download file from academic form or thesis document
     */
    public function downloadFile(Request $request, $type, $id, $fileIndex)
    {
        $this->ensureUserIsAdmin();

        if ($type === 'form') {
            $record = AcademicForm::findOrFail($id);
            $files = $record->uploaded_files ?? [];
        } else {
            $record = ThesisDocument::findOrFail($id);
            $files = $record->uploaded_files ?? [];
        }

        if (!isset($files[$fileIndex])) {
            abort(404, 'File not found.');
        }

        $file = $files[$fileIndex];
        $filePath = storage_path('app/public/' . $file['path']);

        if (!file_exists($filePath)) {
            abort(404, 'File not found on disk.');
        }

        return response()->download($filePath, $file['original_name']);
    }

    /**
     * Export academic forms as CSV
     */
    public function exportForms(Request $request)
    {
        $this->ensureUserIsAdmin();
        
        $query = AcademicForm::with(['user']);
        
        // Apply same filters as index
        if ($request->filled('form_type')) {
            $query->where('form_type', $request->form_type);
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

        $forms = $query->get();

        $filename = 'academic_forms_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($forms) {
            $handle = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($handle, [
                'ID', 'Student Name', 'Student ID', 'Form Type', 'Title', 
                'Status', 'Submission Date', 'Reviewed By', 'Reviewed At'
            ]);
            
            // Add form data
            foreach ($forms as $form) {
                fputcsv($handle, [
                    $form->id,
                    $form->user->name,
                    $form->student_id,
                    ucfirst($form->form_type),
                    $form->title,
                    ucfirst($form->status),
                    $form->submission_date ? $form->submission_date->format('Y-m-d') : 'N/A',
                    $form->reviewer ? $form->reviewer->name : 'N/A',
                    $form->reviewed_at ? $form->reviewed_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }
            
            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export thesis documents as CSV
     */
    public function exportDocuments(Request $request)
    {
        $this->ensureUserIsAdmin();
        
        $query = ThesisDocument::with(['user', 'reviewer']);
        
        // Apply same filters as index
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

        $documents = $query->get();

        $filename = 'thesis_documents_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($documents) {
            $handle = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($handle, [
                'ID', 'Student Name', 'Student ID', 'Document Type', 'Title', 
                'Status', 'Submission Date', 'Reviewed By', 'Reviewed At'
            ]);
            
            // Add document data
            foreach ($documents as $document) {
                fputcsv($handle, [
                    $document->id,
                    $document->user->name,
                    $document->student_id,
                    ucfirst(str_replace('_', ' ', $document->document_type)),
                    $document->title,
                    ucfirst(str_replace('_', ' ', $document->status)),
                    $document->submission_date ? $document->submission_date->format('Y-m-d') : 'N/A',
                    $document->reviewer ? $document->reviewer->name : 'N/A',
                    $document->reviewed_at ? $document->reviewed_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }
            
            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Get feedback logs for a specific record
     */
    public function feedbackLogs($type, $id)
    {
        $this->ensureUserIsAdmin();

        if ($type === 'form') {
            $record = AcademicForm::with(['user', 'reviewer'])->findOrFail($id);
        } else {
            $record = ThesisDocument::with(['user', 'reviewer'])->findOrFail($id);
        }

        return view('admin.records.feedback-logs', compact('record', 'type'));
    }

    /**
     * Confirm defense completion for a thesis document
     */
    public function confirmDefense(Request $request, ThesisDocument $document)
    {
        $this->ensureUserIsAdmin();

        $request->validate([
            'defense_status' => 'required|in:completed,failed,postponed',
            'defense_notes' => 'nullable|string|max:1000',
            'defense_date' => 'nullable|date',
            'defense_grade' => 'nullable|numeric|min:0|max:100'
        ]);

        try {
            // Update document with defense information
            $document->update([
                'defense_status' => $request->defense_status,
                'defense_notes' => $request->defense_notes,
                'defense_date' => $request->defense_date ? \Carbon\Carbon::parse($request->defense_date) : now(),
                'defense_grade' => $request->defense_grade,
                'defense_confirmed_by' => Auth::id(),
                'defense_confirmed_at' => now(),
                'status' => $request->defense_status === 'completed' ? 'approved' : 'returned'
            ]);

            // Log the activity
            \App\Models\ActivityLog::logActivity(
                'defense_confirmed',
                'defense_confirmed',
                $document,
                null, // old values
                [
                    'document_id' => $document->id,
                    'defense_status' => $request->defense_status,
                    'defense_grade' => $request->defense_grade,
                    'defense_notes' => $request->defense_notes
                ], // new values
                [
                    'defense_confirmed_by' => Auth::id(),
                    'defense_confirmed_at' => now()
                ], // metadata
                'Defense confirmed by admin',
                Auth::id()
            );

            // Send notification to student
            \App\Models\Notification::createForUser(
                $document->user_id,
                'defense_result',
                'Defense Result Available',
                "Your {$document->document_type_label} defense has been {$request->defense_status}. " . 
                ($request->defense_notes ? "Notes: {$request->defense_notes}" : ""),
                [
                    'document_id' => $document->id,
                    'defense_status' => $request->defense_status,
                    'defense_grade' => $request->defense_grade,
                    'defense_notes' => $request->defense_notes,
                    'url' => route('student.thesis.show', $document->id)
                ],
                'ThesisDocument',
                $document->id,
                'high'
            );

            return response()->json([
                'success' => true,
                'message' => 'Defense status updated successfully',
                'defense_status' => $request->defense_status,
                'defense_grade' => $request->defense_grade
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update defense status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark proposal as defended
     */
    public function markProposalDefended(Request $request, ThesisDocument $document)
    {
        $this->ensureUserIsAdmin();

        $request->validate([
            'defense_notes' => 'nullable|string|max:1000',
            'defense_grade' => 'nullable|numeric|min:0|max:100'
        ]);

        try {
            // Update document with defense completion
            $document->update([
                'defense_status' => 'completed',
                'defense_notes' => $request->defense_notes,
                'defense_grade' => $request->defense_grade,
                'defense_confirmed_by' => Auth::id(),
                'defense_confirmed_at' => now(),
                'status' => 'approved'
            ]);

            // Log the activity
            \App\Models\ActivityLog::logActivity(
                'proposal_defended',
                'defense_confirmed',
                $document,
                null, // old values
                [
                    'document_id' => $document->id,
                    'defense_grade' => $request->defense_grade,
                    'defense_notes' => $request->defense_notes,
                    'defense_status' => $request->defense_status
                ], // new values
                [
                    'defense_confirmed_by' => Auth::id(),
                    'defense_confirmed_at' => now()
                ], // metadata
                'Defense confirmed by admin',
                Auth::id()
            );

            // Send notification to student
            \App\Models\Notification::createForUser(
                $document->user_id,
                'proposal_defended',
                'Proposal Defense Completed',
                "Your proposal defense has been completed successfully. " . 
                ($request->defense_notes ? "Notes: {$request->defense_notes}" : ""),
                [
                    'document_id' => $document->id,
                    'defense_grade' => $request->defense_grade,
                    'defense_notes' => $request->defense_notes,
                    'url' => route('student.thesis.show', $document->id)
                ],
                'ThesisDocument',
                $document->id,
                'high'
            );

            return response()->json([
                'success' => true,
                'message' => 'Proposal marked as defended successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark proposal as defended: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dashboard statistics for admin
     */
    public function dashboardStats()
    {
        $this->ensureUserIsAdmin();
        
        return [
            'total_submissions' => AcademicForm::count() + ThesisDocument::count(),
            'pending_submissions' => AcademicForm::where('status', 'pending')->count() + 
                                   ThesisDocument::where('status', 'pending')->count(),
            'approved_submissions' => AcademicForm::where('status', 'approved')->count() + 
                                    ThesisDocument::where('status', 'approved')->count(),
            'total_users' => User::count(),
            'active_students' => User::where('role', 'student')->count(),
            'active_faculty' => User::where('role', 'faculty')->count(),
            'recent_forms' => AcademicForm::with(['user'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'recent_documents' => ThesisDocument::with(['user'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];
    }
}
