<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RMT Generation System - Professional Test Creation Platform</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
            /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */@layer theme{:root,:host{--font-sans:'Inter',ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--font-serif:ui-serif,Georgia,Cambria,"Times New Roman",Times,serif;--font-mono:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;--color-red-50:oklch(.971 .013 17.38);--color-red-100:oklch(.936 .032 17.717);--color-red-200:oklch(.885 .062 18.334);--color-red-300:oklch(.808 .114 19.571);--color-red-400:oklch(.704 .191 22.216);--color-red-500:oklch(.637 .237 25.331);--color-red-600:oklch(.577 .245 27.325);--color-red-700:oklch(.505 .213 27.518);--color-red-800:oklch(.444 .177 26.899);--color-red-900:oklch(.396 .141 25.723);--color-red-950:oklch(.258 .092 26.042);--color-blue-50:oklch(.97 .014 254.604);--color-blue-100:oklch(.932 .032 255.585);--color-blue-200:oklch(.882 .059 254.128);--color-blue-300:oklch(.809 .105 251.813);--color-blue-400:oklch(.707 .165 254.624);--color-blue-500:oklch(.623 .214 259.815);--color-blue-600:oklch(.546 .245 262.881);--color-blue-700:oklch(.488 .243 264.376);--color-blue-800:oklch(.424 .199 265.638);--color-blue-900:oklch(.379 .146 265.522);--color-blue-950:oklch(.282 .091 267.935);--color-green-50:oklch(.982 .018 155.826);--color-green-100:oklch(.962 .044 156.743);--color-green-200:oklch(.925 .084 155.995);--color-green-300:oklch(.871 .15 154.449);--color-green-400:oklch(.792 .209 151.711);--color-green-500:oklch(.723 .219 149.579);--color-green-600:oklch(.627 .194 149.214);--color-green-700:oklch(.527 .154 150.069);--color-green-800:oklch(.448 .119 151.328);--color-green-900:oklch(.393 .095 152.535);--color-green-950:oklch(.266 .065 152.934);--color-gray-50:oklch(.985 .002 247.839);--color-gray-100:oklch(.967 .003 264.542);--color-gray-200:oklch(.928 .006 264.531);--color-gray-300:oklch(.872 .01 258.338);--color-gray-400:oklch(.707 .022 261.325);--color-gray-500:oklch(.551 .027 264.364);--color-gray-600:oklch(.446 .03 256.802);--color-gray-700:oklch(.373 .034 259.733);--color-gray-800:oklch(.278 .033 256.848);--color-gray-900:oklch(.21 .034 264.665);--color-gray-950:oklch(.13 .028 261.692);--color-white:#fff;--color-black:#000}*,::before,::after{box-sizing:border-box;border-width:0;border-style:solid;border-color:currentColor}html,:host{line-height:1.5;-webkit-text-size-adjust:100%;-moz-tab-size:4;tab-size:4;font-family:var(--font-sans);font-feature-settings:normal;font-variation-settings:normal;-webkit-tap-highlight-color:transparent}body{margin:0;line-height:inherit}h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}a{color:inherit;text-decoration:inherit}b,strong{font-weight:bolder}code,kbd,samp,pre{font-family:var(--font-mono);font-feature-settings:normal;font-variation-settings:normal;font-size:1em}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sub{bottom:-.25em}sup{top:-.5em}table{text-indent:0;border-color:inherit;border-collapse:collapse}button,input,select,textarea{font-family:inherit;font-feature-settings:inherit;font-variation-settings:inherit;font-size:100%;font-weight:inherit;line-height:inherit;color:inherit;margin:0;padding:0}button,select{text-transform:none}button,[type='button'],[type='reset'],[type='submit']{-webkit-appearance:button;background-color:transparent;background-image:none}:-moz-focusring{outline:auto}:-moz-ui-invalid{box-shadow:none}progress{vertical-align:baseline}::-webkit-inner-spin-button,::-webkit-outer-spin-button{height:auto}[type='search']{-webkit-appearance:textfield;outline-offset:-2px}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}summary{display:list-item}blockquote,dl,dd,h1,h2,h3,h4,h5,h6,hr,figure,p,pre{margin:0}fieldset{margin:0;padding:0}legend{padding:0}ol,ul,menu{list-style:none;margin:0;padding:0}dialog{padding:0}textarea{resize:vertical}input::placeholder,textarea::placeholder{opacity:1;color:oklch(.707 .022 261.325)}button,[role="button"]{cursor:pointer}:disabled{cursor:default}img,svg,video,canvas,audio,iframe,embed,object{display:block;vertical-align:middle}img,video{max-width:100%;height:auto}[hidden]{display:none!important}.container{width:100%}@media (min-width:640px){.container{max-width:640px}}@media (min-width:768px){.container{max-width:768px}}@media (min-width:1024px){.container{max-width:1024px}}@media (min-width:1280px){.container{max-width:1280px}}@media (min-width:1536px){.container{max-width:1536px}}.mx-auto{margin-left:auto;margin-right:auto}.mb-2{margin-bottom:.5rem}.mb-3{margin-bottom:.75rem}.mb-4{margin-bottom:1rem}.mb-6{margin-bottom:1.5rem}.mb-8{margin-bottom:2rem}.mt-2{margin-top:.5rem}.mt-4{margin-top:1rem}.mt-6{margin-top:1.5rem}.mt-8{margin-top:2rem}.block{display:block}.flex{display:flex}.grid{display:grid}.hidden{display:none}.h-6{height:1.5rem}.h-8{height:2rem}.h-10{height:2.5rem}.h-12{height:3rem}.h-screen{height:100vh}.min-h-screen{min-height:100vh}.w-5{width:1.25rem}.w-6{width:1.5rem}.w-8{width:2rem}.w-full{width:100%}.max-w-md{max-width:28rem}.max-w-2xl{max-width:42rem}.max-w-4xl{max-width:56rem}.max-w-6xl{max-width:72rem}.flex-1{flex:1 1 0%}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}.flex-col{flex-direction:column}.items-center{align-items:center}.justify-center{justify-content:center}.justify-between{justify-content:space-between}.gap-2{gap:.5rem}.gap-3{gap:.75rem}.gap-4{gap:1rem}.gap-6{gap:1.5rem}.gap-8{gap:2rem}.space-y-4>:not([hidden])~:not([hidden]){margin-top:1rem}.space-y-5>:not([hidden])~:not([hidden]){margin-top:1.25rem}.space-y-6>:not([hidden])~:not([hidden]){margin-top:1.5rem}.rounded-lg{border-radius:.5rem}.rounded-xl{border-radius:.75rem}.border{border-width:1px}.border-gray-300{border-color:oklch(.872 .01 258.338)}.bg-white{background-color:var(--color-white)}.bg-gray-50{background-color:oklch(.985 .002 247.839)}.bg-blue-50{background-color:oklch(.97 .014 254.604)}.bg-blue-100{background-color:oklch(.932 .032 255.585)}.bg-blue-600{background-color:oklch(.546 .245 262.881)}.bg-blue-700{background-color:oklch(.488 .243 264.376)}.bg-green-50{background-color:oklch(.982 .018 155.826)}.bg-green-100{background-color:oklch(.962 .044 156.743)}.bg-green-600{background-color:oklch(.627 .194 149.214)}.bg-purple-100{background-color:oklch(.962 .041 313.675)}.p-4{padding:1rem}.p-6{padding:1.5rem}.p-8{padding:2rem}.px-4{padding-left:1rem;padding-right:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.px-8{padding-left:2rem;padding-right:2rem}.py-2{padding-top:.5rem;padding-bottom:.5rem}.py-3{padding-top:.75rem;padding-bottom:.75rem}.py-4{padding-top:1rem;padding-bottom:1rem}.py-16{padding-top:4rem;padding-bottom:4rem}.py-20{padding-top:5rem;padding-bottom:5rem}.text-left{text-align:left}.text-center{text-align:center}.text-sm{font-size:.875rem;line-height:1.25rem}.text-base{font-size:1rem;line-height:1.5rem}.text-lg{font-size:1.125rem;line-height:1.75rem}.text-xl{font-size:1.25rem;line-height:1.75rem}.text-2xl{font-size:1.5rem;line-height:2rem}.text-3xl{font-size:1.875rem;line-height:2.25rem}.text-4xl{font-size:2.25rem;line-height:2.5rem}.text-5xl{font-size:3rem;line-height:1}.font-medium{font-weight:500}.font-semibold{font-weight:600}.font-bold{font-weight:700}.font-extrabold{font-weight:800}.leading-tight{line-height:1.25}.text-white{color:var(--color-white)}.text-gray-500{color:oklch(.551 .027 264.364)}.text-gray-600{color:oklch(.446 .03 256.802)}.text-gray-700{color:oklch(.373 .034 259.733)}.text-gray-900{color:oklch(.21 .034 264.665)}.text-blue-600{color:oklch(.546 .245 262.881)}.text-green-600{color:oklch(.627 .194 149.214)}.text-green-700{color:oklch(.527 .154 150.069)}.text-purple-600{color:oklch(.558 .203 313.675)}.text-red-600{color:oklch(.577 .245 27.325)}.shadow-sm{box-shadow:0 1px 2px 0 rgba(0,0,0,.05)}.shadow-lg{box-shadow:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -4px rgba(0,0,0,.1)}.shadow-xl{box-shadow:0 20px 25px -5px rgba(0,0,0,.1),0 8px 10px -6px rgba(0,0,0,.1)}.focus\:outline-none:focus{outline:2px solid transparent;outline-offset:2px}.focus\:ring-2:focus{box-shadow:0 0 0 2px rgba(59,130,246,.5)}.focus\:ring-blue-500:focus{box-shadow:0 0 0 2px rgba(59,130,246,.5)}.hover\:bg-blue-700:hover{background-color:oklch(.488 .243 264.376)}.hover\:text-blue-600:hover{color:oklch(.546 .245 262.881)}.hover\:text-blue-700:hover{color:oklch(.488 .243 264.376)}.transition{transition-property:color,background-color,border-color,text-decoration-color,fill,stroke,opacity,box-shadow,transform,filter,backdrop-filter;transition-timing-function:cubic-bezier(.4,0,.2,1);transition-duration:.15s}@media (min-width:768px){.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}.md\:flex-row{flex-direction:row}.md\:text-left{text-align:left}.md\:text-5xl{font-size:3rem;line-height:1}}@media (min-width:1024px){.lg\:grid-cols-3{grid-template-columns:repeat(3,minmax(0,1fr))}.lg\:px-8{padding-left:2rem;padding-right:2rem}.lg\:py-24{padding-top:6rem;padding-bottom:6rem}}
            </style>
        @endif
    </head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo/Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-gray-900">RMT Generation System</h1>
                </div>

                <!-- Navigation for authenticated users -->
            @if (Route::has('login'))
                    <nav class="flex items-center gap-4">
                    @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">
                            Dashboard
                        </a>
                    @else
                            <a href="#login" class="text-gray-700 hover:text-blue-600 font-medium transition">
                                Login
                            </a>
                        @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                    Sign Up
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
            </div>
        </div>
        </header>

    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="py-16 lg:py-24">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-start">
                    <!-- Content -->
                    <div class="text-center md:text-left pr-0 md:pr-8">
                        <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                            Professional Test Creation Made
                            <span class="text-blue-600">Simple</span>
                        </h2>
                        <p class="text-xl text-gray-600 mb-8">
                            Create, manage, and distribute professional examinations with our comprehensive RMT (Registered Medical Technologist) Generation System. Streamline your assessment process with powerful tools designed for educators and professionals.
                        </p>
                        
                        <!-- Features -->
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center gap-3 justify-center md:justify-start">
                                <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Automated test generation</span>
                            </div>
                            <div class="flex items-center gap-3 justify-center md:justify-start">
                                <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Role-based access control</span>
                            </div>
                            <div class="flex items-center gap-3 justify-center md:justify-start">
                                <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Comprehensive analytics</span>
                            </div>
                        </div>
                    </div>

                    <!-- Login Form -->
                    <div id="login" class="bg-white p-10 rounded-xl shadow-xl max-w-md mx-auto md:mx-0 md:ml-12 mt-8 md:mt-0">
                        <div class="text-center mb-8">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h3>
                            <p class="text-gray-600">Sign in to access your account</p>
                        </div>
                        
                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-green-700 text-sm">{{ session('status') }}</p>
                            </div>
                        @endif

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf

                            <!-- Role Selection -->
                            <div>
                                <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                        Select Your Role
                                    </div>
                                </label>
                                <select name="role" id="role" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition bg-white">
                                    <option value="">Choose your role...</option>
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>
                                        🎓 Student - Take examinations and view results
                                    </option>
                                    <option value="faculty" {{ old('role') == 'faculty' ? 'selected' : '' }}>
                                        👨‍🏫 Faculty - Create and manage examinations
                                    </option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                        ⚙️ Admin - System administration and oversight
                                    </option>
                                </select>
                                @error('role')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                        </svg>
                                        Email Address
                                    </div>
                                </label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                                       placeholder="Enter your email address"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        Password
                                    </div>
                                </label>
                                <input id="password" type="password" name="password" required autocomplete="current-password"
                                       placeholder="Enter your password"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between">
                                <label class="flex items-center">
                                    <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700 transition">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none transition shadow-lg">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    Sign In
                                </div>
                            </button>
                        </form>

                        <!-- Register Link -->
                        @if (Route::has('register'))
                            <div class="mt-6 text-center">
                                <p class="text-sm text-gray-600">
                                    Don't have an account? 
                                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-medium transition">
                                        Create one here
                                    </a>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Why Choose RMT Generation System?</h3>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Our platform provides everything you need to create, manage, and administer professional medical technology examinations.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="text-center p-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C20.168 18.477 18.754 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Smart Test Generation</h4>
                        <p class="text-gray-600">Automatically generate professional-grade examinations with customizable difficulty levels and topic coverage.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="text-center p-6">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Secure Assessment</h4>
                        <p class="text-gray-600">Advanced security features ensure the integrity of your examinations with anti-cheating mechanisms.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="text-center p-6">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Analytics & Reporting</h4>
                        <p class="text-gray-600">Comprehensive analytics help you understand student performance and improve your teaching methods.</p>
                    </div>
                </div>
            </div>
        </section>
            </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold">RMT Generation System</h3>
                </div>
                <p class="text-gray-400 mb-4">Professional Test Creation Platform for Medical Technology Education</p>
                <div class="border-t border-gray-700 pt-4">
                    <p class="text-sm text-gray-500">© {{ date('Y') }} RMT Generation System. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    </body>
</html>
