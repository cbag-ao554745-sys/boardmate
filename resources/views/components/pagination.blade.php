@props(['paginator'])

@if($paginator->hasPages())
    <nav class="flex items-center justify-between border-t border-border px-4 py-3 sm:px-6">
        <div class="hidden sm:block">
            <p class="text-sm text-foreground">
                Showing <span class="font-medium">{{ $paginator->firstItem() }}</span> to <span class="font-medium">{{ $paginator->lastItem() }}</span> of <span class="font-medium">{{ $paginator->total() }}</span> results
            </p>
        </div>

        <div class="flex gap-2">
            {{-- Previous Page Link --}}
            @if($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-muted-foreground bg-card border border-border cursor-not-allowed rounded-md">
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-foreground bg-card border border-border rounded-md hover:bg-muted transition">
                    Previous
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if($page == $paginator->currentPage())
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-primary-foreground bg-primary border border-primary rounded-md">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-foreground bg-card border border-border rounded-md hover:bg-muted transition">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-foreground bg-card border border-border rounded-md hover:bg-muted transition">
                    Next
                </a>
            @else
                <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-muted-foreground bg-card border border-border cursor-not-allowed rounded-md">
                    Next
                </span>
            @endif
        </div>
    </nav>
@endif
