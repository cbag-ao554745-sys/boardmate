<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Upahan</title>
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
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Reset Password</h2>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-600 text-sm">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                    <input id="email"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:border-orange-600 @error('email') border-red-500 @enderror"
                        type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">New Password</label>
                    <input id="password"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:border-orange-600 @error('password') border-red-500 @enderror"
                        type="password" name="password" required />
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">Confirm
                        Password</label>
                    <input id="password_confirmation"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:border-orange-600"
                        type="password" name="password_confirmation" required />
                </div>

                <button type="submit"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 rounded-lg transition mt-6">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</body>

</html>
