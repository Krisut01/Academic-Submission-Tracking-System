<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🎓 {{ __('Faculty Performance Report') }}
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
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time Period</label>
                            <select name="period" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="last_3_months" {{ $period === 'last_3_months' ? 'selected' : '' }}>Last 3 Months</option>
                                <option value="last_6_months" {{ $period === 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                <option value="last_year" {{ $period === 'last_year' ? 'selected' : '' }}>Last Year</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
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
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Faculty</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['faculty']->count() }}</p>
                        </div>
                        <div class="p-3 bg-blue-500 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl p-6 border border-green-200/50 dark:border-green-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Total Reviews</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['faculty']->sum('total_reviews') }}</p>
                        </div>
                        <div class="p-3 bg-green-500 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-2xl p-6 border border-yellow-200/50 dark:border-yellow-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Avg Review Time</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $data['faculty']->where('avg_review_time', '>', 0)->avg('avg_review_time') ? round($data['faculty']->where('avg_review_time', '>', 0)->avg('avg_review_time'), 1) : 0 }} days
                            </p>
                        </div>
                        <div class="p-3 bg-yellow-500 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-2xl p-6 border border-purple-200/50 dark:border-purple-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Period</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ ucfirst(str_replace('_', ' ', $data['period'])) }}
                            </p>
                        </div>
                        <div class="p-3 bg-purple-500 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Faculty Performance Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Faculty Performance Overview</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Visual representation of faculty review activity and performance</p>
                </div>
                <div class="px-6 py-8">
                    <div class="chart-container">
                        <canvas id="facultyPerformanceChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Faculty Performance Table -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Faculty Performance Details</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Detailed breakdown of faculty review activity and performance metrics</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Faculty Member</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Forms Reviewed</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thesis Reviewed</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Reviews</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Avg Review Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Performance</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if($data['faculty']->count() > 0)
                                @foreach($data['faculty']->sortByDesc('total_reviews') as $faculty)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-white">
                                                            {{ strtoupper(substr($faculty->name, 0, 2)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $faculty->name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $faculty->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $faculty->reviewed_forms_count ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $faculty->reviewed_thesis_count ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $faculty->total_reviews ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $faculty->avg_review_time ? round($faculty->avg_review_time, 1) . ' days' : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if(($faculty->total_reviews ?? 0) > 0)
                                                @php
                                                    $avgTime = $faculty->avg_review_time ?? 0;
                                                    $totalReviews = $faculty->total_reviews ?? 0;
                                                @endphp
                                                @if($avgTime <= 3 && $totalReviews >= 5)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                                                        Excellent
                                                    </span>
                                                @elseif($avgTime <= 5 && $totalReviews >= 3)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200">
                                                        Good
                                                    </span>
                                                @elseif($avgTime <= 7 && $totalReviews >= 1)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200">
                                                        Average
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200">
                                                        Needs Improvement
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-200">
                                                    No Activity
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No faculty found</h3>
                                        <p class="text-gray-600 dark:text-gray-400">No faculty members found for the selected period.</p>
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
            const ctx = document.getElementById('facultyPerformanceChart').getContext('2d');
            
            // Detect dark mode
            const hasDarkClass = document.documentElement.classList.contains('dark');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const bodyBg = window.getComputedStyle(document.body).backgroundColor;
            const isLightBackground = bodyBg.includes('255, 255, 255') || bodyBg.includes('rgb(255, 255, 255)') || 
                                    bodyBg.includes('rgba(255, 255, 255') || bodyBg === 'white';
            
            const isDarkMode = hasDarkClass || (prefersDark && !isLightBackground);
            
            // Prepare chart data
            const faculty = @json($data['faculty']);
            
            if (!faculty || faculty.length === 0) {
                ctx.font = '16px Arial';
                ctx.fillStyle = '#000000'; // Always black for visibility
                ctx.textAlign = 'center';
                ctx.fillText('No faculty data available', ctx.canvas.width / 2, ctx.canvas.height / 2);
                return;
            }
            
            // Sort faculty by total reviews for better visualization
            const sortedFaculty = faculty.sort((a, b) => (b.total_reviews || 0) - (a.total_reviews || 0));
            
            const labels = sortedFaculty.map(f => f.name.length > 15 ? f.name.substring(0, 15) + '...' : f.name);
            const totalReviewsData = sortedFaculty.map(f => f.total_reviews || 0);
            const formsData = sortedFaculty.map(f => f.reviewed_forms_count || 0);
            const thesisData = sortedFaculty.map(f => f.reviewed_thesis_count || 0);
            const avgTimeData = sortedFaculty.map(f => f.avg_review_time || 0);
            
            // Define colors - text always black for visibility
            const colors = {
                totalReviews: {
                    border: isDarkMode ? '#3B82F6' : '#2563EB',
                    background: isDarkMode ? 'rgba(59, 130, 246, 0.25)' : 'rgba(37, 99, 235, 0.15)',
                    point: isDarkMode ? '#3B82F6' : '#2563EB'
                },
                forms: {
                    border: isDarkMode ? '#10B981' : '#059669',
                    background: isDarkMode ? 'rgba(16, 185, 129, 0.25)' : 'rgba(5, 150, 105, 0.15)',
                    point: isDarkMode ? '#10B981' : '#059669'
                },
                thesis: {
                    border: isDarkMode ? '#F59E0B' : '#D97706',
                    background: isDarkMode ? 'rgba(245, 158, 11, 0.25)' : 'rgba(217, 119, 6, 0.15)',
                    point: isDarkMode ? '#F59E0B' : '#D97706'
                },
                avgTime: {
                    border: isDarkMode ? '#8B5CF6' : '#7C3AED',
                    background: isDarkMode ? 'rgba(139, 92, 246, 0.25)' : 'rgba(124, 58, 237, 0.15)',
                    point: isDarkMode ? '#8B5CF6' : '#7C3AED'
                },
                text: '#000000', // Always black for visibility
                textSecondary: '#000000', // Always black for visibility
                grid: isDarkMode ? 'rgba(75, 85, 99, 0.6)' : 'rgba(107, 114, 128, 0.3)',
                ticks: '#000000' // Always black for visibility
            };
            
            // Create the chart
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Reviews',
                            data: totalReviewsData,
                            borderColor: colors.totalReviews.border,
                            backgroundColor: colors.totalReviews.background,
                            pointBackgroundColor: colors.totalReviews.point,
                            pointBorderColor: colors.totalReviews.point,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            borderWidth: 2
                        },
                        {
                            label: 'Forms Reviewed',
                            data: formsData,
                            borderColor: colors.forms.border,
                            backgroundColor: colors.forms.background,
                            pointBackgroundColor: colors.forms.point,
                            pointBorderColor: colors.forms.point,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            borderWidth: 2
                        },
                        {
                            label: 'Thesis Reviewed',
                            data: thesisData,
                            borderColor: colors.thesis.border,
                            backgroundColor: colors.thesis.background,
                            pointBackgroundColor: colors.thesis.point,
                            pointBorderColor: colors.thesis.point,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Faculty Review Activity',
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
                                    const facultyIndex = context.dataIndex;
                                    const faculty = sortedFaculty[facultyIndex];
                                    const avgTime = faculty.avg_review_time || 0;
                                    return `Avg Review Time: ${avgTime ? avgTime.toFixed(1) + ' days' : 'N/A'}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Faculty Members',
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
                                maxRotation: 45,
                                minRotation: 0
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Number of Reviews',
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
