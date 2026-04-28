@props(['name', 'label', 'value' => '1', 'checked' => false])

@php
    $error = $errors->first($name);
    $hasError = (bool) $error;
@endphp

<div class="mb-4">
    <div class="flex items-center">
        <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}"
            {{ old($name, $checked) ? 'checked' : '' }}
            {{ $attributes->merge(['class' => 'w-4 h-4 rounded border-input text-primary focus:ring-primary']) }} />

        <label for="{{ $name }}" class="ml-2 text-sm text-foreground">
            {{ $label }}
        </label>
    </div>

    @if ($hasError)
        <div class="flex items-center gap-2 mt-2 text-destructive text-sm">
            <span>⚠</span>
            <span>{{ $error }}</span>
        </div>
    @endif
</div>
