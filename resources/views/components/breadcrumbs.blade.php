@props(['breadcrumbs'])

@if ($breadcrumbs && count($breadcrumbs) > 0)
    <nav class="flex items-center gap-2 text-sm">
        @foreach ($breadcrumbs as $index => $breadcrumb)
            @if ($index > 0)
                <span class="text-muted-foreground">/</span>
            @endif

            @if (isset($breadcrumb['url']))
                <a href="{{ $breadcrumb['url'] }}" class="text-primary hover:text-primary/80 transition">
                    {{ $breadcrumb['label'] }}
                </a>
            @else
                <span class="text-muted-foreground">{{ $breadcrumb['label'] }}</span>
            @endif
        @endforeach
    </nav>
@endif
