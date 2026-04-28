@props(['type' => 'primary', 'size' => 'md', 'disabled' => false])

@php
    $baseClasses =
        'inline-flex items-center justify-center font-medium rounded-lg transition duration-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed';

    $sizeClasses = match ($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-4 py-2 text-sm',
    };

    $typeClasses = match ($type) {
        'primary' => 'bg-primary text-primary-foreground hover:opacity-90 active:opacity-80',
        'secondary' => 'bg-secondary text-secondary-foreground hover:opacity-90 active:opacity-80',
        'danger' => 'bg-destructive text-destructive-foreground hover:opacity-90 active:opacity-80',
        'ghost' => 'text-foreground hover:bg-muted active:bg-muted/80',
        default => 'bg-primary text-primary-foreground hover:opacity-90 active:opacity-80',
    };
@endphp

<button {{ $attributes->merge(['class' => "$baseClasses $sizeClasses $typeClasses", 'disabled' => $disabled]) }}>
    {{ $slot }}
</button>
