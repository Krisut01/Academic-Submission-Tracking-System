<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            👥 {{ __('User Activity Report') }}
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
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User Role</label>
                            <select name="role" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="all" {{ $role === 'all' ? 'selected' : '' }}>All Roles</option>
                                <option value="student" {{ $role === 'student' ? 'selected' : '' }}>Students</option>
                                <option value="faculty" {{ $role === 'faculty' ? 'selected' : '' }}>Faculty</option>
                                <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Administrators</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time Period</label>
                            <select name="period" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="last_7_days" {{ $period === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="last_30_days" {{ $period === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="last_90_days" {{ $period === 'last_90_days' ? 'selected' : '' }}>Last 90 Days</option>
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
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Users</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['users']->count() }}</p>
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
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Active Users</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['total_active'] }}</p>
                        </div>
                        <div class="p-3 bg-green-500 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-2xl p-6 border border-yellow-200/50 dark:border-yellow-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Activity Rate</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $data['users']->count() > 0 ? round(($data['total_active'] / $data['users']->count()) * 100, 1) : 0 }}%
                            </p>
                        </div>
                        <div class="p-3 bg-yellow-500 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Activity Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Activity Overview</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Visual representation of user activity by role</p>
                </div>
                <div class="px-6 py-8">
                    <div class="chart-container">
                        <canvas id="userActivityChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- User Activity Table -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Activity Details</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Detailed breakdown of user activity and submissions</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Academic Forms</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thesis Documents</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Activity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if($data['users']->count() > 0)
                                @foreach($data['users'] as $user)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-white">
                                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($user->role === 'admin') bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200
                                                @elseif($user->role === 'faculty') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200
                                                @else bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200 @endif">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $user->forms_count ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $user->thesis_count ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ ($user->forms_count ?? 0) + ($user->thesis_count ?? 0) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if(($user->forms_count ?? 0) > 0 || ($user->thesis_count ?? 0) > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-200">
                                                    Inactive
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
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No users found</h3>
                                        <p class="text-gray-600 dark:text-gray-400">No users match the selected criteria.</p>
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
            const ctx = document.getElementById('userActivityChart').getContext('2d');
            
            // Detect dark mode
            const hasDarkClass = document.documentElement.classList.contains('dark');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const bodyBg = window.getComputedStyle(document.body).backgroundColor;
            const isLightBackground = bodyBg.includes('255, 255, 255') || bodyBg.includes('rgb(255, 255, 255)') || 
                                    bodyBg.includes('rgba(255, 255, 255') || bodyBg === 'white';
            
            const isDarkMode = hasDarkClass || (prefersDark && !isLightBackground);
            
            // Prepare chart data
            const users = @json($data['users']);
            
            if (!users || users.length === 0) {
                ctx.font = '16px Arial';
                ctx.fillStyle = '#000000'; // Always black for visibility
                ctx.textAlign = 'center';
                ctx.fillText('No user data available', ctx.canvas.width / 2, ctx.canvas.height / 2);
                return;
            }
            
            // Group users by role
            const roleGroups = users.reduce((acc, user) => {
                const role = user.role || 'unknown';
                if (!acc[role]) {
                    acc[role] = { total: 0, active: 0, forms: 0, thesis: 0 };
                }
                acc[role].total++;
                if ((user.forms_count || 0) > 0 || (user.thesis_count || 0) > 0) {
                    acc[role].active++;
                }
                acc[role].forms += user.forms_count || 0;
                acc[role].thesis += user.thesis_count || 0;
                return acc;
            }, {});
            
            const labels = Object.keys(roleGroups).map(role => role.charAt(0).toUpperCase() + role.slice(1));
            const totalData = Object.values(roleGroups).map(group => group.total);
            const activeData = Object.values(roleGroups).map(group => group.active);
            const formsData = Object.values(roleGroups).map(group => group.forms);
            const thesisData = Object.values(roleGroups).map(group => group.thesis);
            
            // Define colors - text always black for visibility
            const colors = {
                total: {
                    border: isDarkMode ? '#3B82F6' : '#2563EB',
                    background: isDarkMode ? 'rgba(59, 130, 246, 0.25)' : 'rgba(37, 99, 235, 0.15)',
                    point: isDarkMode ? '#3B82F6' : '#2563EB'
                },
                active: {
                    border: isDarkMode ? '#10B981' : '#059669',
                    background: isDarkMode ? 'rgba(16, 185, 129, 0.25)' : 'rgba(5, 150, 105, 0.15)',
                    point: isDarkMode ? '#10B981' : '#059669'
                },
                forms: {
                    border: isDarkMode ? '#F59E0B' : '#D97706',
                    background: isDarkMode ? 'rgba(245, 158, 11, 0.25)' : 'rgba(217, 119, 6, 0.15)',
                    point: isDarkMode ? '#F59E0B' : '#D97706'
                },
                thesis: {
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
                            label: 'Total Users',
                            data: totalData,
                            borderColor: colors.total.border,
                            backgroundColor: colors.total.background,
                            pointBackgroundColor: colors.total.point,
                            pointBorderColor: colors.total.point,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            borderWidth: 2
                        },
                        {
                            label: 'Active Users',
                            data: activeData,
                            borderColor: colors.active.border,
                            backgroundColor: colors.active.background,
                            pointBackgroundColor: colors.active.point,
                            pointBorderColor: colors.active.point,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            borderWidth: 2
                        },
                        {
                            label: 'Academic Forms',
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
                            label: 'Thesis Documents',
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
                            text: 'User Activity by Role',
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
                            displayColors: true
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'User Role',
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
                                text: 'Count',
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
