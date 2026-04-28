@props(['type' => 'success', 'message' => '', 'title' => ''])

@php
    $typeClasses = match ($type) {
        'success' => 'bg-green-50 border-green-200 text-green-900',
        'error' => 'bg-red-50 border-red-200 text-red-900',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-900',
        'info' => 'bg-blue-50 border-blue-200 text-blue-900',
        default => 'bg-green-50 border-green-200 text-green-900',
    };

    $iconClasses = match ($type) {
        'success' => 'text-green-600',
        'error' => 'text-red-600',
        'warning' => 'text-yellow-600',
        'info' => 'text-blue-600',
        default => 'text-green-600',
    };

    $iconType = match ($type) {
        'success' => 'fa-check-circle',
        'error' => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        'info' => 'fa-info-circle',
        default => 'fa-check-circle',
    };
@endphp

<div class="rounded-lg border p-4 {{ $typeClasses }} mb-6" {{ $attributes }}>
    <div class="flex items-start gap-3">
        <i class="fas {{ $iconType }} text-lg {{ $iconClasses }} mt-0.5 flex-shrink-0"></i>
        <div class="flex-1">
            @if ($title)
                <h3 class="font-semibold">{{ $title }}</h3>
            @endif
            <p class="text-sm {{ $title ? 'mt-1' : '' }}">{{ $message ?? $slot }}</p>
        </div>
    </div>
</div>
