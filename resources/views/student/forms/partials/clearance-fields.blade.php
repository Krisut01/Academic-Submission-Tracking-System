<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Clearance Type -->
    <div>
        <label for="clearance_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Clearance Type <span class="text-red-500">*</span>
        </label>
        <select name="clearance_type" id="clearance_type" required
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            <option value="">Select Clearance Type</option>
            <option value="Graduation" {{ old('clearance_type') == 'Graduation' ? 'selected' : '' }}>Graduation Clearance</option>
            <option value="Transfer" {{ old('clearance_type') == 'Transfer' ? 'selected' : '' }}>Transfer Clearance</option>
            <option value="Leave of Absence" {{ old('clearance_type') == 'Leave of Absence' ? 'selected' : '' }}>Leave of Absence</option>
            <option value="Employment" {{ old('clearance_type') == 'Employment' ? 'selected' : '' }}>Employment Clearance</option>
            <option value="Other" {{ old('clearance_type') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('clearance_type')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Expected Graduation Date -->
    <div>
        <label for="expected_graduation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Expected Graduation Date
        </label>
        <input type="date" name="expected_graduation" id="expected_graduation" value="{{ old('expected_graduation') }}"
               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
        @error('expected_graduation')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Year Level -->
<div class="mt-6">
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

<!-- Reason for Clearance -->
<div class="mt-6">
    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Reason for Clearance Request <span class="text-red-500">*</span>
    </label>
    <textarea name="reason" id="reason" rows="3" required
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              placeholder="Please explain why you need this clearance...">{{ old('reason') }}</textarea>
    @error('reason')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Outstanding Obligations -->
<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Outstanding Obligations (if any)
    </label>
    <div class="space-y-3">
        <div class="flex items-center">
            <input type="checkbox" name="outstanding_obligations[]" value="Library Books" id="library"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="library" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Library Books/Materials
            </label>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" name="outstanding_obligations[]" value="Laboratory Equipment" id="laboratory"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="laboratory" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Laboratory Equipment
            </label>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" name="outstanding_obligations[]" value="Financial Obligations" id="financial"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="financial" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Financial Obligations
            </label>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" name="outstanding_obligations[]" value="Student Organization Dues" id="organization"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="organization" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Student Organization Dues
            </label>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" name="outstanding_obligations[]" value="Thesis/Research Materials" id="thesis"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="thesis" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Thesis/Research Materials
            </label>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" name="outstanding_obligations[]" value="None" id="none"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="none" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                No Outstanding Obligations
            </label>
        </div>
    </div>
    @error('outstanding_obligations.*')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Clearance Checklist Information -->
<div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
    <h5 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-2 flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Clearance Process Information
    </h5>
    <div class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
        <p>• All outstanding obligations must be settled before clearance approval</p>
        <p>• Processing time: 3-5 business days</p>
        <p>• You will be notified via email once your clearance is processed</p>
        <p>• For urgent requests, please contact the Registrar's Office directly</p>
    </div>
</div> 
