<!-- Approval Sheet Specific Fields -->
<div class="form-section bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-8 py-6 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Approval Details</h3>
        </div>
    </div>
    
    <div class="p-8">
        <div class="field-group space-y-6">
            <!-- Panel Members -->
            <div>
                <label for="panel_members_input" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    Panel Members <span class="text-red-500">*</span>
                </label>
                <div class="space-y-4">
                    <!-- Panel Chair -->
                    <div>
                        <label for="panel_chair" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Panel Chair</label>
                        <input type="text" name="panel_chair" id="panel_chair" value="{{ old('panel_chair') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                               placeholder="Enter panel chair name">
                        @error('panel_chair')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Panel Member 1 -->
                    <div>
                        <label for="panel_member_1" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Panel Member 1</label>
                        <input type="text" name="panel_member_1" id="panel_member_1" value="{{ old('panel_member_1') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                               placeholder="Enter panel member name">
                        @error('panel_member_1')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Panel Member 2 -->
                    <div>
                        <label for="panel_member_2" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Panel Member 2</label>
                        <input type="text" name="panel_member_2" id="panel_member_2" value="{{ old('panel_member_2') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                               placeholder="Enter panel member name">
                        @error('panel_member_2')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Additional Panel Members (Optional) -->
                    <div id="additional-panel-members">
                        <!-- Dynamic panel members will be added here -->
                    </div>

                    <button type="button" id="add-panel-member" 
                            class="inline-flex items-center px-4 py-2 border border-green-300 dark:border-green-600 rounded-lg text-green-700 dark:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Additional Panel Member
                    </button>
                </div>
            </div>

            <!-- Approval Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date of Approval -->
                <div>
                    <label for="approval_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Date of Approval <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="approval_date" id="approval_date" value="{{ old('approval_date') }}" required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    @error('approval_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Approval Status -->
                <div>
                    <label for="approval_status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Approval Status <span class="text-red-500">*</span>
                    </label>
                    <select name="approval_status" id="approval_status" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Select Status</option>
                        <option value="Fully Approved" {{ old('approval_status') == 'Fully Approved' ? 'selected' : '' }}>Fully Approved</option>
                        <option value="Approved with Minor Revisions" {{ old('approval_status') == 'Approved with Minor Revisions' ? 'selected' : '' }}>Approved with Minor Revisions</option>
                        <option value="Approved with Major Revisions" {{ old('approval_status') == 'Approved with Major Revisions' ? 'selected' : '' }}>Approved with Major Revisions</option>
                        <option value="Conditional Approval" {{ old('approval_status') == 'Conditional Approval' ? 'selected' : '' }}>Conditional Approval</option>
                    </select>
                    @error('approval_status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Defense Information -->
            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-2xl p-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Defense Information</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Defense Date -->
                    <div>
                        <label for="defense_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Defense Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="defense_date" id="defense_date" value="{{ old('defense_date') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        @error('defense_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Defense Time -->
                    <div>
                        <label for="defense_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Defense Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="defense_time" id="defense_time" value="{{ old('defense_time') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        @error('defense_time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Defense Venue -->
                <div class="mt-6">
                    <label for="defense_venue" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Defense Venue <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="defense_venue" id="defense_venue" value="{{ old('defense_venue') }}" required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                           placeholder="Enter the venue for thesis defense (e.g., Conference Room A, Online via Zoom)">
                    @error('defense_venue')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Committee Recommendations -->
            <div>
                <label for="committee_recommendations" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    Committee Recommendations <span class="text-red-500">*</span>
                </label>
                <textarea name="committee_recommendations" id="committee_recommendations" rows="5" required
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                          placeholder="Enter any recommendations, revisions, or conditions provided by the committee...">{{ old('committee_recommendations') }}</textarea>
                @error('committee_recommendations')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Final Grade/Rating (if applicable) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="final_grade" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Final Grade/Rating (Optional)
                    </label>
                    <select name="final_grade" id="final_grade"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Select Grade</option>
                        <option value="Excellent" {{ old('final_grade') == 'Excellent' ? 'selected' : '' }}>Excellent</option>
                        <option value="Very Good" {{ old('final_grade') == 'Very Good' ? 'selected' : '' }}>Very Good</option>
                        <option value="Good" {{ old('final_grade') == 'Good' ? 'selected' : '' }}>Good</option>
                        <option value="Satisfactory" {{ old('final_grade') == 'Satisfactory' ? 'selected' : '' }}>Satisfactory</option>
                        <option value="Needs Improvement" {{ old('final_grade') == 'Needs Improvement' ? 'selected' : '' }}>Needs Improvement</option>
                    </select>
                    @error('final_grade')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="approval_validity" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Approval Validity Period
                    </label>
                    <select name="approval_validity" id="approval_validity"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Select Validity</option>
                        <option value="6 months" {{ old('approval_validity') == '6 months' ? 'selected' : '' }}>6 months</option>
                        <option value="1 year" {{ old('approval_validity') == '1 year' ? 'selected' : '' }}>1 year</option>
                        <option value="2 years" {{ old('approval_validity') == '2 years' ? 'selected' : '' }}>2 years</option>
                        <option value="Indefinite" {{ old('approval_validity') == 'Indefinite' ? 'selected' : '' }}>Indefinite</option>
                    </select>
                    @error('approval_validity')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Required Documents Information -->
<div class="bg-green-50 dark:bg-green-900/20 rounded-2xl p-6 border border-green-200 dark:border-green-800">
    <div class="flex items-start space-x-4">
        <div class="p-2 bg-green-500 rounded-lg flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <h4 class="font-semibold text-green-900 dark:text-green-300 mb-3">Required Documents for Approval Sheet Submission:</h4>
            <ul class="text-sm text-green-800 dark:text-green-400 space-y-2">
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Signed approval sheet with all panel member signatures (PDF/Image)
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Defense schedule and venue confirmation
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Panel evaluation forms and recommendations
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Final approval status documentation
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Any conditional requirements or revision notes
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let panelMemberCount = 3; // Starting count (chair + 2 members)
    
    document.getElementById('add-panel-member').addEventListener('click', function() {
        const container = document.getElementById('additional-panel-members');
        const newMemberDiv = document.createElement('div');
        newMemberDiv.className = 'flex items-center space-x-4';
        newMemberDiv.innerHTML = `
            <div class="flex-1">
                <label for="panel_member_${panelMemberCount}" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Panel Member ${panelMemberCount}</label>
                <input type="text" name="panel_member_${panelMemberCount}" id="panel_member_${panelMemberCount}"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                       placeholder="Enter panel member name">
            </div>
            <button type="button" class="remove-panel-member mt-6 p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        `;
        
        container.appendChild(newMemberDiv);
        panelMemberCount++;
        
        // Add remove functionality
        newMemberDiv.querySelector('.remove-panel-member').addEventListener('click', function() {
            newMemberDiv.remove();
        });
    });
});
</script> 