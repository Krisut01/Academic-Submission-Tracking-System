<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    @elseif(Auth::user()->role === 'faculty')
                        <a href="{{ route('faculty.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    @elseif(Auth::user()->role === 'student')
                        <a href="{{ route('student.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    @endif
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @elseif(Auth::user()->role === 'faculty')
                        <x-nav-link :href="route('faculty.dashboard')" :active="request()->routeIs('faculty.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @elseif(Auth::user()->role === 'student')
                        <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Notifications Bell -->
                <div class="relative mr-4">
                    <a href="{{ route('notifications.index') }}" 
                       class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM7 7h10l-5-5 5 5H7z"></path>
                        </svg>
                        <!-- Notification Badge -->
                        @if(Auth::user()->unread_notification_count > 0)
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                                {{ Auth::user()->unread_notification_count > 99 ? '99+' : Auth::user()->unread_notification_count }}
                            </span>
                        @endif
                    </a>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 dark:text-blue-400 font-medium text-xs">
                                        {{ substr(Auth::user()->name, 0, 2) }}
                                    </span>
                                </div>
                                <div class="text-left">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-400">{{ ucfirst(Auth::user()->role) }}</div>
                                </div>
                            </div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Role-specific Dashboard Links -->
                        @if(Auth::user()->role === 'admin')
                            <x-dropdown-link :href="route('admin.dashboard')">
                                🧑‍💼 {{ __('Admin Dashboard') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.users')">
                                👥 {{ __('User Management') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.records')">
                                📋 {{ __('Records & RMT') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.panel')">
                                🧑‍⚖️ {{ __('Panel Assignment') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.reports')">
                                📈 {{ __('Reports') }}
                            </x-dropdown-link>
                        @elseif(Auth::user()->role === 'faculty')
                            <x-dropdown-link :href="route('faculty.dashboard')">
                                👨‍🏫 {{ __('Faculty Dashboard') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('faculty.thesis.reviews')">
                                📂 {{ __('Review Thesis') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('faculty.thesis.progress')">
                                🗒️ {{ __('Track Progress') }}
                            </x-dropdown-link>
                        @elseif(Auth::user()->role === 'student')
                            <x-dropdown-link :href="route('student.dashboard')">
                                👨‍🎓 {{ __('Student Dashboard') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('student.forms.index')">
                                📝 {{ __('Submit Forms') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('student.thesis.index')">
                                📄 {{ __('Thesis Documents') }}
                            </x-dropdown-link>
                        @endif

                        <!-- Divider -->
                        <div class="border-t border-gray-100 dark:border-gray-600 my-1"></div>

                        <!-- Common Links -->
                        <x-dropdown-link :href="route('settings.index')">
                            ⚙️ {{ __('Settings') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.edit')">
                            👤 {{ __('Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('notifications.index')">
                            🔔 {{ __('Notifications') }}
                            @if(Auth::user()->unread_notification_count > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                                    {{ Auth::user()->unread_notification_count }}
                                </span>
                            @endif
                        </x-dropdown-link>

                        <!-- Divider -->
                        <div class="border-t border-gray-100 dark:border-gray-600 my-1"></div>

                        <!-- Enhanced Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); 
                                            if(confirm('Are you sure you want to logout? Your session will be cleared.')) { 
                                                this.closest('form').submit(); 
                                            }"
                                    class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                🔓 {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->role === 'faculty')
                <x-responsive-nav-link :href="route('faculty.dashboard')" :active="request()->routeIs('faculty.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->role === 'student')
                <x-responsive-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
