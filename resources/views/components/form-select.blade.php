@props(['name', 'label', 'options' => [], 'value' => '', 'required' => false, 'placeholder' => 'Select an option'])

@php
    $error = $errors->first($name);
    $hasError = (bool) $error;
@endphp

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-foreground mb-2">
        {{ $label }}
        @if ($required)
            <span class="text-destructive">*</span>
        @endif
    </label>

    <select id="{{ $name }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full px-3 py-2 rounded-lg border transition ' . ($hasError ? 'border-destructive bg-destructive/10 focus:outline-none focus:border-destructive focus:ring-1 focus:ring-destructive' : 'border-input focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary')]) }}>
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @if ($hasError)
        <div class="flex items-center gap-2 mt-2 text-destructive text-sm">
            <span>⚠</span>
            <span>{{ $error }}</span>
        </div>
    @endif
</div>
