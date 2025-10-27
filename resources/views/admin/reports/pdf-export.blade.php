<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RMT Generation System - Comprehensive Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: white;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #2563eb;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .header h2 {
            color: #6b7280;
            font-size: 1.2rem;
            font-weight: normal;
        }
        
        .report-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #2563eb;
        }
        
        .report-info h3 {
            color: #2563eb;
            margin-bottom: 10px;
        }
        
        .report-info p {
            margin: 5px 0;
            color: #6b7280;
        }
        
        .section {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }
        
        .section h3 {
            color: #2563eb;
            font-size: 1.5rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #2563eb;
            text-align: center;
        }
        
        .stat-card h4 {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card .value {
            color: #2563eb;
            font-size: 2rem;
            font-weight: bold;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .table th {
            background: #f8fafc;
            color: #374151;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        
        .table tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .status-under-review {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .chart-placeholder {
            background: #f8fafc;
            border: 2px dashed #d1d5db;
            padding: 40px;
            text-align: center;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .chart-placeholder h4 {
            color: #6b7280;
            margin-bottom: 10px;
        }
        
        .chart-placeholder p {
            color: #9ca3af;
            font-size: 0.9rem;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        @media print {
            body {
                font-size: 12px;
            }
            
            .container {
                padding: 10px;
            }
            
            .section {
                page-break-inside: avoid;
            }
            
            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>RMT Generation System</h1>
            <h2>Comprehensive System Report</h2>
        </div>

        <!-- Report Information -->
        <div class="report-info">
            <h3>Report Details</h3>
            <p><strong>Generated:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</p>
            <p><strong>Period:</strong> {{ \Carbon\Carbon::parse($dateFrom)->format('F j, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('F j, Y') }}</p>
            <p><strong>Report Type:</strong> {{ ucfirst($reportType) }} Report</p>
        </div>

        <!-- Executive Summary -->
        <div class="section">
            <h3>Executive Summary</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <h4>Total Submissions</h4>
                    <div class="value">{{ $data['submissions']['total'] }}</div>
                </div>
                <div class="stat-card">
                    <h4>Academic Forms</h4>
                    <div class="value">{{ $data['submissions']['forms'] }}</div>
                </div>
                <div class="stat-card">
                    <h4>Thesis Documents</h4>
                    <div class="value">{{ $data['submissions']['thesis'] }}</div>
                </div>
                <div class="stat-card">
                    <h4>Total Users</h4>
                    <div class="value">{{ $data['user_stats']['total_users'] }}</div>
                </div>
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="section">
            <h3>Status Breakdown</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <h4>Pending Items</h4>
                    <div class="value">{{ $data['status_breakdown']['pending'] }}</div>
                </div>
                <div class="stat-card">
                    <h4>Approved Items</h4>
                    <div class="value">{{ $data['status_breakdown']['approved'] }}</div>
                </div>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="section">
            <h3>User Statistics</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <h4>Students</h4>
                    <div class="value">{{ $data['user_stats']['students'] }}</div>
                </div>
                <div class="stat-card">
                    <h4>Faculty</h4>
                    <div class="value">{{ $data['user_stats']['faculty'] }}</div>
                </div>
                <div class="stat-card">
                    <h4>Active Users</h4>
                    <div class="value">{{ $additionalData['user_activity']['total_active'] }}</div>
                </div>
                <div class="stat-card">
                    <h4>Activity Rate</h4>
                    <div class="value">
                        {{ $additionalData['user_activity']['users']->count() > 0 ? round(($additionalData['user_activity']['total_active'] / $additionalData['user_activity']['users']->count()) * 100, 1) : 0 }}%
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Trends -->
        <div class="section">
            <h3>Approval Trends (Last 6 Months)</h3>
            <div class="chart-placeholder">
                <h4>Approval Trends Chart</h4>
                <p>Visual representation of approval rates over time</p>
                <p><strong>Approval Rate:</strong> {{ $additionalData['approval_trends']['approval_rate'] }}%</p>
                <p><strong>Average Processing Time:</strong> {{ $additionalData['approval_trends']['avg_processing_time'] }} days</p>
            </div>
            
            @if(isset($additionalData['approval_trends']['trends']) && count($additionalData['approval_trends']['trends']) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Submitted</th>
                            <th>Approved</th>
                            <th>Rejected</th>
                            <th>Approval Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($additionalData['approval_trends']['trends'] as $trend)
                            <tr>
                                <td>{{ $trend['period'] }}</td>
                                <td>{{ $trend['submitted'] ?? 0 }}</td>
                                <td>{{ $trend['approved'] ?? 0 }}</td>
                                <td>{{ $trend['rejected'] ?? 0 }}</td>
                                <td>{{ $trend['approval_rate'] ?? 0 }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Faculty Performance -->
        <div class="section">
            <h3>Faculty Performance (Last 3 Months)</h3>
            @if(isset($additionalData['faculty_performance']['faculty']) && $additionalData['faculty_performance']['faculty']->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Faculty Member</th>
                            <th>Forms Reviewed</th>
                            <th>Thesis Reviewed</th>
                            <th>Total Reviews</th>
                            <th>Avg Review Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($additionalData['faculty_performance']['faculty']->sortByDesc('total_reviews') as $faculty)
                            <tr>
                                <td>{{ $faculty->name }}</td>
                                <td>{{ $faculty->reviewed_forms_count ?? 0 }}</td>
                                <td>{{ $faculty->reviewed_thesis_count ?? 0 }}</td>
                                <td>{{ $faculty->total_reviews ?? 0 }}</td>
                                <td>{{ $faculty->avg_review_time ? round($faculty->avg_review_time, 1) . ' days' : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No faculty performance data available for the selected period.</p>
            @endif
        </div>

        <!-- Overdue Documents -->
        <div class="section">
            <h3>Overdue Documents</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <h4>Total Overdue</h4>
                    <div class="value">{{ $additionalData['overdue_documents']['total'] }}</div>
                </div>
                <div class="stat-card">
                    <h4>Overdue Forms</h4>
                    <div class="value">{{ $additionalData['overdue_documents']['forms']->count() }}</div>
                </div>
                <div class="stat-card">
                    <h4>Overdue Thesis</h4>
                    <div class="value">{{ $additionalData['overdue_documents']['thesis']->count() }}</div>
                </div>
            </div>
            
            @if($additionalData['overdue_documents']['total'] > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Student</th>
                            <th>Title</th>
                            <th>Days Overdue</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($additionalData['overdue_documents']['forms'] as $form)
                            <tr>
                                <td>Academic Form</td>
                                <td>{{ $form->user->name }}</td>
                                <td>{{ $form->title }}</td>
                                <td>{{ $form->submission_date ? $form->submission_date->diffInDays(now()) : 0 }}</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                            </tr>
                        @endforeach
                        @foreach($additionalData['overdue_documents']['thesis'] as $thesis)
                            <tr>
                                <td>Thesis Document</td>
                                <td>{{ $thesis->user->name }}</td>
                                <td>{{ $thesis->title }}</td>
                                <td>{{ $thesis->submission_date ? $thesis->submission_date->diffInDays(now()) : 0 }}</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No overdue documents found.</p>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This report was generated automatically by the RMT Generation System on {{ now()->format('F j, Y \a\t g:i A') }}</p>
            <p>For questions or concerns, please contact the system administrator.</p>
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
