<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📜 {{ __('Form Submission History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                Your Form Submissions
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Track the status of all your submitted academic forms.
                            </p>
                        </div>
                        <a href="{{ route('student.forms.index') }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            📝 Submit New Form
                        </a>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Forms List -->
            @if($forms->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Form Details
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Submitted
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($forms as $form)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3
                                                    @if($form->form_type === 'registration') bg-blue-100 dark:bg-blue-900
                                                    @elseif($form->form_type === 'clearance') bg-green-100 dark:bg-green-900
                                                    @else bg-purple-100 dark:bg-purple-900 @endif">
                                                    @if($form->form_type === 'registration')
                                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    @elseif($form->form_type === 'clearance')
                                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $form->title }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        ID: {{ $form->student_id }}
                                                    </div>
                                                    @if($form->description)
                                                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                            {{ Str::limit($form->description, 50) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($form->form_type === 'registration') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($form->form_type === 'clearance') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @else bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 @endif">
                                                {{ $form->form_type_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($form->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($form->status === 'under_review') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($form->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                @if($form->status === 'pending') ⏳ Pending
                                                @elseif($form->status === 'under_review') 📋 Under Review
                                                @elseif($form->status === 'approved') ✅ Approved
                                                @else ❌ Rejected @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <div>{{ $form->submission_date->format('M j, Y') }}</div>
                                            <div class="text-xs">{{ $form->created_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('student.forms.show', $form) }}" 
                                               class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition">
                                                👁️ View
                                            </a>
                                            
                                            @if($form->uploaded_files && count($form->uploaded_files) > 0)
                                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                                <button onclick="showFilesModal('{{ $form->id }}')" 
                                                        class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 transition">
                                                    📎 Files ({{ count($form->uploaded_files) }})
                                                </button>
                                            @endif
                                            
                                            @if($form->status === 'approved')
                                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                                <span class="text-green-600 dark:text-green-400">
                                                    🎉 Complete
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($forms->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $forms->links() }}
                        </div>
                    @endif
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            No Form Submissions Yet
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            You haven't submitted any forms yet. Start by submitting your first academic form.
                        </p>
                        <a href="{{ route('student.forms.index') }}" 
                           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                            📝 Submit Your First Form
                        </a>
                    </div>
                </div>
            @endif

            <!-- Statistics Cards -->
            @if($forms->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                    <!-- Total Forms -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $forms->total() }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Forms</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Forms -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $forms->where('status', 'pending')->count() + $forms->where('status', 'under_review')->count() }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Pending</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Forms -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $forms->where('status', 'approved')->count() }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Approved</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Success Rate -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $forms->count() > 0 ? round(($forms->where('status', 'approved')->count() / $forms->count()) * 100) : 0 }}%
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Success Rate</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Files Modal -->
    <div id="filesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" onclick="closeFilesModal()">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800" onclick="event.stopPropagation()">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        📎 Uploaded Files
                    </h3>
                    <button onclick="closeFilesModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="filesContent" class="space-y-2">
                    <!-- Files will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function showFilesModal(formId) {
            // In a real application, you would fetch the files via AJAX
            // For now, we'll show a placeholder
            const modal = document.getElementById('filesModal');
            const content = document.getElementById('filesContent');
            
            content.innerHTML = '<p class="text-gray-500 dark:text-gray-400">Loading files...</p>';
            modal.classList.remove('hidden');
            
            // Simulate loading files (replace with actual AJAX call)
            setTimeout(() => {
                content.innerHTML = `
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <p>Files for this form would be listed here.</p>
                        <p class="mt-2">Click "View" to see detailed form information including file download links.</p>
                    </div>
                `;
            }, 500);
        }
        
        function closeFilesModal() {
            document.getElementById('filesModal').classList.add('hidden');
        }
    </script>
</x-app-layout> 