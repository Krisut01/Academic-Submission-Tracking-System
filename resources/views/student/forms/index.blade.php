<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                {{ __('Submit Academic Forms') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>
    </x-slot>

    <!-- Enhanced Custom Styles -->
    <style>
        .form-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .form-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .form-btn {
            background: linear-gradient(135deg, var(--bg-from), var(--bg-to));
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px 0 rgba(0, 0, 0, 0.1);
        }
        .form-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px 0 rgba(0, 0, 0, 0.15);
        }
        .registration { --bg-from: #3B82F6; --bg-to: #1D4ED8; }
        .clearance { --bg-from: #10B981; --bg-to: #059669; }
        .evaluation { --bg-from: #8B5CF6; --bg-to: #7C3AED; }
    </style>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Enhanced Page Header -->
            <div class="mb-12 text-center">
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl shadow-2xl mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white mb-6">
                    Academic Form Submission
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto leading-relaxed">
                    Choose a form type to submit your academic documents and requirements. Each form serves specific academic purposes and requirements.
                </p>
                <div class="mt-8">
                    <a href="{{ route('student.forms.history') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 font-bold transition-all duration-300 shadow-sm hover:shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        View History
                    </a>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-2xl shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-500 rounded-xl shadow-lg mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-green-800 dark:text-green-300 font-semibold text-lg">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-6 bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border border-red-200 dark:border-red-800 rounded-2xl shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-500 rounded-xl shadow-lg mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-red-800 dark:text-red-300 font-semibold text-lg">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Enhanced Form Types Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <!-- Registration Form -->
                <div class="form-card bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-8">
                        <!-- Enhanced Header -->
                        <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 text-center">
                            Registration Form
                        </h3>
                        <p class="text-base text-gray-600 dark:text-gray-400 text-center mb-6 leading-relaxed">
                            Submit your course registration and enrollment documents for the current semester.
                        </p>
                        
                        <!-- Enhanced Requirements -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-4 mb-8">
                            <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-3">Required Documents:</h4>
                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-blue-800 dark:text-blue-400">
                                    <svg class="w-4 h-4 mr-2 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Course selection
                                </div>
                                <div class="flex items-center text-sm text-blue-800 dark:text-blue-400">
                                    <svg class="w-4 h-4 mr-2 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Academic records
                                </div>
                                <div class="flex items-center text-sm text-blue-800 dark:text-blue-400">
                                    <svg class="w-4 h-4 mr-2 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Payment verification
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('student.forms.create', ['type' => 'registration']) }}" 
                           class="form-btn registration w-full text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-300 flex items-center justify-center group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Submit Registration Form
                        </a>
                    </div>
                </div>

                <!-- Clearance Form -->
                <div class="form-card bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-8">
                        <!-- Enhanced Header -->
                        <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 text-center">
                            Clearance Form
                        </h3>
                        <p class="text-base text-gray-600 dark:text-gray-400 text-center mb-6 leading-relaxed">
                            Request clearance for graduation, transfer, or other academic purposes.
                        </p>

                        <!-- Enhanced Requirements -->
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-4 mb-8">
                            <h4 class="font-semibold text-emerald-900 dark:text-emerald-300 mb-3">Required Documents:</h4>
                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-emerald-800 dark:text-emerald-400">
                                    <svg class="w-4 h-4 mr-2 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Library clearance
                                </div>
                                <div class="flex items-center text-sm text-emerald-800 dark:text-emerald-400">
                                    <svg class="w-4 h-4 mr-2 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Financial obligations
                                </div>
                                <div class="flex items-center text-sm text-emerald-800 dark:text-emerald-400">
                                    <svg class="w-4 h-4 mr-2 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Equipment return
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('student.forms.create', ['type' => 'clearance']) }}" 
                           class="form-btn clearance w-full text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-300 flex items-center justify-center group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Submit Clearance Form
                        </a>
                    </div>
                </div>

                <!-- Evaluation Form -->
                <div class="form-card bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-8">
                        <!-- Enhanced Header -->
                        <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl shadow-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 text-center">
                            Evaluation Form
                        </h3>
                        <p class="text-base text-gray-600 dark:text-gray-400 text-center mb-6 leading-relaxed">
                            Submit academic evaluation requests for credit assessment and grade verification.
                        </p>

                        <!-- Enhanced Requirements -->
                        <div class="bg-violet-50 dark:bg-violet-900/20 rounded-2xl p-4 mb-8">
                            <h4 class="font-semibold text-violet-900 dark:text-violet-300 mb-3">Required Documents:</h4>
                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-violet-800 dark:text-violet-400">
                                    <svg class="w-4 h-4 mr-2 text-violet-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Grade verification
                                </div>
                                <div class="flex items-center text-sm text-violet-800 dark:text-violet-400">
                                    <svg class="w-4 h-4 mr-2 text-violet-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Credit assessment
                                </div>
                                <div class="flex items-center text-sm text-violet-800 dark:text-violet-400">
                                    <svg class="w-4 h-4 mr-2 text-violet-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Transcript requests
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('student.forms.create', ['type' => 'evaluation']) }}" 
                           class="form-btn evaluation w-full text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-300 flex items-center justify-center group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Submit Evaluation Form
                        </a>
                    </div>
                </div>
            </div>

            <!-- Enhanced Quick Links -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Quick Links & Information
                        </h3>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="group flex items-center space-x-6 p-6 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl border border-blue-200 dark:border-blue-700 hover:shadow-lg transition-all duration-300">
                            <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg group-hover:shadow-xl transition-all duration-300 flex-shrink-0">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-blue-900 dark:text-blue-300 mb-2">Processing Time</p>
                                <p class="text-base text-blue-800 dark:text-blue-400">3-5 business days</p>
                            </div>
                        </div>
                        
                        <div class="group flex items-center space-x-6 p-6 bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 rounded-2xl border border-emerald-200 dark:border-emerald-700 hover:shadow-lg transition-all duration-300">
                            <div class="p-4 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg group-hover:shadow-xl transition-all duration-300 flex-shrink-0">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2v0M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-emerald-900 dark:text-emerald-300 mb-2">Supported Files</p>
                                <p class="text-base text-emerald-800 dark:text-emerald-400">PDF, DOC, JPG, PNG (10MB max)</p>
                            </div>
                        </div>
                        
                        <a href="{{ route('student.forms.history') }}" class="group flex items-center space-x-6 p-6 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-2xl border border-amber-200 dark:border-amber-700 hover:shadow-lg transition-all duration-300">
                            <div class="p-4 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg group-hover:shadow-xl transition-all duration-300 flex-shrink-0">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-amber-900 dark:text-amber-300 mb-2">Submission History</p>
                                <p class="text-base text-amber-800 dark:text-amber-400">View all submitted forms</p>
                            </div>
                        </a>
                        
                        <div class="group flex items-center space-x-6 p-6 bg-gradient-to-br from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 rounded-2xl border border-red-200 dark:border-red-700 hover:shadow-lg transition-all duration-300">
                            <div class="p-4 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-lg group-hover:shadow-xl transition-all duration-300 flex-shrink-0">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-red-900 dark:text-red-300 mb-2">Need Help?</p>
                                <p class="text-base text-red-800 dark:text-red-400">Contact academic office</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 