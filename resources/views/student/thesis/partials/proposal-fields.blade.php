<!-- Thesis Proposal Specific Fields -->
<div class="form-section bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-8 py-6 bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Proposal Details</h3>
        </div>
    </div>
    
    <div class="p-8">
        <div class="field-group space-y-6">
            <!-- Abstract/Brief Description -->
            <div>
                <label for="abstract" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    Abstract / Brief Description <span class="text-red-500">*</span>
                </label>
                <textarea name="abstract" id="abstract" rows="6" required
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                          placeholder="Provide a comprehensive abstract of your research proposal including background, objectives, methodology, and expected outcomes...">{{ old('abstract') }}</textarea>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Minimum 200 words recommended</p>
                @error('abstract')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Research Area/Specialization -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="research_area" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Research Area / Specialization <span class="text-red-500">*</span>
                    </label>
                    <select name="research_area" id="research_area" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Select Research Area</option>
                        <option value="Software Engineering" {{ old('research_area') == 'Software Engineering' ? 'selected' : '' }}>Software Engineering</option>
                        <option value="Web Development" {{ old('research_area') == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                        <option value="Mobile Application Development" {{ old('research_area') == 'Mobile Application Development' ? 'selected' : '' }}>Mobile Application Development</option>
                        <option value="Database Management Systems" {{ old('research_area') == 'Database Management Systems' ? 'selected' : '' }}>Database Management Systems</option>
                        <option value="Network Security" {{ old('research_area') == 'Network Security' ? 'selected' : '' }}>Network Security</option>
                        <option value="Cybersecurity" {{ old('research_area') == 'Cybersecurity' ? 'selected' : '' }}>Cybersecurity</option>
                        <option value="Artificial Intelligence" {{ old('research_area') == 'Artificial Intelligence' ? 'selected' : '' }}>Artificial Intelligence</option>
                        <option value="Machine Learning" {{ old('research_area') == 'Machine Learning' ? 'selected' : '' }}>Machine Learning</option>
                        <option value="Data Science & Analytics" {{ old('research_area') == 'Data Science & Analytics' ? 'selected' : '' }}>Data Science & Analytics</option>
                        <option value="Information Systems Management" {{ old('research_area') == 'Information Systems Management' ? 'selected' : '' }}>Information Systems Management</option>
                        <option value="Human-Computer Interaction" {{ old('research_area') == 'Human-Computer Interaction' ? 'selected' : '' }}>Human-Computer Interaction</option>
                        <option value="Cloud Computing" {{ old('research_area') == 'Cloud Computing' ? 'selected' : '' }}>Cloud Computing</option>
                        <option value="Internet of Things (IoT)" {{ old('research_area') == 'Internet of Things (IoT)' ? 'selected' : '' }}>Internet of Things (IoT)</option>
                        <option value="Blockchain Technology" {{ old('research_area') == 'Blockchain Technology' ? 'selected' : '' }}>Blockchain Technology</option>
                        <option value="Game Development" {{ old('research_area') == 'Game Development' ? 'selected' : '' }}>Game Development</option>
                        <option value="Other" {{ old('research_area') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('research_area')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Research Methodology -->
                <div>
                    <label for="methodology" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Research Methodology <span class="text-red-500">*</span>
                    </label>
                    <select name="methodology" id="methodology" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Select Methodology</option>
                        <option value="Agile Development" {{ old('methodology') == 'Agile Development' ? 'selected' : '' }}>Agile Development</option>
                        <option value="Waterfall Model" {{ old('methodology') == 'Waterfall Model' ? 'selected' : '' }}>Waterfall Model</option>
                        <option value="Prototyping" {{ old('methodology') == 'Prototyping' ? 'selected' : '' }}>Prototyping</option>
                        <option value="Experimental Research" {{ old('methodology') == 'Experimental Research' ? 'selected' : '' }}>Experimental Research</option>
                        <option value="Comparative Analysis" {{ old('methodology') == 'Comparative Analysis' ? 'selected' : '' }}>Comparative Analysis</option>
                        <option value="Case Study" {{ old('methodology') == 'Case Study' ? 'selected' : '' }}>Case Study</option>
                        <option value="Survey Research" {{ old('methodology') == 'Survey Research' ? 'selected' : '' }}>Survey Research</option>
                        <option value="Design Science Research" {{ old('methodology') == 'Design Science Research' ? 'selected' : '' }}>Design Science Research</option>
                        <option value="Systematic Literature Review" {{ old('methodology') == 'Systematic Literature Review' ? 'selected' : '' }}>Systematic Literature Review</option>
                        <option value="Action Research" {{ old('methodology') == 'Action Research' ? 'selected' : '' }}>Action Research</option>
                        <option value="Mixed Methods" {{ old('methodology') == 'Mixed Methods' ? 'selected' : '' }}>Mixed Methods</option>
                        <option value="Qualitative Research" {{ old('methodology') == 'Qualitative Research' ? 'selected' : '' }}>Qualitative Research</option>
                        <option value="Quantitative Research" {{ old('methodology') == 'Quantitative Research' ? 'selected' : '' }}>Quantitative Research</option>
                        <option value="Performance Testing" {{ old('methodology') == 'Performance Testing' ? 'selected' : '' }}>Performance Testing</option>
                        <option value="Usability Testing" {{ old('methodology') == 'Usability Testing' ? 'selected' : '' }}>Usability Testing</option>
                    </select>
                    @error('methodology')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Research Timeline -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="expected_start_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Expected Start Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="expected_start_date" id="expected_start_date" value="{{ old('expected_start_date') }}" required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    @error('expected_start_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="expected_completion_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Expected Completion Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="expected_completion_date" id="expected_completion_date" value="{{ old('expected_completion_date') }}" required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    @error('expected_completion_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Research Keywords -->
            <div>
                <label for="keywords" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    Research Keywords <span class="text-red-500">*</span>
                </label>
                <input type="text" name="keywords" id="keywords" value="{{ old('keywords') }}" required
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                       placeholder="Enter 5-8 keywords separated by commas (e.g., software development, web application, database design, user interface)">
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Separate keywords with commas. These will help in categorizing and searching your research.</p>
                @error('keywords')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Research Objectives -->
            <div>
                <label for="research_objectives" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    Research Objectives <span class="text-red-500">*</span>
                </label>
                <textarea name="research_objectives" id="research_objectives" rows="5" required
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                          placeholder="List your general and specific research objectives. Use bullet points or numbered format for clarity...">{{ old('research_objectives') }}</textarea>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Clearly state both general and specific objectives of your research</p>
                @error('research_objectives')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Expected Outcomes -->
            <div>
                <label for="expected_outcomes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    Expected Outcomes <span class="text-red-500">*</span>
                </label>
                <textarea name="expected_outcomes" id="expected_outcomes" rows="4" required
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                          placeholder="Describe the expected outcomes and potential impact of your research...">{{ old('expected_outcomes') }}</textarea>
                @error('expected_outcomes')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>

<!-- Required Documents Information -->
<div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-800">
    <div class="flex items-start space-x-4">
        <div class="p-2 bg-blue-500 rounded-lg flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-3">Required Documents for Proposal Submission:</h4>
            <ul class="text-sm text-blue-800 dark:text-blue-400 space-y-2">
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Complete research proposal document (PDF format preferred)
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Literature review and related works analysis
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    System design and architecture diagrams (if applicable)
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Project timeline and development methodology
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Technical specifications and requirements documentation
                </li>
            </ul>
        </div>
    </div>
</div> 