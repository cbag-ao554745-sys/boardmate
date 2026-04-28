<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Log in - Upahan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background">
    <div class="flex min-h-screen flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <!-- Logo Section -->
                <div class="flex flex-col items-center gap-4">
                    <h1 class="text-2xl font-bold">BoardMate</h1>

                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium text-foreground">Log in to your account</h1>
                        <p class="text-muted-foreground text-center text-sm">Enter your username and password below to
                            log in</p>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-6">
                    @csrf

                    <!-- Status Messages -->
                    @if ($errors->any())
                        <div
                            class="flex items-start gap-3 rounded-lg border border-destructive/50 bg-destructive/10 p-4">
                            <x-lucide-icon icon="alert-circle" class="mt-0.5 h-5 w-5 text-destructive flex-shrink-0" />
                            <p class="text-sm font-medium text-destructive">{{ $errors->first() }}</p>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="flex items-start gap-3 rounded-lg border border-green-500/50 bg-green-500/10 p-4">
                            <x-lucide-icon icon="check-circle"
                                class="mt-0.5 h-5 w-5 text-green-600 dark:text-green-400 flex-shrink-0" />
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">{{ session('status') }}
                            </p>
                        </div>
                    @endif

                    <div class="grid gap-6">
                        <!-- Username Field -->
                        <div class="grid gap-2">
                            <label for="username" class="text-sm font-medium text-foreground">Username</label>
                            <input id="username" type="text" name="username" value="{{ old('username') }}" required
                                autofocus placeholder="your.username"
                                class="border-border bg-card text-foreground placeholder:text-muted-foreground focus:border-primary h-10 rounded-lg border px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20" />
                            @error('username')
                                <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="grid gap-2">
                            <label for="password" class="text-sm font-medium text-foreground">Password</label>
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password" placeholder="••••••••"
                                class="border-border bg-card text-foreground placeholder:text-muted-foreground focus:border-primary h-10 rounded-lg border px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20" />
                            @error('password')
                                <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="bg-primary text-primary-foreground hover:bg-primary/90 active:bg-primary/80 mt-2 h-10 w-full rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                            Log in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
