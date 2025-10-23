<!-- Panel Assignment Specific Fields -->
<div class="form-section bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-8 py-6 bg-gradient-to-r from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Panel Assignment Request</h3>
        </div>
    </div>
    
    <div class="p-8">
        <div class="field-group space-y-6">
            <!-- Defense Type -->
            <div>
                <label for="defense_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-100 mb-3">
                    Defense Type <span class="text-red-500">*</span>
                </label>
                <select name="defense_type" id="defense_type" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Select Defense Type</option>
                    <option value="Proposal Defense" {{ old('defense_type') == 'Proposal Defense' ? 'selected' : '' }}>Proposal Defense</option>
                    <option value="Final Defense" {{ old('defense_type') == 'Final Defense' ? 'selected' : '' }}>Final Defense</option>
                    <option value="Comprehensive Defense" {{ old('defense_type') == 'Comprehensive Defense' ? 'selected' : '' }}>Comprehensive Defense</option>
                </select>
                @error('defense_type')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Preferred Panel Members -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-100 mb-3">
                    Preferred Panel Members (Optional)
                </label>
                <p class="text-sm text-gray-500 dark:text-gray-300 mb-4">
                    These are your preferred panel members. The final assignment will be made by the administration based on availability and expertise.
                </p>
                
                <div class="space-y-4">
                    <!-- Preferred Panel Chair -->
                    <div>
                        <label for="preferred_panel_chair" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-2">Preferred Panel Chair</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <select name="preferred_panel_chair_id" id="preferred_panel_chair_id"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Select from Faculty List</option>
                                @foreach(\App\Models\User::where('role', 'faculty')->orderBy('name')->get() as $faculty)
                                    <option value="{{ $faculty->id }}" {{ old('preferred_panel_chair_id') == $faculty->id ? 'selected' : '' }}>
                                        {{ $faculty->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="preferred_panel_chair" id="preferred_panel_chair" value="{{ old('preferred_panel_chair') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                   placeholder="Or enter name manually">
                        </div>
                        @error('preferred_panel_chair')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preferred Panel Member 1 -->
                    <div>
                        <label for="preferred_panel_member_1" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-2">Preferred Panel Member 1</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <select name="preferred_panel_member_1_id" id="preferred_panel_member_1_id"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Select from Faculty List</option>
                                @foreach(\App\Models\User::where('role', 'faculty')->orderBy('name')->get() as $faculty)
                                    <option value="{{ $faculty->id }}" {{ old('preferred_panel_member_1_id') == $faculty->id ? 'selected' : '' }}>
                                        {{ $faculty->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="preferred_panel_member_1" id="preferred_panel_member_1" value="{{ old('preferred_panel_member_1') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                   placeholder="Or enter name manually">
                        </div>
                        @error('preferred_panel_member_1')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preferred Panel Member 2 -->
                    <div>
                        <label for="preferred_panel_member_2" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-2">Preferred Panel Member 2</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <select name="preferred_panel_member_2_id" id="preferred_panel_member_2_id"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Select from Faculty List</option>
                                @foreach(\App\Models\User::where('role', 'faculty')->orderBy('name')->get() as $faculty)
                                    <option value="{{ $faculty->id }}" {{ old('preferred_panel_member_2_id') == $faculty->id ? 'selected' : '' }}>
                                        {{ $faculty->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="preferred_panel_member_2" id="preferred_panel_member_2" value="{{ old('preferred_panel_member_2') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                   placeholder="Or enter name manually">
                        </div>
                        @error('preferred_panel_member_2')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Specialization Areas -->
            <div>
                <label for="required_specializations" class="block text-sm font-semibold text-gray-700 dark:text-gray-100 mb-3">
                    Required Specialization Areas <span class="text-red-500">*</span>
                </label>
                <textarea name="required_specializations" id="required_specializations" rows="4" required
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-300"
                          placeholder="List the specific areas of expertise required from panel members for your thesis (e.g., Clinical Research, Laboratory Medicine, Statistics, etc.)">{{ old('required_specializations') }}</textarea>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                    Be specific about the expertise areas needed to properly evaluate your research
                </p>
                @error('required_specializations')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Requested Schedule -->
            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-2xl p-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Preferred Defense Schedule</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Preferred Date 1 -->
                    <div>
                        <label for="preferred_date_1" class="block text-sm font-semibold text-gray-700 dark:text-gray-100 mb-3">
                            1st Choice Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="preferred_date_1" id="preferred_date_1" value="{{ old('preferred_date_1') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        @error('preferred_date_1')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preferred Date 2 -->
                    <div>
                        <label for="preferred_date_2" class="block text-sm font-semibold text-gray-700 dark:text-gray-100 mb-3">
                            2nd Choice Date
                        </label>
                        <input type="date" name="preferred_date_2" id="preferred_date_2" value="{{ old('preferred_date_2') }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        @error('preferred_date_2')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preferred Date 3 -->
                    <div>
                        <label for="preferred_date_3" class="block text-sm font-semibold text-gray-700 dark:text-gray-100 mb-3">
                            3rd Choice Date
                        </label>
                        <input type="date" name="preferred_date_3" id="preferred_date_3" value="{{ old('preferred_date_3') }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        @error('preferred_date_3')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preferred Time -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="preferred_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-100 mb-3">
                            Preferred Time <span class="text-red-500">*</span>
                        </label>
                        <select name="preferred_time" id="preferred_time" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Select Preferred Time</option>
                            <option value="Morning (8:00 AM - 12:00 PM)" {{ old('preferred_time') == 'Morning (8:00 AM - 12:00 PM)' ? 'selected' : '' }}>Morning (8:00 AM - 12:00 PM)</option>
                            <option value="Afternoon (1:00 PM - 5:00 PM)" {{ old('preferred_time') == 'Afternoon (1:00 PM - 5:00 PM)' ? 'selected' : '' }}>Afternoon (1:00 PM - 5:00 PM)</option>
                            <option value="Evening (6:00 PM - 8:00 PM)" {{ old('preferred_time') == 'Evening (6:00 PM - 8:00 PM)' ? 'selected' : '' }}>Evening (6:00 PM - 8:00 PM)</option>
                            <option value="Flexible" {{ old('preferred_time') == 'Flexible' ? 'selected' : '' }}>Flexible</option>
                        </select>
                        @error('preferred_time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="preferred_venue" class="block text-sm font-semibold text-gray-700 dark:text-gray-100 mb-3">
                            Preferred Venue Type
                        </label>
                        <select name="preferred_venue" id="preferred_venue"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Select Venue Type</option>
                            <option value="Face-to-Face" {{ old('preferred_venue') == 'Face-to-Face' ? 'selected' : '' }}>Face-to-Face</option>
                            <option value="Online/Virtual" {{ old('preferred_venue') == 'Online/Virtual' ? 'selected' : '' }}>Online/Virtual</option>
                            <option value="Hybrid" {{ old('preferred_venue') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                            <option value="No Preference" {{ old('preferred_venue') == 'No Preference' ? 'selected' : '' }}>No Preference</option>
                        </select>
                        @error('preferred_venue')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Special Requirements -->
            <div>
                <label for="special_requirements" class="block text-sm font-semibold text-gray-700 dark:text-gray-100 mb-3">
                    Special Requirements or Accommodations
                </label>
                <textarea name="special_requirements" id="special_requirements" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                          placeholder="Describe any special requirements, equipment needs, accessibility accommodations, or other considerations for the defense...">{{ old('special_requirements') }}</textarea>
                @error('special_requirements')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Justification for Panel Request -->
            <div>
                <label for="panel_justification" class="block text-sm font-semibold text-gray-700 dark:text-gray-100 mb-3">
                    Justification for Panel Assignment Request <span class="text-red-500">*</span>
                </label>
                <textarea name="panel_justification" id="panel_justification" rows="5" required
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                          placeholder="Explain why you are requesting panel assignment at this time, your preparation status, and timeline considerations...">{{ old('panel_justification') }}</textarea>
                @error('panel_justification')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>

<!-- Required Documents Information -->
<div class="bg-amber-50 dark:bg-amber-900/20 rounded-2xl p-6 border border-amber-200 dark:border-amber-800">
    <div class="flex items-start space-x-4">
        <div class="p-2 bg-amber-500 rounded-lg flex-shrink-0">
            <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <h4 class="font-semibold text-amber-900 dark:text-amber-300 mb-3">Required Documents for Panel Assignment Request:</h4>
            <ul class="text-sm text-amber-800 dark:text-amber-400 space-y-2">
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-amber-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Formal request letter for panel assignment
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-amber-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Progress report or thesis draft (if available)
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-amber-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Adviser's recommendation or endorsement
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-amber-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Academic transcript or enrollment proof
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-amber-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Panel member CVs or expertise profiles (if suggesting specific members)
                </li>
            </ul>
        </div>
    </div>
</div> 
