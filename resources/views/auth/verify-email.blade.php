<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email - Upahan</title>
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
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Verify Email Address</h2>
            <p class="text-slate-600 text-sm mb-6">We've sent you a verification email. Please click the link in your
                email to verify your account.</p>

            @if (session('status') === 'verification-link-sent')
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-600 text-sm">A verification link has been sent to your email address.</p>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
                @csrf
                <button type="submit"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 rounded-lg transition">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit"
                    class="w-full bg-slate-200 hover:bg-slate-300 text-slate-900 font-medium py-2 rounded-lg transition">
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</body>

</html>
