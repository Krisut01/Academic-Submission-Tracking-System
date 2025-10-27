<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📊 {{ __('Approval Trends Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.reports') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Reports
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Report Filters</h3>
                </div>
                <div class="px-6 py-5">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Period</label>
                            <select name="period" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="last_30_days" {{ $period === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="last_3_months" {{ $period === 'last_3_months' ? 'selected' : '' }}>Last 3 Months</option>
                                <option value="last_6_months" {{ $period === 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                <option value="last_year" {{ $period === 'last_year' ? 'selected' : '' }}>Last Year</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Document Type</label>
                            <select name="type" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types</option>
                                <option value="academic_forms" {{ $type === 'academic_forms' ? 'selected' : '' }}>Academic Forms</option>
                                <option value="thesis_documents" {{ $type === 'thesis_documents' ? 'selected' : '' }}>Thesis Documents</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-gray-900 dark:text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl p-6 border border-green-200/50 dark:border-green-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Approval Rate</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['approval_rate'] ?? 0 }}%</p>
                        </div>
                        <div class="p-3 bg-green-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200/50 dark:border-blue-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Submissions</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['total_submissions'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-blue-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-2xl p-6 border border-yellow-200/50 dark:border-yellow-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Pending Review</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['pending_review'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-yellow-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-2xl p-6 border border-purple-200/50 dark:border-purple-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Avg Processing Time</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['avg_processing_time'] ?? 0 }} days</p>
                        </div>
                        <div class="p-3 bg-purple-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Approval Trends</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Visual representation of approval rates over time</p>
                </div>
                <div class="px-6 py-8">
                    <div class="chart-container">
                        <canvas id="approvalTrendsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Status Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Status Distribution -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Status Distribution</h3>
                    </div>
                    <div class="px-6 py-5">
                        <div class="space-y-4">
                            @if(isset($data['status_breakdown']))
                                @foreach($data['status_breakdown'] as $status => $count)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-3 h-3 rounded-full
                                                @if($status === 'approved') bg-green-500
                                                @elseif($status === 'pending') bg-yellow-500
                                                @elseif($status === 'under_review') bg-blue-500
                                                @else bg-red-500 @endif">
                                            </div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white capitalize">
                                                {{ str_replace('_', ' ', $status) }}
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $count }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                ({{ $data['total_submissions'] > 0 ? round(($count / $data['total_submissions']) * 100, 1) : 0 }}%)
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-8">
                                    <p class="text-gray-500 dark:text-gray-400">No status data available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Processing Time Analysis -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Processing Time Analysis</h3>
                    </div>
                    <div class="px-6 py-5">
                        <div class="space-y-4">
                            @if(isset($data['processing_times']))
                                @foreach($data['processing_times'] as $range => $count)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $range }}</span>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $count }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">documents</span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-8">
                                    <p class="text-gray-500 dark:text-gray-400">No processing time data available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Data Table -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Approval Trends Data</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approved</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rejected</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approval Rate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Avg Processing Time</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if(isset($data['trends']) && count($data['trends']) > 0)
                                @foreach($data['trends'] as $trend)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $trend['period'] }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $trend['submitted'] ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $trend['approved'] ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $trend['rejected'] ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ ($trend['approval_rate'] ?? 0) >= 80 ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 
                                                   (($trend['approval_rate'] ?? 0) >= 60 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200') }}">
                                                {{ $trend['approval_rate'] ?? 0 }}%
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $trend['avg_processing_time'] ?? 0 }} days
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No data available</h3>
                                        <p class="text-gray-600 dark:text-gray-400">No approval trends data found for the selected period.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(249, 250, 251, 0.98) 100%);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 2px solid rgba(209, 213, 219, 0.7);
        }
        
        .dark .chart-container {
            background: linear-gradient(135deg, rgba(17, 24, 39, 0.95) 0%, rgba(31, 41, 55, 0.9) 100%);
            border: 2px solid rgba(75, 85, 99, 0.5);
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('approvalTrendsChart').getContext('2d');
            
            // Detect dark mode
            const hasDarkClass = document.documentElement.classList.contains('dark');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const bodyBg = window.getComputedStyle(document.body).backgroundColor;
            const isLightBackground = bodyBg.includes('255, 255, 255') || bodyBg.includes('rgb(255, 255, 255)') || 
                                    bodyBg.includes('rgba(255, 255, 255') || bodyBg === 'white';
            
            const isDarkMode = hasDarkClass || (prefersDark && !isLightBackground);
            
            // Prepare chart data
            const trends = @json($data['trends'] ?? []);
            
            if (trends.length === 0) {
                // Show no data message
                ctx.font = '16px Arial';
                ctx.fillStyle = '#000000'; // Always black for visibility
                ctx.textAlign = 'center';
                ctx.fillText('No approval trends data available', ctx.canvas.width / 2, ctx.canvas.height / 2);
                return;
            }
            
            // Define colors - text always black for visibility
            const colors = {
                approved: {
                    border: isDarkMode ? '#10B981' : '#059669',
                    background: isDarkMode ? 'rgba(16, 185, 129, 0.25)' : 'rgba(5, 150, 105, 0.15)',
                    point: isDarkMode ? '#10B981' : '#059669'
                },
                rejected: {
                    border: isDarkMode ? '#EF4444' : '#DC2626',
                    background: isDarkMode ? 'rgba(239, 68, 68, 0.25)' : 'rgba(220, 38, 38, 0.15)',
                    point: isDarkMode ? '#EF4444' : '#DC2626'
                },
                submitted: {
                    border: isDarkMode ? '#3B82F6' : '#2563EB',
                    background: isDarkMode ? 'rgba(59, 130, 246, 0.25)' : 'rgba(37, 99, 235, 0.15)',
                    point: isDarkMode ? '#3B82F6' : '#2563EB'
                },
                text: '#000000', // Always black for visibility
                textSecondary: '#000000', // Always black for visibility
                grid: isDarkMode ? 'rgba(75, 85, 99, 0.6)' : 'rgba(107, 114, 128, 0.3)',
                ticks: '#000000' // Always black for visibility
            };
            
            // Extract data for chart
            const labels = trends.map(trend => trend.period);
            const submittedData = trends.map(trend => trend.submitted);
            const approvedData = trends.map(trend => trend.approved);
            const rejectedData = trends.map(trend => trend.rejected);
            
            // Create the chart
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Submitted',
                            data: submittedData,
                            borderColor: colors.submitted.border,
                            backgroundColor: colors.submitted.background,
                            pointBackgroundColor: colors.submitted.point,
                            pointBorderColor: colors.submitted.point,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Approved',
                            data: approvedData,
                            borderColor: colors.approved.border,
                            backgroundColor: colors.approved.background,
                            pointBackgroundColor: colors.approved.point,
                            pointBorderColor: colors.approved.point,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Rejected',
                            data: rejectedData,
                            borderColor: colors.rejected.border,
                            backgroundColor: colors.rejected.background,
                            pointBackgroundColor: colors.rejected.point,
                            pointBorderColor: colors.rejected.point,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Approval Trends Over Time',
                            font: {
                                size: 18,
                                weight: 'bold'
                            },
                            color: colors.text
                        },
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 14,
                                    weight: '500'
                                },
                                color: colors.text,
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)', // Always white background for black text
                            titleColor: '#000000', // Always black
                            bodyColor: '#000000', // Always black
                            borderColor: 'rgba(209, 213, 219, 0.3)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                afterLabel: function(context) {
                                    const trend = trends[context.dataIndex];
                                    const approvalRate = trend.approval_rate || 0;
                                    return `Approval Rate: ${approvalRate}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Time Period',
                                font: {
                                    size: 14,
                                    weight: '600'
                                },
                                color: colors.text
                            },
                            grid: {
                                color: colors.grid,
                                drawBorder: false
                            },
                            ticks: {
                                color: colors.ticks,
                                font: {
                                    size: 12,
                                    weight: '500'
                                }
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Number of Submissions',
                                font: {
                                    size: 14,
                                    weight: '600'
                                },
                                color: colors.text
                            },
                            grid: {
                                color: colors.grid,
                                drawBorder: false
                            },
                            ticks: {
                                color: colors.ticks,
                                font: {
                                    size: 12,
                                    weight: '500'
                                },
                                beginAtZero: true,
                                stepSize: 1
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    }
                }
            });
        });
    </script>
</x-app-layout>
