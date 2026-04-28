<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Upahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-gradient-to-br from-orange-50 via-slate-50 to-orange-100 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-orange-600 rounded-lg flex items-center justify-center mx-auto mb-4 shadow-lg">
                <x-lucide-icon icon="building" class="w-8 h-8 text-white" />
            </div>
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Upahan</h1>
            <p class="text-slate-500">Property Management System</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-xl shadow-2xl p-8 border border-slate-100">
            <h2 class="text-2xl font-bold text-slate-900 mb-1">Create Account</h2>
            <p class="text-slate-500 text-sm mb-6">Register as a new landlord</p>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
                    <x-lucide-icon icon="alert-circle" class="w-5 h-5 text-red-600 mt-0.5" />
                    <p class="text-red-600 text-sm font-medium">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-semibold text-slate-700 mb-2">
                        <x-lucide-icon icon="user" class="w-4 h-4 mr-2 text-orange-600 inline" />First Name
                    </label>
                    <input id="first_name"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-orange-600 focus:ring-2 focus:ring-orange-100 transition @error('first_name') border-red-500 @enderror"
                        type="text" name="first_name" value="{{ old('first_name') }}" required autofocus />
                    @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Middle Name -->
                <div>
                    <label for="middle_name" class="block text-sm font-semibold text-slate-700 mb-2">
                        <x-lucide-icon icon="user" class="w-4 h-4 mr-2 text-orange-600 inline" />Middle Name (Optional)
                    </label>
                    <input id="middle_name"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-orange-600 focus:ring-2 focus:ring-orange-100 transition @error('middle_name') border-red-500 @enderror"
                        type="text" name="middle_name" value="{{ old('middle_name') }}" />
                    @error('middle_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-semibold text-slate-700 mb-2">
                        <x-lucide-icon icon="user" class="w-4 h-4 mr-2 text-orange-600 inline" />Last Name
                    </label>
                    <input id="last_name"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-orange-600 focus:ring-2 focus:ring-orange-100 transition @error('last_name') border-red-500 @enderror"
                        type="text" name="last_name" value="{{ old('last_name') }}" required />
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-700 mb-2">
                        <x-lucide-icon icon="at-sign" class="w-4 h-4 mr-2 text-orange-600 inline" />Username
                    </label>
                    <input id="username"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-orange-600 focus:ring-2 focus:ring-orange-100 transition @error('username') border-red-500 @enderror"
                        type="text" name="username" value="{{ old('username') }}" required />
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-orange-600"></i>Email Address
                    </label>
                    <input id="email"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-orange-600 focus:ring-2 focus:ring-orange-100 transition @error('email') border-red-500 @enderror"
                        type="email" name="email" value="{{ old('email') }}" required />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-lock mr-2 text-orange-600"></i>Password
                    </label>
                    <input id="password"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-orange-600 focus:ring-2 focus:ring-orange-100 transition @error('password') border-red-500 @enderror"
                        type="password" name="password" required />
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-lock-check mr-2 text-orange-600"></i>Confirm Password
                    </label>
                    <input id="password_confirmation"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-orange-600 focus:ring-2 focus:ring-orange-100 transition"
                        type="password" name="password_confirmation" required />
                </div>

                <!-- Register Button -->
                <button type="submit"
                    class="w-full bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white font-semibold py-2.5 rounded-lg transition shadow-lg hover:shadow-xl mt-6">
                    <i class="fas fa-user-plus mr-2"></i>Create Account
                </button>
            </form>

            <!-- Sign In Link -->
            <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                <p class="text-sm text-slate-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-700 font-semibold">
                        <i class="fas fa-sign-in-alt mr-1"></i>Sign in here
                    </a>
                </p>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-8 bg-blue-100 border border-blue-300 rounded-lg p-4">
            <p class="text-slate-700 text-sm"><i class="fas fa-info-circle text-blue-600 mr-2"></i>Fill in your details
                to create a new account and start managing your properties.</p>
        </div>
    </div>
</body>

</html>
