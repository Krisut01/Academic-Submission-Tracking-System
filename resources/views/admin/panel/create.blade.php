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

                <form method="POST" action="{{ route('admin.panel.store') }}" class="p-6" id="panel-assignment-form">
                    @csrf
                    <input type="hidden" name="form_submitted" value="1">
                    
                    <!-- Hidden field to track the original student request -->
                    @if(isset($studentRequest))
                        <input type="hidden" name="from_request_id" value="{{ $studentRequest->id }}">
                    @endif


                    <!-- Student Request Information -->
                    @if(isset($studentRequest))
                        <div class="mb-6 p-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl">
                            <div class="flex items-start space-x-4">
                                <div class="p-2 bg-blue-500 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">
                                        📋 Creating Panel from Student Request
                                    </h4>
                                    <p class="text-sm text-blue-700 dark:text-blue-300 mb-3">
                                        This panel assignment is being created from <strong>{{ $studentRequest->user->name }}'s</strong> panel assignment request submitted on {{ $studentRequest->submission_date->format('F j, Y') }}.
                                    </p>
                                    
                                    @if(!empty($preferredPanelMembers))
                                        <div class="mt-4">
                                            <h5 class="font-medium text-blue-800 dark:text-blue-200 mb-2">Student's Preferred Panel Members:</h5>
                                            <div class="space-y-2">
                                                @foreach($preferredPanelMembers as $key => $member)
                                                    @if((isset($member['name']) && $member['name']) || (isset($member['id']) && $member['id']))
                                                        <div class="flex items-center space-x-2">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200">
                                                                {{ ucfirst(str_replace('_', ' ', str_replace('preferred_', '', $key))) }}
                                                            </span>
                                                            @if(isset($member['id']) && $member['id'])
                                                                @php
                                                                    $facultyMember = \App\Models\User::find($member['id']);
                                                                @endphp
                                                                @if($facultyMember)
                                                                    <span class="text-sm text-blue-700 dark:text-blue-300 font-medium">{{ $facultyMember->name }}</span>
                                                                    <span class="text-xs text-blue-600 dark:text-blue-400">({{ $facultyMember->email }})</span>
                                                                    <span class="text-xs bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300 px-2 py-1 rounded-full">✓ In System</span>
                                                                @else
                                                                    <span class="text-sm text-blue-700 dark:text-blue-300">{{ $member['name'] ?? 'Unknown Faculty' }}</span>
                                                                    <span class="text-xs bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 px-2 py-1 rounded-full">⚠ Not Found</span>
                                                                @endif
                                                            @elseif(isset($member['name']) && $member['name'])
                                                                <span class="text-sm text-blue-700 dark:text-blue-300">{{ $member['name'] }}</span>
                                                                <span class="text-xs bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300 px-2 py-1 rounded-full">Manual Entry</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-2">
                                                💡 These are suggestions from the student. Faculty members in the system will be automatically pre-selected below.
                                            </p>
                                        </div>
                                    @endif
                                    
                                    <!-- Student's Preferred Dates and Times -->
                                    @if($studentRequest->preferred_dates || $studentRequest->preferred_time || $studentRequest->preferred_venue)
                                        <div class="mt-4">
                                            <h5 class="font-medium text-blue-800 dark:text-blue-200 mb-2">📅 Student's Preferred Schedule:</h5>
                                            <div class="space-y-3">
                                                @if($studentRequest->preferred_dates && is_array($studentRequest->preferred_dates))
                                                    <div>
                                                        <h6 class="text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Preferred Dates:</h6>
                                                        <div class="space-y-1">
                                                            @foreach($studentRequest->preferred_dates as $index => $date)
                                                                @if($date)
                                                                    <div class="flex items-center space-x-2">
                                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200">
                                                                            Option {{ $index + 1 }}
                                                                        </span>
                                                                        <span class="text-sm text-blue-700 dark:text-blue-300 font-medium">
                                                                            {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
                                                                        </span>
                                                                        <span class="text-xs text-blue-600 dark:text-blue-400">
                                                                            ({{ \Carbon\Carbon::parse($date)->format('l') }})
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if($studentRequest->preferred_time)
                                                    <div>
                                                        <h6 class="text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Preferred Time:</h6>
                                                        <span class="text-sm text-blue-700 dark:text-blue-300 font-medium">{{ $studentRequest->preferred_time }}</span>
                                                    </div>
                                                @endif
                                                
                                                @if($studentRequest->preferred_venue)
                                                    <div>
                                                        <h6 class="text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Preferred Venue:</h6>
                                                        <span class="text-sm text-blue-700 dark:text-blue-300">{{ $studentRequest->preferred_venue }}</span>
                                                    </div>
                                                @endif
                                                
                                                @if($studentRequest->special_requirements)
                                                    <div>
                                                        <h6 class="text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Special Requirements:</h6>
                                                        <span class="text-sm text-blue-700 dark:text-blue-300">{{ $studentRequest->special_requirements }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-2">
                                                💡 Consider these preferences when scheduling the defense. You can use them as a starting point or choose different dates/times as needed.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

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
                                @php
                                    // Check if this faculty member is preferred by the student
                                    $isPreferred = false;
                                    $preferredRole = '';
                                    if (isset($preferredPanelMembers) && !empty($preferredPanelMembers)) {
                                        foreach ($preferredPanelMembers as $key => $member) {
                                            // Check by ID first (most accurate)
                                            if (isset($member['id']) && $member['id'] == $faculty->id) {
                                                $isPreferred = true;
                                                $preferredRole = ucfirst(str_replace('_', ' ', str_replace('preferred_', '', $key)));
                                                break;
                                            } 
                                            // Check by exact name match
                                            elseif (isset($member['name']) && !empty($member['name'])) {
                                                $memberName = trim($member['name']);
                                                $facultyName = trim($faculty->name);
                                                
                                                // Exact match (case-insensitive)
                                                if (strtolower($memberName) === strtolower($facultyName)) {
                                                    $isPreferred = true;
                                                    $preferredRole = ucfirst(str_replace('_', ' ', str_replace('preferred_', '', $key)));
                                                    break;
                                                }
                                                
                                                // Partial match (if faculty name contains the preferred name or vice versa)
                                                if (stripos($facultyName, $memberName) !== false || stripos($memberName, $facultyName) !== false) {
                                                    $isPreferred = true;
                                                    $preferredRole = ucfirst(str_replace('_', ' ', str_replace('preferred_', '', $key))) . ' (partial match)';
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    
                                    // Pre-select if preferred by student or previously selected
                                    $isChecked = in_array($faculty->id, old('panel_members', [])) || $isPreferred;
                                @endphp
                                
                                <label class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors {{ $isPreferred ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-600' : '' }}">
                                    <input type="checkbox" name="panel_members[]" value="{{ $faculty->id }}"
                                           {{ $isChecked ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $faculty->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $faculty->email }}</p>
                                            </div>
                                            @if($isPreferred)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200">
                                                    Student's {{ $preferredRole }}
                                                </span>
                                            @endif
                                        </div>
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
                                @php
                                    // Check if this faculty is the preferred chair
                                    $isPreferredChair = false;
                                    if (isset($preferredPanelMembers['preferred_chair'])) {
                                        $preferredChair = $preferredPanelMembers['preferred_chair'];
                                        // Check by ID first
                                        if (isset($preferredChair['id']) && $preferredChair['id'] == $faculty->id) {
                                            $isPreferredChair = true;
                                        }
                                        // Check by name match
                                        elseif (isset($preferredChair['name']) && !empty($preferredChair['name'])) {
                                            $chairName = trim($preferredChair['name']);
                                            $facultyName = trim($faculty->name);
                                            if (strtolower($chairName) === strtolower($facultyName) || 
                                                stripos($facultyName, $chairName) !== false || 
                                                stripos($chairName, $facultyName) !== false) {
                                                $isPreferredChair = true;
                                            }
                                        }
                                    }
                                    
                                    $isSelected = old('panel_chair_id') == $faculty->id || $isPreferredChair;
                                @endphp
                                <option value="{{ $faculty->id }}" 
                                        {{ $isSelected ? 'selected' : '' }}>
                                    {{ $faculty->name }} ({{ $faculty->email }}){{ $isPreferredChair ? ' ⭐ Student\'s Preferred Chair' : '' }}
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

                    <!-- Defense Type Selection -->
                    <div class="mb-8">
                        <label for="defense_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Defense Type *
                        </label>
                        <select name="defense_type" id="defense_type" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Select Defense Type</option>
                            <option value="proposal_defense" {{ old('defense_type') === 'proposal_defense' ? 'selected' : '' }}>Proposal Defense</option>
                            <option value="final_defense" {{ old('defense_type') === 'final_defense' ? 'selected' : '' }}>Final Defense</option>
                            <option value="redefense" {{ old('defense_type') === 'redefense' ? 'selected' : '' }}>Re-defense</option>
                            <option value="oral_defense" {{ old('defense_type') === 'oral_defense' ? 'selected' : '' }}>Oral Defense</option>
                            <option value="thesis_defense" {{ old('defense_type') === 'thesis_defense' ? 'selected' : '' }}>Thesis Defense</option>
                        </select>
                        @error('defense_type')
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

        // Form validation and double-submission prevention
        let formSubmitted = false;
        document.querySelector('form').addEventListener('submit', function(e) {
            // Prevent double submission
            if (formSubmitted) {
                e.preventDefault();
                return false;
            }
            
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
            
            // Mark form as submitted and disable submit button
            formSubmitted = true;
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<svg class="w-5 h-5 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Creating...';
            }
        });

        // Pre-fill defense date and venue with student's preferences
        @if(isset($studentRequest))
            // Pre-fill defense date
            @if($studentRequest->preferred_dates && is_array($studentRequest->preferred_dates) && !empty($studentRequest->preferred_dates[0]))
                const firstPreferredDate = '{{ $studentRequest->preferred_dates[0] }}';
                const preferredTime = '{{ $studentRequest->preferred_time ?? "09:00" }}';
                
                if (firstPreferredDate) {
                    // Convert date to datetime-local format
                    const date = new Date(firstPreferredDate);
                    const time = preferredTime.split(':');
                    date.setHours(parseInt(time[0]) || 9, parseInt(time[1]) || 0);
                    
                    const datetimeLocal = date.toISOString().slice(0, 16);
                    document.getElementById('defense_date').value = datetimeLocal;
                    
                    // Show a notification that the date was pre-filled
                    const dateField = document.getElementById('defense_date');
                    const dateNotification = document.createElement('div');
                    dateNotification.className = 'mt-2 p-2 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-sm rounded-lg';
                    dateNotification.innerHTML = '✅ Pre-filled with student\'s first preferred date. You can change this if needed.';
                    dateField.parentNode.appendChild(dateNotification);
                }
            @endif
            
            // Pre-fill defense venue
            @if($studentRequest->preferred_venue)
                const preferredVenue = '{{ $studentRequest->preferred_venue }}';
                if (preferredVenue) {
                    document.getElementById('defense_venue').value = preferredVenue;
                    
                    // Show a notification that the venue was pre-filled
                    const venueField = document.getElementById('defense_venue');
                    const venueNotification = document.createElement('div');
                    venueNotification.className = 'mt-2 p-2 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-sm rounded-lg';
                    venueNotification.innerHTML = '✅ Pre-filled with student\'s preferred venue. You can change this if needed.';
                    venueField.parentNode.appendChild(venueNotification);
                }
            @endif
        @endif
    </script>
</x-app-layout>
