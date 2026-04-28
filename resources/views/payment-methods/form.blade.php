{{-- Form partial for creating and editing payment methods --}}

<div class="grid gap-2">
    <label for="name" class="text-foreground text-sm font-medium">
        Payment Method Name <span class="text-destructive">*</span>
    </label>
    <input type="text" id="name" name="name" 
        value="{{ old('name', $paymentMethod->name ?? '') }}"
        placeholder="e.g., GCash, Bank Transfer, Cash"
        class="border-input bg-background text-foreground placeholder:text-muted-foreground
               focus-visible:border-ring focus-visible:ring-ring/50
               flex h-9 w-full rounded-md border px-3 py-1 text-sm 
               transition-colors focus-visible:outline-none focus-visible:ring-[3px]
               @error('name') border-destructive focus-visible:ring-destructive/50 @enderror" />
    @error('name')
        <div class="text-destructive flex items-center gap-1.5 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="shrink-0">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" x2="12" y1="8" y2="12" />
                <line x1="12" x2="12.01" y1="16" y2="16" />
            </svg>
            {{ $message }}
        </div>
    @enderror
</div>

<div class="grid gap-2">
    <label for="description" class="text-foreground text-sm font-medium">
        Description <span class="text-muted-foreground text-xs font-normal">(Optional)</span>
    </label>
    <textarea id="description" name="description" rows="4"
        placeholder="e.g., Mobile wallet payment via GCash app"
        class="border-input bg-background text-foreground placeholder:text-muted-foreground
               focus-visible:border-ring focus-visible:ring-ring/50
               flex w-full rounded-md border px-3 py-2 text-sm 
               transition-colors focus-visible:outline-none focus-visible:ring-[3px]
               @error('description') border-destructive focus-visible:ring-destructive/50 @enderror">{{ old('description', $paymentMethod->description ?? '') }}</textarea>
    @error('description')
        <div class="text-destructive flex items-center gap-1.5 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="shrink-0">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" x2="12" y1="8" y2="12" />
                <line x1="12" x2="12.01" y1="16" y2="16" />
            </svg>
            {{ $message }}
        </div>
    @enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div class="flex items-center gap-3 hidden">
        <input type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $paymentMethod->is_active ?? true) ? 'checked' : '' }}
            class="w-4 h-4 rounded border-input cursor-pointer" />
        <label for="is_active" class="text-foreground text-sm font-medium cursor-pointer">
            Active
        </label>
    </div>

    <div class="flex items-center gap-3">
        <input type="checkbox" id="requires_reference" name="requires_reference" value="1"
            {{ old('requires_reference', $paymentMethod->requires_reference ?? false) ? 'checked' : '' }}
            class="w-4 h-4 rounded border-input cursor-pointer" />
        <label for="requires_reference" class="text-foreground text-sm font-medium cursor-pointer">
            Requires Reference Number
        </label>
    </div>
</div>

{{-- <div class="text-muted-foreground text-xs bg-muted rounded px-3 py-2">
    <p><strong>Note:</strong> The system code will be automatically generated from the payment method name (e.g., "GCash" → "gcash").</p>
</div> --}}
