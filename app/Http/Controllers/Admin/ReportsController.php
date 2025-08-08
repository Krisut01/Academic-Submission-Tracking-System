<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicForm;
use App\Models\ThesisDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
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
     * Display the reports dashboard
     */
    public function index()
    {
        $this->ensureUserIsAdmin();
        
        // Get summary statistics
        $stats = $this->getSystemStats();
        
        return view('admin.reports.index', compact('stats'));
    }

    /**
     * Submission Rates Report
     */
    public function submissionRates(Request $request)
    {
        $this->ensureUserIsAdmin();
        
        $period = $request->get('period', 'monthly');
        $year = $request->get('year', now()->year);
        
        $data = $this->calculateSubmissionRates($period, $year);
        
        if ($request->ajax()) {
            return response()->json($data);
        }
        
        return view('admin.reports.submission-rates', compact('data', 'period', 'year'));
    }

    /**
     * Overdue Documents Report
     */
    public function overdueDocuments(Request $request)
    {
        $this->ensureUserIsAdmin();
        
        $overdueThreshold = $request->get('days', 5);
        
        $overdueDate = now()->subDays($overdueThreshold);
        
        $overdueForms = AcademicForm::with(['user'])
            ->where('status', 'pending')
            ->where('submission_date', '<=', $overdueDate)
            ->orderBy('submission_date')
            ->get();
        
        $overdueThesis = ThesisDocument::with(['user'])
            ->where('status', 'pending')
            ->where('submission_date', '<=', $overdueDate)
            ->orderBy('submission_date')
            ->get();
        
        $stats = [
            'total_overdue' => $overdueForms->count() + $overdueThesis->count(),
            'overdue_forms' => $overdueForms->count(),
            'overdue_thesis' => $overdueThesis->count(),
            'threshold_days' => $overdueThreshold,
        ];
        
        return view('admin.reports.overdue-documents', compact(
            'overdueForms', 'overdueThesis', 'stats', 'overdueThreshold'
        ));
    }

    /**
     * Approval Status Trends Report
     */
    public function approvalTrends(Request $request)
    {
        $this->ensureUserIsAdmin();
        
        $period = $request->get('period', 'last_6_months');
        $type = $request->get('type', 'all');
        
        $data = $this->calculateApprovalTrends($period, $type);
        
        if ($request->ajax()) {
            return response()->json($data);
        }
        
        return view('admin.reports.approval-trends', compact('data', 'period', 'type'));
    }

    /**
     * User Activity Report
     */
    public function userActivity(Request $request)
    {
        $this->ensureUserIsAdmin();
        
        $role = $request->get('role', 'all');
        $period = $request->get('period', 'last_30_days');
        
        $data = $this->calculateUserActivity($role, $period);
        
        return view('admin.reports.user-activity', compact('data', 'role', 'period'));
    }

    /**
     * Faculty Performance Report
     */
    public function facultyPerformance(Request $request)
    {
        $this->ensureUserIsAdmin();
        
        $period = $request->get('period', 'last_3_months');
        
        $data = $this->calculateFacultyPerformance($period);
        
        return view('admin.reports.faculty-performance', compact('data', 'period'));
    }

    /**
     * Export comprehensive report as PDF
     */
    public function exportPDF(Request $request)
    {
        $this->ensureUserIsAdmin();
        
        $reportType = $request->get('type', 'comprehensive');
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        
        $data = $this->generateComprehensiveReport($dateFrom, $dateTo);
        
        // In a real application, you would use a PDF library like DomPDF or wkhtmltopdf
        // For now, we'll return a simple response
        return response()->json([
            'message' => 'PDF generation would be implemented here',
            'data' => $data
        ]);
    }

    /**
     * Export report data as CSV
     */
    public function exportCSV(Request $request)
    {
        $this->ensureUserIsAdmin();
        
        $reportType = $request->get('type', 'submissions');
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        
        switch ($reportType) {
            case 'submissions':
                return $this->exportSubmissionsCSV($dateFrom, $dateTo);
            case 'users':
                return $this->exportUsersCSV();
            case 'overdue':
                return $this->exportOverdueCSV();
            default:
                return $this->exportComprehensiveCSV($dateFrom, $dateTo);
        }
    }

    /**
     * Calculate submission rates
     */
    private function calculateSubmissionRates($period, $year)
    {
        $query = AcademicForm::query()
            ->selectRaw('DATE_FORMAT(submission_date, "%Y-%m") as period, COUNT(*) as count')
            ->whereYear('submission_date', $year);
        
        if ($period === 'weekly') {
            $query->selectRaw('WEEK(submission_date) as period, COUNT(*) as count');
        } elseif ($period === 'daily') {
            $query->selectRaw('DATE(submission_date) as period, COUNT(*) as count');
        }
        
        $forms = $query->groupBy('period')->pluck('count', 'period')->toArray();
        
        $thesisQuery = ThesisDocument::query()
            ->selectRaw('DATE_FORMAT(submission_date, "%Y-%m") as period, COUNT(*) as count')
            ->whereYear('submission_date', $year);
        
        if ($period === 'weekly') {
            $thesisQuery->selectRaw('WEEK(submission_date) as period, COUNT(*) as count');
        } elseif ($period === 'daily') {
            $thesisQuery->selectRaw('DATE(submission_date) as period, COUNT(*) as count');
        }
        
        $thesis = $thesisQuery->groupBy('period')->pluck('count', 'period')->toArray();
        
        return [
            'forms' => $forms,
            'thesis' => $thesis,
            'period' => $period,
            'year' => $year
        ];
    }

    /**
     * Calculate approval trends
     */
    private function calculateApprovalTrends($period, $type)
    {
        $months = 6;
        if ($period === 'last_year') $months = 12;
        if ($period === 'last_3_months') $months = 3;
        
        $startDate = now()->subMonths($months);
        
        $data = [];
        
        if ($type === 'all' || $type === 'forms') {
            $forms = AcademicForm::selectRaw('
                DATE_FORMAT(submission_date, "%Y-%m") as month,
                status,
                COUNT(*) as count
            ')
            ->where('submission_date', '>=', $startDate)
            ->groupBy('month', 'status')
            ->get()
            ->groupBy('month');
            
            $data['forms'] = $forms;
        }
        
        if ($type === 'all' || $type === 'thesis') {
            $thesis = ThesisDocument::selectRaw('
                DATE_FORMAT(submission_date, "%Y-%m") as month,
                status,
                COUNT(*) as count
            ')
            ->where('submission_date', '>=', $startDate)
            ->groupBy('month', 'status')
            ->get()
            ->groupBy('month');
            
            $data['thesis'] = $thesis;
        }
        
        return $data;
    }

    /**
     * Calculate user activity
     */
    private function calculateUserActivity($role, $period)
    {
        $days = 30;
        if ($period === 'last_7_days') $days = 7;
        if ($period === 'last_90_days') $days = 90;
        
        $startDate = now()->subDays($days);
        
        $query = User::query();
        
        if ($role !== 'all') {
            $query->where('role', $role);
        }
        
        $users = $query->withCount([
            'academicForms as forms_count' => function ($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate);
            },
            'thesisDocuments as thesis_count' => function ($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate);
            }
        ])->get();
        
        return [
            'users' => $users,
            'period' => $period,
            'role' => $role,
            'total_active' => $users->where(function($user) {
                return $user->forms_count > 0 || $user->thesis_count > 0;
            })->count()
        ];
    }

    /**
     * Calculate faculty performance
     */
    private function calculateFacultyPerformance($period)
    {
        $months = 3;
        if ($period === 'last_6_months') $months = 6;
        if ($period === 'last_year') $months = 12;
        
        $startDate = now()->subMonths($months);
        
        $faculty = User::where('role', 'faculty')
            ->withCount([
                'reviewedForms as reviewed_forms_count' => function ($q) use ($startDate) {
                    $q->where('reviewed_at', '>=', $startDate);
                },
                'reviewedThesis as reviewed_thesis_count' => function ($q) use ($startDate) {
                    $q->where('reviewed_at', '>=', $startDate);
                }
            ])
            ->get();
        
        // Calculate average review time for each faculty
        foreach ($faculty as $member) {
            $reviewTimes = collect();
            
            // Get form review times
            $forms = AcademicForm::where('reviewed_by', $member->id)
                ->where('reviewed_at', '>=', $startDate)
                ->whereNotNull('reviewed_at')
                ->get();
            
            foreach ($forms as $form) {
                $reviewTimes->push($form->submission_date->diffInDays($form->reviewed_at));
            }
            
            // Get thesis review times
            $thesis = ThesisDocument::where('reviewed_by', $member->id)
                ->where('reviewed_at', '>=', $startDate)
                ->whereNotNull('reviewed_at')
                ->get();
            
            foreach ($thesis as $doc) {
                $reviewTimes->push($doc->submission_date->diffInDays($doc->reviewed_at));
            }
            
            $member->avg_review_time = $reviewTimes->average() ?? 0;
            $member->total_reviews = $member->reviewed_forms_count + $member->reviewed_thesis_count;
        }
        
        return [
            'faculty' => $faculty,
            'period' => $period
        ];
    }

    /**
     * Get system statistics
     */
    private function getSystemStats()
    {
        return [
            'total_submissions' => AcademicForm::count() + ThesisDocument::count(),
            'pending_submissions' => AcademicForm::where('status', 'pending')->count() + 
                                   ThesisDocument::where('status', 'pending')->count(),
            'approved_submissions' => AcademicForm::where('status', 'approved')->count() + 
                                    ThesisDocument::where('status', 'approved')->count(),
            'total_users' => User::count(),
            'active_students' => User::where('role', 'student')->count(),
            'active_faculty' => User::where('role', 'faculty')->count(),
            'this_month_submissions' => AcademicForm::whereMonth('created_at', now()->month)->count() + 
                                      ThesisDocument::whereMonth('created_at', now()->month)->count(),
        ];
    }

    /**
     * Export submissions data as CSV
     */
    private function exportSubmissionsCSV($dateFrom, $dateTo)
    {
        $forms = AcademicForm::with(['user'])
            ->whereBetween('submission_date', [$dateFrom, $dateTo])
            ->get();
        
        $documents = ThesisDocument::with(['user'])
            ->whereBetween('submission_date', [$dateFrom, $dateTo])
            ->get();
        
        $filename = 'submissions_report_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($forms, $documents) {
            $handle = fopen('php://output', 'w');
            
            fputcsv($handle, ['Type', 'ID', 'Student Name', 'Title', 'Status', 'Submission Date']);
            
            foreach ($forms as $form) {
                fputcsv($handle, [
                    'Academic Form',
                    $form->id,
                    $form->user->name,
                    $form->title,
                    ucfirst($form->status),
                    $form->submission_date->format('Y-m-d'),
                ]);
            }
            
            foreach ($documents as $document) {
                fputcsv($handle, [
                    'Thesis Document',
                    $document->id,
                    $document->user->name,
                    $document->title,
                    ucfirst(str_replace('_', ' ', $document->status)),
                    $document->submission_date->format('Y-m-d'),
                ]);
            }
            
            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export users data as CSV
     */
    private function exportUsersCSV()
    {
        $users = User::withTrashed()->get();

        $filename = 'users_report_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($users) {
            $handle = fopen('php://output', 'w');
            
            fputcsv($handle, ['ID', 'Name', 'Email', 'Role', 'Status', 'Created At']);
            
            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    ucfirst($user->role),
                    $user->deleted_at ? 'Inactive' : 'Active',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export overdue documents as CSV
     */
    private function exportOverdueCSV()
    {
        $overdueDate = now()->subDays(5);
        
        $overdueForms = AcademicForm::with(['user'])
            ->where('status', 'pending')
            ->where('submission_date', '<=', $overdueDate)
            ->get();
        
        $overdueThesis = ThesisDocument::with(['user'])
            ->where('status', 'pending')
            ->where('submission_date', '<=', $overdueDate)
            ->get();

        $filename = 'overdue_report_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($overdueForms, $overdueThesis) {
            $handle = fopen('php://output', 'w');
            
            fputcsv($handle, ['Type', 'ID', 'Student Name', 'Title', 'Days Overdue', 'Submission Date']);
            
            foreach ($overdueForms as $form) {
                fputcsv($handle, [
                    'Academic Form',
                    $form->id,
                    $form->user->name,
                    $form->title,
                    $form->submission_date->diffInDays(now()),
                    $form->submission_date->format('Y-m-d'),
                ]);
            }
            
            foreach ($overdueThesis as $document) {
                fputcsv($handle, [
                    'Thesis Document',
                    $document->id,
                    $document->user->name,
                    $document->title,
                    $document->submission_date->diffInDays(now()),
                    $document->submission_date->format('Y-m-d'),
                ]);
            }
            
            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Generate comprehensive report data
     */
    private function generateComprehensiveReport($dateFrom, $dateTo)
    {
        return [
            'period' => ['from' => $dateFrom, 'to' => $dateTo],
            'submissions' => [
                'total' => AcademicForm::whereBetween('submission_date', [$dateFrom, $dateTo])->count() +
                          ThesisDocument::whereBetween('submission_date', [$dateFrom, $dateTo])->count(),
                'forms' => AcademicForm::whereBetween('submission_date', [$dateFrom, $dateTo])->count(),
                'thesis' => ThesisDocument::whereBetween('submission_date', [$dateFrom, $dateTo])->count(),
            ],
            'status_breakdown' => [
                'pending' => AcademicForm::where('status', 'pending')
                    ->whereBetween('submission_date', [$dateFrom, $dateTo])->count() +
                    ThesisDocument::where('status', 'pending')
                    ->whereBetween('submission_date', [$dateFrom, $dateTo])->count(),
                'approved' => AcademicForm::where('status', 'approved')
                    ->whereBetween('submission_date', [$dateFrom, $dateTo])->count() +
                    ThesisDocument::where('status', 'approved')
                    ->whereBetween('submission_date', [$dateFrom, $dateTo])->count(),
            ],
            'user_stats' => [
                'total_users' => User::count(),
                'students' => User::where('role', 'student')->count(),
                'faculty' => User::where('role', 'faculty')->count(),
            ]
        ];
    }

    /**
     * Export comprehensive report as CSV
     */
    private function exportComprehensiveCSV($dateFrom, $dateTo)
    {
        $data = $this->generateComprehensiveReport($dateFrom, $dateTo);
        
        $filename = 'comprehensive_report_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($data) {
            $handle = fopen('php://output', 'w');
            
            fputcsv($handle, ['Report Type', 'Comprehensive System Report']);
            fputcsv($handle, ['Generated At', now()->format('Y-m-d H:i:s')]);
            fputcsv($handle, ['Period', $data['period']['from'] . ' to ' . $data['period']['to']]);
            fputcsv($handle, []);
            
            fputcsv($handle, ['Metric', 'Value']);
            fputcsv($handle, ['Total Submissions', $data['submissions']['total']]);
            fputcsv($handle, ['Academic Forms', $data['submissions']['forms']]);
            fputcsv($handle, ['Thesis Documents', $data['submissions']['thesis']]);
            fputcsv($handle, ['Pending Items', $data['status_breakdown']['pending']]);
            fputcsv($handle, ['Approved Items', $data['status_breakdown']['approved']]);
            fputcsv($handle, ['Total Users', $data['user_stats']['total_users']]);
            fputcsv($handle, ['Students', $data['user_stats']['students']]);
            fputcsv($handle, ['Faculty', $data['user_stats']['faculty']]);
            
            fclose($handle);
        }, 200, $headers);
    }
}
