@props(['title' => ''])

<div class="mb-8">
    @if ($title)
        <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ $title }}</h3>
    @endif
    <div>
        {{ $slot }}
    </div>
</div>
