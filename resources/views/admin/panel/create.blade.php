<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📅 {{ __('Schedule Thesis Defense') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.panel') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Panel Assignments
                </a>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Schedule New Defense</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Assign panel members and schedule thesis defense</p>
                </div>

                <form method="POST" action="{{ route('admin.panel.store') }}" class="p-6">
                    @csrf

                    <!-- Student and Thesis Selection -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Student Selection -->
                        <div>
                            <label for="student_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Student *
                            </label>
                            <select name="student_id" id="student_id" required 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Select Student</option>
                                @foreach($eligibleStudents as $eligibleStudent)
                                    <option value="{{ $eligibleStudent->id }}" 
                                            {{ (old('student_id', $student->id ?? '') == $eligibleStudent->id) ? 'selected' : '' }}>
                                        {{ $eligibleStudent->name }} ({{ $eligibleStudent->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Thesis Document Selection -->
                        <div>
                            <label for="thesis_document_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Thesis Document *
                            </label>
                            <select name="thesis_document_id" id="thesis_document_id" required 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Select Thesis Document</option>
                                @if(isset($thesisDocument))
                                    <option value="{{ $thesisDocument->id }}" selected>
                                        {{ $thesisDocument->title }}
                                    </option>
                                @endif
                            </select>
                            @error('thesis_document_id')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Thesis Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="thesis_title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Thesis Title *
                            </label>
                            <input type="text" name="thesis_title" id="thesis_title" 
                                   value="{{ old('thesis_title', $thesisDocument->title ?? '') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                   placeholder="Enter thesis title">
                            @error('thesis_title')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="thesis_description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Thesis Description
                            </label>
                            <textarea name="thesis_description" id="thesis_description" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                      placeholder="Brief description of the thesis">{{ old('thesis_description', $thesisDocument->description ?? '') }}</textarea>
                            @error('thesis_description')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                                           {{ in_array($faculty->id, old('panel_members', [])) ? 'checked' : '' }}
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
                                        {{ old('panel_chair_id') == $faculty->id ? 'selected' : '' }}>
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
                                        {{ old('secretary_id') == $faculty->id ? 'selected' : '' }}>
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
                                   value="{{ old('defense_date') }}" required
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
                                   value="{{ old('defense_venue') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                   placeholder="e.g., Room 301, Main Building">
                            @error('defense_venue')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Defense Instructions -->
                    <div class="mb-8">
                        <label for="defense_instructions" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Defense Instructions
                        </label>
                        <textarea name="defense_instructions" id="defense_instructions" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                  placeholder="Special instructions for the defense (optional)">{{ old('defense_instructions') }}</textarea>
                        @error('defense_instructions')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.panel') }}" 
                           class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200 font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white rounded-xl font-medium transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Schedule Defense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-populate thesis information when student is selected
        document.getElementById('student_id').addEventListener('change', function() {
            const studentId = this.value;
            const thesisSelect = document.getElementById('thesis_document_id');
            const titleInput = document.getElementById('thesis_title');
            const descriptionInput = document.getElementById('thesis_description');
            
            if (studentId) {
                // Fetch thesis documents for selected student
                fetch(`/admin/panel/api/student-thesis/${studentId}`)
                    .then(response => response.json())
                    .then(data => {
                        thesisSelect.innerHTML = '<option value="">Select Thesis Document</option>';
                        data.forEach(thesis => {
                            const option = document.createElement('option');
                            option.value = thesis.id;
                            option.textContent = thesis.title;
                            thesisSelect.appendChild(option);
                        });
                    });
            }
        });

        // Auto-populate title and description when thesis is selected
        document.getElementById('thesis_document_id').addEventListener('change', function() {
            const thesisId = this.value;
            const titleInput = document.getElementById('thesis_title');
            const descriptionInput = document.getElementById('thesis_description');
            
            if (thesisId) {
                // Fetch thesis details
                fetch(`/admin/panel/api/thesis-details/${thesisId}`)
                    .then(response => response.json())
                    .then(data => {
                        titleInput.value = data.title || '';
                        descriptionInput.value = data.description || '';
                    });
            }
        });

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
        });
    </script>
</x-app-layout>
