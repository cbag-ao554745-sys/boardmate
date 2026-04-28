<x-layout title="Edit Room {{ $room->room_number }}">

    <div>

        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('rooms.show', $room->room_id) }}" class="hover:bg-muted rounded-lg p-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-muted-foreground">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
            </a>
            <div>
                <h1 class="text-foreground text-3xl font-bold">Edit Room {{ $room->room_number }}</h1>
                <p class="text-muted-foreground mt-1 text-sm">Update room details</p>
            </div>
        </div>

        <div class="border-border bg-card mx-auto rounded-xl border p-8">

            <form method="POST" action="{{ route('rooms.update', $room->room_id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-2">
                    <label for="room_number" class="text-foreground text-sm font-medium">
                        Room Number <span class="text-destructive">*</span>
                    </label>
                    <input type="text" id="room_number" name="room_number"
                        value="{{ old('room_number', $room->room_number) }}" placeholder="e.g., 101, A1"
                        class="border-input bg-background text-foreground placeholder:text-muted-foreground
                               focus-visible:border-ring focus-visible:ring-ring/50
                               flex h-9 w-full rounded-md border px-3 py-1 text-sm 
                               transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                               @error('room_number') border-destructive focus-visible:ring-destructive/50 @enderror" />
                    @error('room_number')
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
                    <label for="floor" class="text-foreground text-sm font-medium">
                        Floor <span class="text-destructive">*</span>
                    </label>
                    <input type="number" id="floor" name="floor" min="0"
                        value="{{ old('floor', $room->floor) }}" placeholder="e.g., 1, 2, 3"
                        class="border-input bg-background text-foreground placeholder:text-muted-foreground
                               focus-visible:border-ring focus-visible:ring-ring/50
                               flex h-9 w-full rounded-md border px-3 py-1 text-sm 
                               transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                               @error('floor') border-destructive focus-visible:ring-destructive/50 @enderror" />
                    @error('floor')
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
                    <label for="monthly_rent" class="text-foreground text-sm font-medium">
                        Monthly Rent (₱) <span class="text-destructive">*</span>
                    </label>
                    <input type="number" id="monthly_rent" name="monthly_rent" min="0" step="0.01"
                        value="{{ old('monthly_rent', $room->monthly_rent) }}" placeholder="e.g., 5000.00"
                        class="border-input bg-background text-foreground placeholder:text-muted-foreground
                               focus-visible:border-ring focus-visible:ring-ring/50
                               flex h-9 w-full rounded-md border px-3 py-1 text-sm 
                               transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                               @error('monthly_rent') border-destructive focus-visible:ring-destructive/50 @enderror" />
                    @error('monthly_rent')
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
                    <label for="status" class="text-foreground text-sm font-medium">
                        Status <span class="text-destructive">*</span>
                    </label>
                    <div class="relative">
                        <select id="status" name="status"
                            class="border-input bg-background text-foreground
                               focus-visible:border-ring focus-visible:ring-ring/50
                               flex h-9 w-full rounded-md border px-3 py-1 text-sm 
                               transition-colors focus-visible:outline-none focus-visible:ring-[3px] appearance-none
                               @error('status') border-destructive focus-visible:ring-destructive/50 @enderror">
                            <option value="Available"
                                {{ old('status', $room->status) === 'Available' ? 'selected' : '' }}>Available
                            </option>
                            <option value="Occupied"
                                {{ old('status', $room->status) === 'Occupied' ? 'selected' : '' }}>Occupied
                            </option>
                            <option value="Under Maintenance"
                                {{ old('status', $room->status) === 'Under Maintenance' ? 'selected' : '' }}>Under
                                Maintenance</option>
                        </select>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="text-muted-foreground pointer-events-none absolute right-2 top-1/2 -translate-y-1/2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                    @error('status')
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

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('rooms.show', $room->room_id) }}"
                        class="border-border text-foreground hover:bg-muted flex-1 rounded-lg border px-4
                              py-2.5 text-center text-sm font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-primary text-primary-foreground hover:bg-primary/90 flex-1 rounded-lg
                                   px-4 py-2.5 text-center text-sm font-medium transition-colors">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>

</x-layout>
