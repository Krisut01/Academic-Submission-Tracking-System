<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Evaluation Type -->
    <div>
        <label for="evaluation_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Evaluation Type <span class="text-red-500">*</span>
        </label>
        <select name="evaluation_type" id="evaluation_type" required
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            <option value="">Select Evaluation Type</option>
            <option value="Grade Verification" {{ old('evaluation_type') == 'Grade Verification' ? 'selected' : '' }}>Grade Verification</option>
            <option value="Credit Assessment" {{ old('evaluation_type') == 'Credit Assessment' ? 'selected' : '' }}>Credit Assessment</option>
            <option value="Transcript Request" {{ old('evaluation_type') == 'Transcript Request' ? 'selected' : '' }}>Transcript Request</option>
            <option value="Transfer Credit Evaluation" {{ old('evaluation_type') == 'Transfer Credit Evaluation' ? 'selected' : '' }}>Transfer Credit Evaluation</option>
            <option value="Academic Standing Review" {{ old('evaluation_type') == 'Academic Standing Review' ? 'selected' : '' }}>Academic Standing Review</option>
            <option value="Other" {{ old('evaluation_type') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('evaluation_type')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Current Year Level -->
    <div>
        <label for="year_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Current Year Level <span class="text-red-500">*</span>
        </label>
        <select name="year_level" id="year_level" required
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            <option value="">Select Year Level</option>
            <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
            <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
            <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
            <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
            <option value="5th Year" {{ old('year_level') == '5th Year' ? 'selected' : '' }}>5th Year</option>
            <option value="Graduate" {{ old('year_level') == 'Graduate' ? 'selected' : '' }}>Graduate</option>
        </select>
        @error('year_level')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Thesis Status -->
    <div class="md:col-span-2">
        <label for="thesis_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Thesis/Research Status
        </label>
        <select name="thesis_status" id="thesis_status"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            <option value="">Select Thesis Status</option>
            <option value="Not Started" {{ old('thesis_status') == 'Not Started' ? 'selected' : '' }}>Not Started</option>
            <option value="Proposal Stage" {{ old('thesis_status') == 'Proposal Stage' ? 'selected' : '' }}>Proposal Stage</option>
            <option value="Data Collection" {{ old('thesis_status') == 'Data Collection' ? 'selected' : '' }}>Data Collection</option>
            <option value="Writing Stage" {{ old('thesis_status') == 'Writing Stage' ? 'selected' : '' }}>Writing Stage</option>
            <option value="Under Review" {{ old('thesis_status') == 'Under Review' ? 'selected' : '' }}>Under Review</option>
            <option value="Defense Scheduled" {{ old('thesis_status') == 'Defense Scheduled' ? 'selected' : '' }}>Defense Scheduled</option>
            <option value="Completed" {{ old('thesis_status') == 'Completed' ? 'selected' : '' }}>Completed</option>
            <option value="Not Applicable" {{ old('thesis_status') == 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
        </select>
        @error('thesis_status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Completed Subjects -->
<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Completed Subjects for Evaluation
    </label>
    <div id="completed-subjects-container" class="space-y-3">
        <div class="subject-row flex gap-3">
            <input type="text" name="completed_subjects[]" placeholder="Subject Code (e.g., MEDTECH 101)"
                   class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            <select name="grades[]" 
                    class="w-24 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">Grade</option>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
                <option value="DRP">DRP</option>
            </select>
            <input type="number" name="subject_units[]" placeholder="Units" min="1" max="5"
                   class="w-20 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            <button type="button" onclick="removeCompletedSubjectRow(this)" 
                    class="px-3 py-3 text-red-600 hover:bg-red-50 dark:hover:bg-red-900 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    </div>
    <button type="button" onclick="addCompletedSubjectRow()" 
            class="mt-3 px-4 py-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-lg transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Add Another Subject
    </button>
    @error('completed_subjects.*')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Special Requirements -->
<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Special Requirements or Accommodations
    </label>
    <div class="space-y-3">
        <div class="flex items-center">
            <input type="checkbox" name="special_requirements[]" value="Rush Processing" id="rush"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="rush" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Rush Processing (Additional fees may apply)
            </label>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" name="special_requirements[]" value="Certified True Copy" id="certified"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="certified" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Certified True Copy
            </label>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" name="special_requirements[]" value="Multiple Copies" id="multiple"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="multiple" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Multiple Copies (Please specify quantity in remarks)
            </label>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" name="special_requirements[]" value="Electronic Copy" id="electronic"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="electronic" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Electronic Copy (PDF format)
            </label>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" name="special_requirements[]" value="Translation Required" id="translation"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="translation" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Translation Required
            </label>
        </div>
    </div>
    @error('special_requirements.*')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Evaluation Information -->
<div class="mt-6 p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg">
    <h5 class="text-sm font-semibold text-purple-800 dark:text-purple-300 mb-2 flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Evaluation Process Information
    </h5>
    <div class="text-sm text-purple-700 dark:text-purple-300 space-y-1">
        <p>• Academic evaluations are processed within 5-7 business days</p>
        <p>• Rush processing reduces time to 2-3 business days (additional fees apply)</p>
        <p>• Ensure all uploaded documents are clear and legible</p>
        <p>• For transfer credits, provide official transcripts from previous institutions</p>
        <p>• Contact the Registrar's Office for questions about specific requirements</p>
    </div>
</div>

<script>
function addCompletedSubjectRow() {
    const container = document.getElementById('completed-subjects-container');
    const newRow = document.createElement('div');
    newRow.className = 'subject-row flex gap-3';
    newRow.innerHTML = `
        <input type="text" name="completed_subjects[]" placeholder="Subject Code (e.g., MEDTECH 101)"
               class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
        <select name="grades[]" 
                class="w-24 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            <option value="">Grade</option>
            <option value="1.00">1.00</option>
            <option value="1.25">1.25</option>
            <option value="1.50">1.50</option>
            <option value="1.75">1.75</option>
            <option value="2.00">2.00</option>
            <option value="2.25">2.25</option>
            <option value="2.50">2.50</option>
            <option value="2.75">2.75</option>
            <option value="3.00">3.00</option>
            <option value="5.00">5.00</option>
            <option value="INC">INC</option>
            <option value="DRP">DRP</option>
        </select>
        <input type="number" name="subject_units[]" placeholder="Units" min="1" max="5"
               class="w-20 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
        <button type="button" onclick="removeCompletedSubjectRow(this)" 
                class="px-3 py-3 text-red-600 hover:bg-red-50 dark:hover:bg-red-900 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    `;
    container.appendChild(newRow);
}

function removeCompletedSubjectRow(button) {
    const row = button.closest('.subject-row');
    const container = document.getElementById('completed-subjects-container');
    if (container.children.length > 1) {
        row.remove();
    }
}
</script> 