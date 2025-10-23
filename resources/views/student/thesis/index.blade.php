<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                {{ __('Thesis Documents') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>
    </x-slot>

    <!-- Enhanced Custom Styles -->
    <style>
        /* Remove any skeleton/loading states */
        .thesis-card * {
            background-image: none !important;
        }
        
        /* Ensure no pseudo-elements create gray areas */
        .thesis-card::before,
        .thesis-card::after,
        .thesis-card *::before,
        .thesis-card *::after {
            display: none !important;
        }
        
        .thesis-card {
            transition: all 0.2s ease;
            position: relative;
        }
        .thesis-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.1);
        }
        .thesis-btn {
            transition: all 0.2s ease;
            border: none !important;
            text-decoration: none !important;
            outline: none !important;
            position: relative;
            overflow: hidden;
        }
        .thesis-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .thesis-btn:focus {
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }
        
        /* Simple solid colors for each button */
        .proposal {
            background-color: #3B82F6 !important;
        }
        .proposal:hover {
            background-color: #1D4ED8 !important;
        }
        
        .approval {
            background-color: #10B981 !important;
        }
        .approval:hover {
            background-color: #059669 !important;
        }
        
        .assignment {
            background-color: #F59E0B !important;
        }
        .assignment:hover {
            background-color: #D97706 !important;
        }
        
        .manuscript {
            background-color: #8B5CF6 !important;
        }
        .manuscript:hover {
            background-color: #7C3AED !important;
        }
        
        /* Ensure card content is clean */
        .card-content {
            background: transparent !important;
        }
        
        /* Remove any browser default styling that might cause gray elements */
        .thesis-card h3,
        .thesis-card p,
        .thesis-card div {
            background: transparent !important;
            border: none !important;
        }
    </style>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-2xl shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-500 rounded-xl shadow-lg mr-4">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-green-800 dark:text-green-300 font-semibold text-lg">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-6 bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border border-red-200 dark:border-red-800 rounded-2xl shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-500 rounded-xl shadow-lg mr-4">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-red-800 dark:text-red-300 font-semibold text-lg">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Enhanced Page Header -->
            <div class="mb-12 text-center">
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl shadow-2xl mb-6">
                        <svg class="w-10 h-10 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C20.168 18.477 18.754 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white mb-6">
                    Submit Thesis Documents
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto leading-relaxed">
                    Choose the type of thesis document you want to submit. Each document type has specific requirements and guidelines to ensure successful processing.
                </p>
            </div>

            <!-- Enhanced Document Type Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                <!-- Proposal Form -->
                <div class="thesis-card bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-8 card-content">
                        <!-- Icon Header -->
                        <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 text-center">Proposal Form</h3>
                        <p class="text-base text-gray-600 dark:text-gray-400 mb-6 text-center leading-relaxed">Submit your comprehensive research proposal with methodology and objectives.</p>
                        
                        <!-- Requirements List -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-4 mb-8">
                            <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-3">Required Documents:</h4>
                            <ul class="text-sm text-blue-800 dark:text-blue-400 space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Research title & objectives
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Methodology & timeline
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Literature review
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Expected outcomes
                                </li>
                            </ul>
                        </div>
                        
                        <a href="{{ route('student.thesis.create', ['type' => 'proposal']) }}" 
                           class="thesis-btn proposal w-full text-gray-900 dark:text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Submit Proposal
                        </a>
                    </div>
                </div>

                <!-- Approval Sheet -->
                <div class="thesis-card bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-8 card-content">
                        <!-- Icon Header -->
                        <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 text-center">Approval Sheet</h3>
                        <p class="text-base text-gray-600 dark:text-gray-400 mb-6 text-center leading-relaxed">Submit your approved thesis document with panel signatures and recommendations.</p>
                        
                        <!-- Requirements List -->
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-4 mb-8">
                            <h4 class="font-semibold text-emerald-900 dark:text-emerald-300 mb-3">Required Documents:</h4>
                            <ul class="text-sm text-emerald-800 dark:text-emerald-400 space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Panel member signatures
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Defense date & venue
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Final approval status
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Committee recommendations
                                </li>
                            </ul>
                        </div>
                        
                        <a href="{{ route('student.thesis.create', ['type' => 'approval_sheet']) }}" 
                           class="thesis-btn approval w-full text-gray-900 dark:text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Submit Approval
                        </a>
                    </div>
                </div>

                <!-- Panel Assignment -->
                <div class="thesis-card bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-8 card-content">
                        <!-- Icon Header -->
                        <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 text-center">Panel Assignment</h3>
                        <p class="text-base text-gray-600 dark:text-gray-400 mb-6 text-center leading-relaxed">Submit panel assignment documentation and committee details.</p>
                        
                        <!-- Requirements List -->
                        <div class="bg-amber-50 dark:bg-amber-900/20 rounded-2xl p-4 mb-8">
                            <h4 class="font-semibold text-amber-900 dark:text-amber-300 mb-3">Required Documents:</h4>
                            <ul class="text-sm text-amber-800 dark:text-amber-400 space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-amber-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Panel chair assignment
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-amber-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Committee members
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-amber-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Specialization areas
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-amber-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Assignment dates
                                </li>
                            </ul>
                        </div>
                        
                        <a href="{{ route('student.thesis.create', ['type' => 'panel_assignment']) }}" 
                           class="thesis-btn assignment w-full text-gray-900 dark:text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Submit Assignment
                        </a>
                    </div>
                </div>

                <!-- Final Manuscript -->
                <div class="thesis-card bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-8 card-content">
                        <!-- Icon Header -->
                        <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl shadow-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C20.168 18.477 18.754 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 text-center">Final Manuscript</h3>
                        <p class="text-base text-gray-600 dark:text-gray-400 mb-6 text-center leading-relaxed">Submit your completed thesis manuscript with all required components.</p>
                        
                        <!-- Requirements List -->
                        <div class="bg-violet-50 dark:bg-violet-900/20 rounded-2xl p-4 mb-8">
                            <h4 class="font-semibold text-violet-900 dark:text-violet-300 mb-3">Required Documents:</h4>
                            <ul class="text-sm text-violet-800 dark:text-violet-400 space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-violet-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Complete manuscript
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-violet-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Plagiarism check results
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-violet-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Final formatting
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-violet-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    All chapters included
                                </li>
                            </ul>
                        </div>
                        
                        <a href="{{ route('student.thesis.create', ['type' => 'final_manuscript']) }}" 
                           class="thesis-btn manuscript w-full text-gray-900 dark:text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Submit Manuscript
                        </a>
                    </div>
                </div>
            </div>

            <!-- Enhanced Quick Links & Information -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Quick Links & Information
                        </h2>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- View History -->
                        <div class="group p-6 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl border border-blue-200 dark:border-blue-700 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-300">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-blue-900 dark:text-blue-300">View Submission History</h3>
                            </div>
                            <p class="text-base text-blue-800 dark:text-blue-400 mb-6 leading-relaxed">Track all your thesis document submissions and monitor their current status.</p>
                            <a href="{{ route('student.thesis.history') }}" 
                               class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-bold transition-colors group-hover:translate-x-1 transform duration-300">
                                View History
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>

                        <!-- Guidelines -->
                        <div class="group p-6 bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 rounded-2xl border border-emerald-200 dark:border-emerald-700 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-300">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-emerald-900 dark:text-emerald-300">Submission Guidelines</h3>
                            </div>
                            <p class="text-base text-emerald-800 dark:text-emerald-400 mb-6 leading-relaxed">Review the detailed requirements and guidelines for each document type.</p>
                            <a href="#" class="inline-flex items-center text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-bold transition-colors group-hover:translate-x-1 transform duration-300">
                                View Guidelines
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>

                        <!-- Support -->
                        <div class="group p-6 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-2xl border border-amber-200 dark:border-amber-700 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-300">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-amber-900 dark:text-amber-300">Need Help?</h3>
                            </div>
                            <p class="text-base text-amber-800 dark:text-amber-400 mb-6 leading-relaxed">Contact our support team if you have questions about the submission process.</p>
                            <a href="#" class="inline-flex items-center text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 font-bold transition-colors group-hover:translate-x-1 transform duration-300">
                                Get Support
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
