<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Year Level -->
    <div>
        <label for="year_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Year Level <span class="text-red-500">*</span>
        </label>
        <select name="year_level" id="year_level" required
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            <option value="">Select Year Level</option>
            <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
            <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
            <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
            <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
            <option value="5th Year" {{ old('year_level') == '5th Year' ? 'selected' : '' }}>5th Year</option>
        </select>
        @error('year_level')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Total Units -->
    <div>
        <label for="units_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Total Units to Enroll
        </label>
        <input type="number" name="units_total" id="units_total" value="{{ old('units_total') }}" min="1" max="30"
               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
               placeholder="Enter total units">
        @error('units_total')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Previous GPA -->
    <div>
        <label for="previous_gpa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Previous Semester GPA
        </label>
        <input type="number" name="previous_gpa" id="previous_gpa" value="{{ old('previous_gpa') }}" step="0.01" min="1.0" max="4.0"
               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
               placeholder="e.g., 3.50">
        @error('previous_gpa')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Scholarship Status -->
    <div>
        <label for="scholarship_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Scholarship Status
        </label>
        <select name="scholarship_status" id="scholarship_status"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            <option value="">Select Scholarship Status</option>
            <option value="None" {{ old('scholarship_status') == 'None' ? 'selected' : '' }}>None</option>
            <option value="Academic Scholar" {{ old('scholarship_status') == 'Academic Scholar' ? 'selected' : '' }}>Academic Scholar</option>
            <option value="Athletic Scholar" {{ old('scholarship_status') == 'Athletic Scholar' ? 'selected' : '' }}>Athletic Scholar</option>
            <option value="Financial Aid" {{ old('scholarship_status') == 'Financial Aid' ? 'selected' : '' }}>Financial Aid</option>
            <option value="Other" {{ old('scholarship_status') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('scholarship_status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Subjects to Enroll -->
<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Subjects to Enroll <span class="text-red-500">*</span>
    </label>
    <div id="subjects-container" class="space-y-3">
        <div class="subject-row flex gap-3">
            <input type="text" name="subjects[]" placeholder="Subject Code (e.g., MEDTECH 101)"
                   class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            <input type="number" name="subject_units[]" placeholder="Units" min="1" max="5"
                   class="w-20 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            <button type="button" onclick="removeSubjectRow(this)" 
                    class="px-3 py-3 text-red-600 hover:bg-red-50 dark:hover:bg-red-900 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    </div>
    <button type="button" onclick="addSubjectRow()" 
            class="mt-3 px-4 py-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-lg transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Add Another Subject
    </button>
    @error('subjects.*')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<script>
function addSubjectRow() {
    const container = document.getElementById('subjects-container');
    const newRow = document.createElement('div');
    newRow.className = 'subject-row flex gap-3';
    newRow.innerHTML = `
        <input type="text" name="subjects[]" placeholder="Subject Code (e.g., MEDTECH 101)"
               class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
        <input type="number" name="subject_units[]" placeholder="Units" min="1" max="5"
               class="w-20 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
        <button type="button" onclick="removeSubjectRow(this)" 
                class="px-3 py-3 text-red-600 hover:bg-red-50 dark:hover:bg-red-900 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    `;
    container.appendChild(newRow);
}

function removeSubjectRow(button) {
    const row = button.closest('.subject-row');
    const container = document.getElementById('subjects-container');
    if (container.children.length > 1) {
        row.remove();
    }
}
</script> 
