<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Upahan' }} - {{ config('app.name', 'Upahan') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="flex h-screen bg-background">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-card border-b border-border ">
                <div class="px-8 py-4">
                    <h1 class="text-2xl font-bold text-foreground">{{ $title ?? 'Dashboard' }}</h1>
                    @if (isset($subtitle))
                        <p class="mt-1 text-sm text-muted-foreground">{{ $subtitle }}</p>
                    @endif
                </div>
                @if (isset($breadcrumbs))
                    <div class="px-8 pb-4">
                        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
                    </div>
                @endif
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-auto">
                <div class="p-8">
                    @if (session('success'))
                        <x-alert type="success" :message="session('success')" data-alert-auto-dismiss />
                    @endif

                    @if (session('error'))
                        <x-alert type="error" :message="session('error')" />
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>

</html>
