<!-- Final Manuscript Specific Fields -->
<div class="form-section bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-8 py-6 bg-gradient-to-r from-violet-50 to-violet-100 dark:from-violet-900/20 dark:to-violet-800/20 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C20.168 18.477 18.754 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Final Manuscript Details</h3>
        </div>
    </div>
    
    <div class="p-8">
        <div class="field-group space-y-6">
            <!-- Completion Status -->
            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-2xl p-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Manuscript Completion Status</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Final Revisions Completed -->
                    <div>
                        <label for="final_revisions_completed" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Final Revisions Completed? <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center">
                                <input type="radio" name="final_revisions_completed" value="1" {{ old('final_revisions_completed') == '1' ? 'checked' : '' }} required
                                       class="w-4 h-4 text-violet-600 border-gray-300 focus:ring-violet-500">
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Yes</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="final_revisions_completed" value="0" {{ old('final_revisions_completed') == '0' ? 'checked' : '' }} required
                                       class="w-4 h-4 text-violet-600 border-gray-300 focus:ring-violet-500">
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">No</span>
                            </label>
                        </div>
                        @error('final_revisions_completed')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Manuscript Status -->
                    <div>
                        <label for="manuscript_status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Manuscript Status <span class="text-red-500">*</span>
                        </label>
                        <select name="manuscript_status" id="manuscript_status" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Select Status</option>
                            <option value="Ready for Final Defense" {{ old('manuscript_status') == 'Ready for Final Defense' ? 'selected' : '' }}>Ready for Final Defense</option>
                            <option value="Awaiting Final Approval" {{ old('manuscript_status') == 'Awaiting Final Approval' ? 'selected' : '' }}>Awaiting Final Approval</option>
                            <option value="Requires Minor Revisions" {{ old('manuscript_status') == 'Requires Minor Revisions' ? 'selected' : '' }}>Requires Minor Revisions</option>
                            <option value="Complete - Ready for Publication" {{ old('manuscript_status') == 'Complete - Ready for Publication' ? 'selected' : '' }}>Complete - Ready for Publication</option>
                        </select>
                        @error('manuscript_status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Manuscript Details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Pages -->
                <div>
                    <label for="total_pages" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Total Pages <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="total_pages" id="total_pages" value="{{ old('total_pages') }}" required min="1"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                           placeholder="e.g., 120">
                    @error('total_pages')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Chapters -->
                <div>
                    <label for="total_chapters" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Total Chapters <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="total_chapters" id="total_chapters" value="{{ old('total_chapters') }}" required min="1"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                           placeholder="e.g., 5">
                    @error('total_chapters')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Word Count -->
                <div>
                    <label for="word_count" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Approximate Word Count
                    </label>
                    <input type="number" name="word_count" id="word_count" value="{{ old('word_count') }}" min="1000"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                           placeholder="e.g., 15000">
                    @error('word_count')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Chapter Completion Checklist -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    Chapters Completed <span class="text-red-500">*</span>
                </label>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Check all chapters that are completed and included in the manuscript</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="chapters_completed[]" value="Abstract" {{ in_array('Abstract', old('chapters_completed', [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Abstract</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="chapters_completed[]" value="Chapter 1: Introduction" {{ in_array('Chapter 1: Introduction', old('chapters_completed', [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Chapter 1: Introduction</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="chapters_completed[]" value="Chapter 2: Literature Review" {{ in_array('Chapter 2: Literature Review', old('chapters_completed', [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Chapter 2: Literature Review</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="chapters_completed[]" value="Chapter 3: Methodology" {{ in_array('Chapter 3: Methodology', old('chapters_completed', [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Chapter 3: Methodology</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="chapters_completed[]" value="Chapter 4: Results" {{ in_array('Chapter 4: Results', old('chapters_completed', [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Chapter 4: Results</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="chapters_completed[]" value="Chapter 5: Discussion" {{ in_array('Chapter 5: Discussion', old('chapters_completed', [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Chapter 5: Discussion</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="chapters_completed[]" value="Chapter 6: Conclusion" {{ in_array('Chapter 6: Conclusion', old('chapters_completed', [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Chapter 6: Conclusion</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="chapters_completed[]" value="References/Bibliography" {{ in_array('References/Bibliography', old('chapters_completed', [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">References/Bibliography</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="chapters_completed[]" value="Appendices" {{ in_array('Appendices', old('chapters_completed', [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Appendices</span>
                    </label>
                </div>
                @error('chapters_completed')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Plagiarism Check Information -->
            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-2xl p-6 border border-yellow-200 dark:border-yellow-800">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Plagiarism Check Details</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Has Plagiarism Report -->
                    <div>
                        <label for="has_plagiarism_report" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Turnitin/Plagiarism Report Available? <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center">
                                <input type="radio" name="has_plagiarism_report" value="1" {{ old('has_plagiarism_report') == '1' ? 'checked' : '' }} required
                                       class="w-4 h-4 text-violet-600 border-gray-300 focus:ring-violet-500">
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Yes</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="has_plagiarism_report" value="0" {{ old('has_plagiarism_report') == '0' ? 'checked' : '' }} required
                                       class="w-4 h-4 text-violet-600 border-gray-300 focus:ring-violet-500">
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">No</span>
                            </label>
                        </div>
                        @error('has_plagiarism_report')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Plagiarism Percentage -->
                    <div id="plagiarism_percentage_div" style="display: none;">
                        <label for="plagiarism_percentage" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Similarity Percentage <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="plagiarism_percentage" id="plagiarism_percentage" value="{{ old('plagiarism_percentage') }}" 
                                   min="0" max="100" step="0.1"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                   placeholder="e.g., 15.5">
                            <span class="absolute right-3 top-3 text-gray-500">%</span>
                        </div>
                        @error('plagiarism_percentage')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Plagiarism Check Details -->
                <div id="plagiarism_details_div" style="display: none;" class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="plagiarism_tool" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                Plagiarism Check Tool Used
                            </label>
                            <select name="plagiarism_tool" id="plagiarism_tool"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Select Tool</option>
                                <option value="Turnitin" {{ old('plagiarism_tool') == 'Turnitin' ? 'selected' : '' }}>Turnitin</option>
                                <option value="Grammarly" {{ old('plagiarism_tool') == 'Grammarly' ? 'selected' : '' }}>Grammarly</option>
                                <option value="Copyscape" {{ old('plagiarism_tool') == 'Copyscape' ? 'selected' : '' }}>Copyscape</option>
                                <option value="Plagiarism Checker X" {{ old('plagiarism_tool') == 'Plagiarism Checker X' ? 'selected' : '' }}>Plagiarism Checker X</option>
                                <option value="Other" {{ old('plagiarism_tool') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('plagiarism_tool')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="plagiarism_check_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                Date of Plagiarism Check
                            </label>
                            <input type="date" name="plagiarism_check_date" id="plagiarism_check_date" value="{{ old('plagiarism_check_date') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            @error('plagiarism_check_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formatting and Compliance -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    Formatting and Compliance Checklist <span class="text-red-500">*</span>
                </label>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Confirm that your manuscript meets all formatting requirements</p>
                
                <div class="space-y-3">
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="formatting_compliance[]" value="proper_font_and_spacing" required
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Proper font, font size, and line spacing used</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="formatting_compliance[]" value="correct_margins" required
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Correct margins and page layout</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="formatting_compliance[]" value="proper_citations" required
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Proper citation format (APA, MLA, etc.)</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="formatting_compliance[]" value="complete_bibliography" required
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Complete and accurate bibliography/references</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="formatting_compliance[]" value="numbered_pages" required
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Pages properly numbered</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="checkbox" name="formatting_compliance[]" value="table_of_contents" required
                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Table of contents included</span>
                    </label>
                </div>
                @error('formatting_compliance')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Additional Manuscript Information -->
            <div>
                <label for="manuscript_notes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    Additional Notes or Comments
                </label>
                <textarea name="manuscript_notes" id="manuscript_notes" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                          placeholder="Any additional information about your manuscript, special considerations, or notes for reviewers...">{{ old('manuscript_notes') }}</textarea>
                @error('manuscript_notes')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>

<!-- Required Documents Information -->
<div class="bg-violet-50 dark:bg-violet-900/20 rounded-2xl p-6 border border-violet-200 dark:border-violet-800">
    <div class="flex items-start space-x-4">
        <div class="p-2 bg-violet-500 rounded-lg flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <h4 class="font-semibold text-violet-900 dark:text-violet-300 mb-3">Required Documents for Final Manuscript Submission:</h4>
            <ul class="text-sm text-violet-800 dark:text-violet-400 space-y-2">
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-violet-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Complete final manuscript (PDF format preferred)
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-violet-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Plagiarism check report (Turnitin or equivalent)
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-violet-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    All chapters in proper format and sequence
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-violet-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Supervisor's final approval letter
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-violet-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Copyright and ethics clearance documentation
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const plagiarismReportRadios = document.querySelectorAll('input[name="has_plagiarism_report"]');
    const plagiarismPercentageDiv = document.getElementById('plagiarism_percentage_div');
    const plagiarismDetailsDiv = document.getElementById('plagiarism_details_div');
    const plagiarismPercentageInput = document.getElementById('plagiarism_percentage');

    function togglePlagiarismFields() {
        const hasReport = document.querySelector('input[name="has_plagiarism_report"]:checked')?.value;
        
        if (hasReport === '1') {
            plagiarismPercentageDiv.style.display = 'block';
            plagiarismDetailsDiv.style.display = 'block';
            plagiarismPercentageInput.required = true;
        } else {
            plagiarismPercentageDiv.style.display = 'none';
            plagiarismDetailsDiv.style.display = 'none';
            plagiarismPercentageInput.required = false;
            plagiarismPercentageInput.value = '';
        }
    }

    plagiarismReportRadios.forEach(radio => {
        radio.addEventListener('change', togglePlagiarismFields);
    });

    // Initialize on page load
    togglePlagiarismFields();
});
</script> 