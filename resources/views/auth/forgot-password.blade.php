<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - Upahan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Upahan</h1>
            <p class="text-slate-400">Apartment Management System</p>
        </div>

        <div class="bg-white rounded-lg shadow-xl p-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-2">Forgot Password</h2>
            <p class="text-slate-600 text-sm mb-6">Enter your email and we'll send you a link to reset your password.
            </p>

            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-600 text-sm">{{ session('status') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-600 text-sm">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                    <input id="email"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:border-orange-600 @error('email') border-red-500 @enderror"
                        type="email" name="email" value="{{ old('email') }}" required autofocus />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 rounded-lg transition mt-6">
                    Send Reset Link
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                <a href="{{ route('login') }}" class="text-sm text-orange-600 hover:text-orange-700">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</body>

</html>
