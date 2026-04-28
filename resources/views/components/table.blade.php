@props(['class' => ''])

<div class="overflow-x-auto rounded-lg border border-border">
    <table class="min-w-full divide-y divide-border {{ $class }}">
        {{ $slot }}
    </table>
</div>
