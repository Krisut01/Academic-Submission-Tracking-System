<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📈 {{ __('Submission Rates Report') }}
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
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Period</label>
                            <select name="period" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ $period === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Year</label>
                            <select name="year" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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

                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl p-6 border border-green-200/50 dark:border-green-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Academic Forms</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['academic_forms'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-green-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-2xl p-6 border border-yellow-200/50 dark:border-yellow-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Thesis Documents</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['thesis_documents'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-yellow-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C20.168 18.477 18.754 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-2xl p-6 border border-purple-200/50 dark:border-purple-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Average Monthly</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['average_monthly'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-purple-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Submission Trends</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Visual representation of submission rates over time</p>
                </div>
                <div class="px-6 py-8">
                    <div class="chart-container">
                        <canvas id="submissionChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity Summary</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Key insights from the selected period</p>
                </div>
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                                {{ $data['total_submissions'] ?? 0 }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Total Submissions</div>
                            <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                {{ $data['year'] ?? date('Y') }} {{ ucfirst(is_string($data['period'] ?? 'monthly') ? $data['period'] : 'monthly') }}
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2">
                                {{ $data['academic_forms'] ?? 0 }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Academic Forms</div>
                            <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                {{ $data['total_submissions'] > 0 ? round((($data['academic_forms'] ?? 0) / $data['total_submissions']) * 100, 1) : 0 }}% of total
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mb-2">
                                {{ $data['thesis_documents'] ?? 0 }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Thesis Documents</div>
                            <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                {{ $data['total_submissions'] > 0 ? round((($data['thesis_documents'] ?? 0) / $data['total_submissions']) * 100, 1) : 0 }}% of total
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Data Table -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detailed Submission Data</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Academic Forms</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thesis Documents</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Growth</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if(isset($data['periods']) && count($data['periods']) > 0)
                                @foreach($data['periods'] as $period)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $period['label'] }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $period['academic_forms'] ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $period['thesis_documents'] ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ ($period['academic_forms'] ?? 0) + ($period['thesis_documents'] ?? 0) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if(isset($period['growth']))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $period['growth'] >= 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200' }}">
                                                    {{ $period['growth'] >= 0 ? '+' : '' }}{{ $period['growth'] }}%
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No data available</h3>
                                        <p class="text-gray-600 dark:text-gray-400">No submission data found for the selected period.</p>
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
        /* Dark mode chart styling */
        .dark #submissionChart {
            background: rgba(17, 24, 39, 0.9);
            border-radius: 8px;
            padding: 16px;
            border: 1px solid rgba(75, 85, 99, 0.3);
        }
        
        /* Light mode chart styling */
        #submissionChart {
            background: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            padding: 16px;
        }
        
        /* Chart container improvements */
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }
        
        /* Dark mode specific adjustments */
        .dark .chart-container {
            background: linear-gradient(135deg, rgba(17, 24, 39, 0.95) 0%, rgba(31, 41, 55, 0.9) 100%);
            border: 2px solid rgba(75, 85, 99, 0.5);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }
        
        /* Light mode specific adjustments */
        .chart-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(249, 250, 251, 0.98) 100%);
            border: 2px solid rgba(209, 213, 219, 0.7);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('submissionChart').getContext('2d');
            
            // Detect dark mode with multiple checks
            const hasDarkClass = document.documentElement.classList.contains('dark');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const bodyBg = window.getComputedStyle(document.body).backgroundColor;
            const isLightBackground = bodyBg.includes('255, 255, 255') || bodyBg.includes('rgb(255, 255, 255)') || 
                                    bodyBg.includes('rgba(255, 255, 255') || bodyBg === 'white';
            
            // Check if the page is actually in dark mode by looking at the chart container
            const chartContainer = document.querySelector('.chart-container');
            const containerBg = chartContainer ? window.getComputedStyle(chartContainer).backgroundColor : '';
            const isDarkContainer = containerBg.includes('17, 24, 39') || containerBg.includes('31, 41, 55') || 
                                  containerBg.includes('rgba(17, 24, 39') || containerBg.includes('rgba(31, 41, 55');
            
            const isDarkMode = hasDarkClass || (prefersDark && !isLightBackground) || isDarkContainer;
            
            // Debug theme detection
            console.log('Has dark class:', hasDarkClass);
            console.log('Prefers dark:', prefersDark);
            console.log('Is light background:', isLightBackground);
            console.log('Is dark container:', isDarkContainer);
            console.log('Container background:', containerBg);
            console.log('Final isDarkMode:', isDarkMode);
            console.log('Body background:', bodyBg);
            
            // Prepare chart data
            const periods = @json($data['periods'] ?? []);
            
            // Check if we have data to display
            if (!periods || periods.length === 0) {
                ctx.fillStyle = '#000000'; // Always black for visibility
                ctx.font = 'bold 18px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('No data available for the selected period', ctx.canvas.width / 2, ctx.canvas.height / 2);
                return;
            }
            
            // Safely extract data with fallbacks
            const labels = periods.map(p => p.label || 'Unknown');
            const academicFormsData = periods.map(p => parseInt(p.academic_forms) || 0);
            const thesisDocumentsData = periods.map(p => parseInt(p.thesis_documents) || 0);
            
            // Check if all data is zero
            const totalData = [...academicFormsData, ...thesisDocumentsData];
            const hasData = totalData.some(value => value > 0);
            
            if (!hasData) {
                ctx.fillStyle = '#000000'; // Always black for visibility
                ctx.font = 'bold 18px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('No submissions found for the selected period', ctx.canvas.width / 2, ctx.canvas.height / 2);
                return;
            }
            
            // Define colors - text always black for visibility
            const colors = {
                academicForms: {
                    border: isDarkMode ? '#10B981' : '#059669',
                    background: isDarkMode ? 'rgba(16, 185, 129, 0.25)' : 'rgba(5, 150, 105, 0.15)',
                    point: isDarkMode ? '#10B981' : '#059669',
                    pointBorder: '#ffffff'
                },
                thesisDocuments: {
                    border: isDarkMode ? '#F59E0B' : '#D97706',
                    background: isDarkMode ? 'rgba(245, 158, 11, 0.25)' : 'rgba(217, 119, 6, 0.15)',
                    point: isDarkMode ? '#F59E0B' : '#D97706',
                    pointBorder: '#ffffff'
                },
                text: '#000000', // Always black for visibility
                textSecondary: '#000000', // Always black for visibility
                grid: isDarkMode ? 'rgba(75, 85, 99, 0.6)' : 'rgba(107, 114, 128, 0.3)',
                ticks: '#000000' // Always black for visibility
            };
            
            console.log('Using colors:', colors);
            console.log('Chart will use text color:', colors.text);
            
            // Create the chart
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Academic Forms',
                            data: academicFormsData,
                            borderColor: colors.academicForms.border,
                            backgroundColor: colors.academicForms.background,
                            pointBackgroundColor: colors.academicForms.point,
                            pointBorderColor: colors.academicForms.pointBorder,
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            tension: 0.1,
                            fill: true,
                            borderWidth: 3
                        },
                        {
                            label: 'Thesis Documents',
                            data: thesisDocumentsData,
                            borderColor: colors.thesisDocuments.border,
                            backgroundColor: colors.thesisDocuments.background,
                            pointBackgroundColor: colors.thesisDocuments.point,
                            pointBorderColor: colors.thesisDocuments.pointBorder,
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            tension: 0.1,
                            fill: true,
                            borderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Submission Trends Over Time',
                            color: colors.text,
                            font: {
                                size: 20,
                                weight: 'bold'
                            }
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                color: colors.text,
                                font: {
                                    size: 15,
                                    weight: '600'
                                },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                color: colors.ticks,
                                font: {
                                    size: 14,
                                    weight: '600'
                                }
                            },
                            title: {
                                display: true,
                                text: 'Number of Submissions',
                                color: colors.text,
                                font: {
                                    size: 16,
                                    weight: '700'
                                }
                            },
                            grid: {
                                color: colors.grid,
                                drawBorder: false
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ ucfirst(is_string($period) ? $period : 'monthly') }} Period',
                                color: colors.text,
                                font: {
                                    size: 16,
                                    weight: '700'
                                }
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 0,
                                color: colors.ticks,
                                font: {
                                    size: 14,
                                    weight: '600'
                                }
                            },
                            grid: {
                                color: colors.grid,
                                drawBorder: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    elements: {
                        point: {
                            hoverBackgroundColor: '#ffffff',
                            hoverBorderColor: '#000000',
                            hoverBorderWidth: 3
                        }
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
