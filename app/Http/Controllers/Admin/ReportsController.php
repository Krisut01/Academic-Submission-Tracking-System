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
        
        // Generate additional data for the PDF report
        $additionalData = [
            'approval_trends' => $this->calculateApprovalTrends('last_6_months', 'all'),
            'submission_rates' => $this->calculateSubmissionRates('monthly', now()->year),
            'user_activity' => $this->calculateUserActivity('all', 'last_30_days'),
            'faculty_performance' => $this->calculateFacultyPerformance('last_3_months'),
            'overdue_documents' => $this->getOverdueDocumentsData(),
        ];
        
        // Return HTML view that can be printed as PDF
        return view('admin.reports.pdf-export', compact('data', 'additionalData', 'reportType', 'dateFrom', 'dateTo'));
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
        // Determine the date format based on period
        $dateFormat = '%Y-%m'; // Monthly by default
        $groupByFormat = 'Y-m';
        
        if ($period === 'weekly') {
            $dateFormat = '%Y-%u'; // Year-Week
            $groupByFormat = 'Y-W';
        } elseif ($period === 'daily') {
            $dateFormat = '%Y-%m-%d'; // Year-Month-Day
            $groupByFormat = 'Y-m-d';
        } elseif ($period === 'quarterly') {
            $dateFormat = '%Y-%m'; // Year-Month for quarterly calculation
            $groupByFormat = 'Y-m';
        }
        
        // Get academic forms data
        $formsQuery = AcademicForm::query()
            ->selectRaw("DATE_FORMAT(submission_date, '{$dateFormat}') as period, COUNT(*) as count")
            ->whereYear('submission_date', $year);
        
        $forms = $formsQuery->groupBy('period')->pluck('count', 'period')->toArray();
        
        // Get thesis documents data
        $thesisQuery = ThesisDocument::query()
            ->selectRaw("DATE_FORMAT(submission_date, '{$dateFormat}') as period, COUNT(*) as count")
            ->whereYear('submission_date', $year);
        
        $thesis = $thesisQuery->groupBy('period')->pluck('count', 'period')->toArray();
        
        // Calculate totals
        $totalForms = array_sum($forms);
        $totalThesis = array_sum($thesis);
        $totalSubmissions = $totalForms + $totalThesis;
        
        // Calculate average monthly (convert to monthly equivalent)
        $monthsInPeriod = 12; // Default for yearly
        if ($period === 'quarterly') $monthsInPeriod = 3;
        elseif ($period === 'weekly') $monthsInPeriod = 52/12; // Convert weeks to months
        elseif ($period === 'daily') $monthsInPeriod = 365/12; // Convert days to months
        
        $averageMonthly = $monthsInPeriod > 0 ? round($totalSubmissions / $monthsInPeriod, 1) : 0;
        
        // Create periods array for detailed table
        $allPeriods = array_unique(array_merge(array_keys($forms), array_keys($thesis)));
        sort($allPeriods);
        
        $periods = [];
        $previousTotal = 0;
        
        // Handle quarterly grouping
        if ($period === 'quarterly') {
            $quarterlyData = [];
            foreach ($allPeriods as $periodKey) {
                $formsCount = $forms[$periodKey] ?? 0;
                $thesisCount = $thesis[$periodKey] ?? 0;
                $total = $formsCount + $thesisCount;
                
                // Extract year and month
                $year = substr($periodKey, 0, 4);
                $month = (int)substr($periodKey, 5, 2);
                $quarter = ceil($month / 3);
                $quarterKey = "{$year}-Q{$quarter}";
                
                if (!isset($quarterlyData[$quarterKey])) {
                    $quarterlyData[$quarterKey] = [
                        'academic_forms' => 0,
                        'thesis_documents' => 0,
                        'total' => 0
                    ];
                }
                
                $quarterlyData[$quarterKey]['academic_forms'] += $formsCount;
                $quarterlyData[$quarterKey]['thesis_documents'] += $thesisCount;
                $quarterlyData[$quarterKey]['total'] += $total;
            }
            
            // Convert quarterly data to periods array
            foreach ($quarterlyData as $quarterKey => $data) {
                $growth = $previousTotal > 0 ? round((($data['total'] - $previousTotal) / $previousTotal) * 100, 1) : null;
                
                $periods[] = [
                    'period' => $quarterKey,
                    'label' => $quarterKey, // Will be formatted by formatPeriodLabel
                    'academic_forms' => $data['academic_forms'],
                    'thesis_documents' => $data['thesis_documents'],
                    'total' => $data['total'],
                    'growth' => $growth
                ];
                
                $previousTotal = $data['total'];
            }
        } else {
            // Regular processing for other periods
            foreach ($allPeriods as $periodKey) {
                $formsCount = $forms[$periodKey] ?? 0;
                $thesisCount = $thesis[$periodKey] ?? 0;
                $total = $formsCount + $thesisCount;
                
                // Calculate growth percentage
                $growth = $previousTotal > 0 ? round((($total - $previousTotal) / $previousTotal) * 100, 1) : null;
                
                // Format period label
                $label = $this->formatPeriodLabel($periodKey, $period);
                
                $periods[] = [
                    'period' => $periodKey,
                    'label' => $label,
                    'academic_forms' => $formsCount,
                    'thesis_documents' => $thesisCount,
                    'total' => $total,
                    'growth' => $growth
                ];
                
                $previousTotal = $total;
            }
        }
        
        return [
            // Summary statistics
            'total_submissions' => $totalSubmissions,
            'academic_forms' => $totalForms,
            'thesis_documents' => $totalThesis,
            'average_monthly' => $averageMonthly,
            
            // Detailed data
            'periods' => $periods,
            
            // Raw data for charts (if needed)
            'forms' => $forms,
            'thesis' => $thesis,
            'period' => $period,
            'year' => $year
        ];
    }
    
    /**
     * Format period label for display
     */
    private function formatPeriodLabel($periodKey, $period)
    {
        switch ($period) {
            case 'monthly':
                return date('F Y', strtotime($periodKey . '-01'));
            case 'quarterly':
                // Handle both old format (2025-q) and new format (2025-Q4)
                if (strpos($periodKey, 'Q') !== false) {
                    return $periodKey; // Already formatted as "2025-Q4"
                } else {
                    $year = substr($periodKey, 0, 4);
                    $quarter = substr($periodKey, 5);
                    // Handle the case where quarter might be 'q' instead of a number
                    if ($quarter === 'q') {
                        // Calculate quarter from month
                        $month = (int)substr($periodKey, 5, 2);
                        $quarter = ceil($month / 3);
                    }
                    return "Q{$quarter} {$year}";
                }
            case 'weekly':
                $year = substr($periodKey, 0, 4);
                $week = substr($periodKey, 5);
                return "Week {$week}, {$year}";
            case 'daily':
                return date('M j, Y', strtotime($periodKey));
            default:
                return $periodKey;
        }
    }

    /**
     * Calculate approval trends
     */
    private function calculateApprovalTrends($period, $type)
    {
        $months = 6;
        if ($period === 'last_year') $months = 12;
        if ($period === 'last_3_months') $months = 3;
        if ($period === 'last_30_days') $months = 1;
        
        $startDate = now()->subMonths($months);
        
        // Get all submissions in the period
        $allSubmissions = collect();
        
        if ($type === 'all' || $type === 'academic_forms') {
            $forms = AcademicForm::where('submission_date', '>=', $startDate)->get();
            $allSubmissions = $allSubmissions->merge($forms);
        }
        
        if ($type === 'all' || $type === 'thesis_documents') {
            $thesis = ThesisDocument::where('submission_date', '>=', $startDate)->get();
            $allSubmissions = $allSubmissions->merge($thesis);
        }
        
        // Calculate overall statistics
        $totalSubmissions = $allSubmissions->count();
        $approvedSubmissions = $allSubmissions->where('status', 'approved')->count();
        $pendingSubmissions = $allSubmissions->where('status', 'pending')->count();
        $underReviewSubmissions = $allSubmissions->where('status', 'under_review')->count();
        $rejectedSubmissions = $allSubmissions->where('status', 'rejected')->count();
        
        $approvalRate = $totalSubmissions > 0 ? round(($approvedSubmissions / $totalSubmissions) * 100, 1) : 0;
        
        // Calculate average processing time
        $processedSubmissions = $allSubmissions->whereIn('status', ['approved', 'rejected'])
            ->whereNotNull('reviewed_at');
        
        $avgProcessingTime = 0;
        if ($processedSubmissions->count() > 0) {
            $totalDays = $processedSubmissions->sum(function($submission) {
                return $submission->submission_date && $submission->reviewed_at ? 
                    $submission->submission_date->diffInDays($submission->reviewed_at) : 0;
            });
            $avgProcessingTime = round($totalDays / $processedSubmissions->count(), 1);
        }
        
        // Status breakdown
        $statusBreakdown = [
            'approved' => $approvedSubmissions,
            'pending' => $pendingSubmissions,
            'under_review' => $underReviewSubmissions,
            'rejected' => $rejectedSubmissions
        ];
        
        // Processing time analysis
        $processingTimes = [
            '0-1 days' => $processedSubmissions->filter(function($submission) {
                return $submission->submission_date && $submission->reviewed_at ? 
                    $submission->submission_date->diffInDays($submission->reviewed_at) <= 1 : false;
            })->count(),
            '2-3 days' => $processedSubmissions->filter(function($submission) {
                $days = $submission->submission_date && $submission->reviewed_at ? 
                    $submission->submission_date->diffInDays($submission->reviewed_at) : 0;
                return $days >= 2 && $days <= 3;
            })->count(),
            '4-7 days' => $processedSubmissions->filter(function($submission) {
                $days = $submission->submission_date && $submission->reviewed_at ? 
                    $submission->submission_date->diffInDays($submission->reviewed_at) : 0;
                return $days >= 4 && $days <= 7;
            })->count(),
            '8+ days' => $processedSubmissions->filter(function($submission) {
                return $submission->submission_date && $submission->reviewed_at ? 
                    $submission->submission_date->diffInDays($submission->reviewed_at) >= 8 : false;
            })->count()
        ];
        
        // Monthly trends
        $trends = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();
            
            $monthSubmissions = $allSubmissions->filter(function($submission) use ($monthStart, $monthEnd) {
                return $submission->submission_date->between($monthStart, $monthEnd);
            });
            
            $monthApproved = $monthSubmissions->where('status', 'approved')->count();
            $monthRejected = $monthSubmissions->where('status', 'rejected')->count();
            $monthSubmitted = $monthSubmissions->count();
            $monthApprovalRate = $monthSubmitted > 0 ? round(($monthApproved / $monthSubmitted) * 100, 1) : 0;
            
            // Calculate average processing time for this month
            $monthProcessed = $monthSubmissions->whereIn('status', ['approved', 'rejected'])
                ->whereNotNull('reviewed_at');
            $monthAvgProcessingTime = 0;
            if ($monthProcessed->count() > 0) {
                $monthTotalDays = $monthProcessed->sum(function($submission) {
                    return $submission->submission_date && $submission->reviewed_at ? 
                    $submission->submission_date->diffInDays($submission->reviewed_at) : 0;
                });
                $monthAvgProcessingTime = round($monthTotalDays / $monthProcessed->count(), 1);
            }
            
            $trends[] = [
                'period' => $monthStart->format('M Y'),
                'submitted' => $monthSubmitted,
                'approved' => $monthApproved,
                'rejected' => $monthRejected,
                'approval_rate' => $monthApprovalRate,
                'avg_processing_time' => $monthAvgProcessingTime
            ];
        }
        
        return [
            'approval_rate' => $approvalRate,
            'total_submissions' => $totalSubmissions,
            'pending_review' => $pendingSubmissions + $underReviewSubmissions,
            'avg_processing_time' => $avgProcessingTime,
            'status_breakdown' => $statusBreakdown,
            'processing_times' => $processingTimes,
            'trends' => $trends,
            'period' => $period,
            'type' => $type
        ];
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
                if ($form->submission_date && $form->reviewed_at) {
                    $reviewTimes->push($form->submission_date->diffInDays($form->reviewed_at));
                }
            }
            
            // Get thesis review times
            $thesis = ThesisDocument::where('reviewed_by', $member->id)
                ->where('reviewed_at', '>=', $startDate)
                ->whereNotNull('reviewed_at')
                ->get();
            
            foreach ($thesis as $doc) {
                if ($doc->submission_date && $doc->reviewed_at) {
                    $reviewTimes->push($doc->submission_date->diffInDays($doc->reviewed_at));
                }
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
        // Get current counts for real-time data
        $totalForms = AcademicForm::count();
        $totalDocuments = ThesisDocument::count();
        $pendingForms = AcademicForm::where('status', 'pending')->count();
        $pendingDocuments = ThesisDocument::where('status', 'pending')->count();
        $approvedForms = AcademicForm::where('status', 'approved')->count();
        $approvedDocuments = ThesisDocument::where('status', 'approved')->count();
        $underReviewForms = AcademicForm::where('status', 'under_review')->count();
        $underReviewDocuments = ThesisDocument::where('status', 'under_review')->count();
        
        // Calculate overdue items (more than 5 days old)
        $overdueDate = now()->subDays(5);
        $overdueForms = AcademicForm::where('status', 'pending')
            ->where('submission_date', '<=', $overdueDate)
            ->count();
        $overdueDocuments = ThesisDocument::where('status', 'pending')
            ->where('submission_date', '<=', $overdueDate)
            ->count();
        
        // Get user counts
        $totalUsers = User::count();
        $students = User::where('role', 'student')->count();
        $faculty = User::where('role', 'faculty')->count();
        $admins = User::where('role', 'admin')->count();
        
        // Get active users (users who have submitted something in the last 30 days)
        $activeUsers = User::where(function($query) {
            $query->whereHas('academicForms', function($q) {
                $q->where('created_at', '>=', now()->subDays(30));
            })->orWhereHas('thesisDocuments', function($q) {
                $q->where('created_at', '>=', now()->subDays(30));
            });
        })->count();
        
        // This month's submissions
        $thisMonthForms = AcademicForm::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $thisMonthDocuments = ThesisDocument::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        return [
            // Legacy fields for backward compatibility
            'total_submissions' => $totalForms + $totalDocuments,
            'pending_submissions' => $pendingForms + $pendingDocuments,
            'approved_submissions' => $approvedForms + $approvedDocuments,
            'total_users' => $totalUsers,
            'active_students' => $students,
            'active_faculty' => $faculty,
            'this_month_submissions' => $thisMonthForms + $thisMonthDocuments,
            
            // New detailed fields for the dashboard
            'total_forms' => $totalForms,
            'total_documents' => $totalDocuments,
            'pending_forms' => $pendingForms,
            'pending_documents' => $pendingDocuments,
            'approved_forms' => $approvedForms,
            'approved_documents' => $approvedDocuments,
            'under_review_forms' => $underReviewForms,
            'under_review_documents' => $underReviewDocuments,
            'overdue_forms' => $overdueForms,
            'overdue_documents' => $overdueDocuments,
            'students' => $students,
            'faculty' => $faculty,
            'admins' => $admins,
            'active_users' => $activeUsers,
            'this_month_forms' => $thisMonthForms,
            'this_month_documents' => $thisMonthDocuments,
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
                    $form->submission_date ? $form->submission_date->format('Y-m-d') : 'N/A',
                ]);
            }
            
            foreach ($documents as $document) {
                fputcsv($handle, [
                    'Thesis Document',
                    $document->id,
                    $document->user->name,
                    $document->title,
                    ucfirst(str_replace('_', ' ', $document->status)),
                    $document->submission_date ? $document->submission_date->format('Y-m-d') : 'N/A',
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
                    $form->submission_date ? $form->submission_date->diffInDays(now()) : 0,
                    $form->submission_date ? $form->submission_date->format('Y-m-d') : 'N/A',
                ]);
            }
            
            foreach ($overdueThesis as $document) {
                fputcsv($handle, [
                    'Thesis Document',
                    $document->id,
                    $document->user->name,
                    $document->title,
                    $document->submission_date ? $document->submission_date->diffInDays(now()) : 0,
                    $document->submission_date ? $document->submission_date->format('Y-m-d') : 'N/A',
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
     * Get overdue documents data
     */
    private function getOverdueDocumentsData()
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
        
        return [
            'forms' => $overdueForms,
            'thesis' => $overdueThesis,
            'total' => $overdueForms->count() + $overdueThesis->count(),
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
