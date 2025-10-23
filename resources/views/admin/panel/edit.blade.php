<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ✏️ {{ __('Edit Panel Assignment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.panel.show', $assignment) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Assignment Details
                </a>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Panel Assignment</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update panel members and defense details</p>
                </div>

                <form method="POST" action="{{ route('admin.panel.update', $assignment) }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Assignment Information (Read-only) -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Student
                            </label>
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 rounded-xl text-gray-900 dark:text-gray-100">
                                {{ $assignment->student->name }} ({{ $assignment->student->email }})
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Thesis Title
                            </label>
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 rounded-xl text-gray-900 dark:text-gray-100">
                                {{ $assignment->thesis_title }}
                            </div>
                        </div>
                    </div>

                    <!-- Panel Members Selection -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Panel Members * (Select 3-5 members)
                            <span id="member-count" class="text-xs text-gray-500 dark:text-gray-400 ml-2">(0 selected)</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($facultyMembers as $faculty)
                                <label class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" name="panel_members[]" value="{{ $faculty->id }}"
                                           {{ in_array($faculty->id, old('panel_members', $assignment->panel_member_ids)) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $faculty->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $faculty->email }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('panel_members')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Panel Chair Selection -->
                    <div class="mb-8">
                        <label for="panel_chair_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Panel Chair *
                        </label>
                        <select name="panel_chair_id" id="panel_chair_id" required 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Select Panel Chair</option>
                            @foreach($facultyMembers as $faculty)
                                <option value="{{ $faculty->id }}" 
                                        {{ old('panel_chair_id', $assignment->panel_chair_id) == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name }} ({{ $faculty->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('panel_chair_id')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Secretary Selection -->
                    <div class="mb-8">
                        <label for="secretary_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Secretary
                        </label>
                        <select name="secretary_id" id="secretary_id" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Select Secretary (Optional)</option>
                            @foreach($facultyMembers as $faculty)
                                <option value="{{ $faculty->id }}" 
                                        {{ old('secretary_id', $assignment->secretary_id) == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name }} ({{ $faculty->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('secretary_id')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Defense Details -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="defense_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Defense Date & Time *
                            </label>
                            <input type="datetime-local" name="defense_date" id="defense_date" 
                                   value="{{ old('defense_date', $assignment->defense_date ? $assignment->defense_date->format('Y-m-d\TH:i') : '') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            @error('defense_date')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="defense_venue" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Defense Venue *
                            </label>
                            <input type="text" name="defense_venue" id="defense_venue" 
                                   value="{{ old('defense_venue', $assignment->defense_venue) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                   placeholder="e.g., Room 301, Main Building">
                            @error('defense_venue')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status and Result -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Status *
                            </label>
                            <select name="status" id="status" required 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="scheduled" {{ old('status', $assignment->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ old('status', $assignment->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="postponed" {{ old('status', $assignment->status) === 'postponed' ? 'selected' : '' }}>Postponed</option>
                                <option value="cancelled" {{ old('status', $assignment->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="result" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Result
                            </label>
                            <select name="result" id="result" 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Select Result</option>
                                <option value="passed" {{ old('result', $assignment->result) === 'passed' ? 'selected' : '' }}>Passed</option>
                                <option value="failed" {{ old('result', $assignment->result) === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="conditional" {{ old('result', $assignment->result) === 'conditional' ? 'selected' : '' }}>Conditional</option>
                                <option value="pending" {{ old('result', $assignment->result) === 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            @error('result')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Final Grade -->
                    <div class="mb-8">
                        <label for="final_grade" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Final Grade
                        </label>
                        <input type="number" name="final_grade" id="final_grade" 
                               value="{{ old('final_grade', $assignment->final_grade) }}" 
                               min="0" max="100" step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                               placeholder="Enter final grade (0-100)">
                        @error('final_grade')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Defense Instructions -->
                    <div class="mb-8">
                        <label for="defense_instructions" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Defense Instructions
                        </label>
                        <textarea name="defense_instructions" id="defense_instructions" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                  placeholder="Special instructions for the defense (optional)">{{ old('defense_instructions', $assignment->defense_instructions) }}</textarea>
                        @error('defense_instructions')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Panel Feedback -->
                    <div class="mb-8">
                        <label for="panel_feedback" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Panel Feedback
                        </label>
                        <textarea name="panel_feedback" id="panel_feedback" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                  placeholder="Panel feedback and comments (optional)">{{ old('panel_feedback', $assignment->panel_feedback) }}</textarea>
                        @error('panel_feedback')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.panel.show', $assignment) }}" 
                           class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200 font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white rounded-xl font-medium transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Assignment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Update panel member count
        function updateMemberCount() {
            const panelMembers = document.querySelectorAll('input[name="panel_members[]"]:checked');
            const countElement = document.getElementById('member-count');
            const count = panelMembers.length;
            
            countElement.textContent = `(${count} selected)`;
            
            if (count < 3) {
                countElement.className = 'text-xs text-red-500 dark:text-red-400 ml-2';
            } else if (count > 5) {
                countElement.className = 'text-xs text-orange-500 dark:text-orange-400 ml-2';
            } else {
                countElement.className = 'text-xs text-green-500 dark:text-green-400 ml-2';
            }
        }

        // Add event listeners to panel member checkboxes
        document.querySelectorAll('input[name="panel_members[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', updateMemberCount);
        });

        // Initialize count
        updateMemberCount();

        // Show/hide result field based on status
        document.getElementById('status').addEventListener('change', function() {
            const resultField = document.getElementById('result');
            const gradeField = document.getElementById('final_grade');
            
            if (this.value === 'completed') {
                resultField.parentElement.style.display = 'block';
                gradeField.parentElement.style.display = 'block';
                resultField.required = true;
            } else {
                resultField.parentElement.style.display = 'block';
                gradeField.parentElement.style.display = 'block';
                resultField.required = false;
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const panelMembers = document.querySelectorAll('input[name="panel_members[]"]:checked');
            const panelChair = document.getElementById('panel_chair_id').value;
            
            // Check if at least 3 panel members are selected
            if (panelMembers.length < 3) {
                e.preventDefault();
                alert('Please select at least 3 panel members.');
                return;
            }
            
            // Check if no more than 5 panel members are selected
            if (panelMembers.length > 5) {
                e.preventDefault();
                alert('Please select no more than 5 panel members.');
                return;
            }
            
            // Check if panel chair is selected
            if (!panelChair) {
                e.preventDefault();
                alert('Please select a panel chair.');
                return;
            }
            
            // Check if panel chair is one of the selected panel members
            const selectedMemberIds = Array.from(panelMembers).map(cb => cb.value);
            if (!selectedMemberIds.includes(panelChair)) {
                e.preventDefault();
                alert('Panel chair must be one of the selected panel members.');
                return;
            }
            
            // Check if result is required for completed status
            const status = document.getElementById('status').value;
            const result = document.getElementById('result').value;
            if (status === 'completed' && !result) {
                e.preventDefault();
                alert('Result is required when status is completed.');
                return;
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const statusField = document.getElementById('status');
            statusField.dispatchEvent(new Event('change'));
        });
    </script>
</x-app-layout>
