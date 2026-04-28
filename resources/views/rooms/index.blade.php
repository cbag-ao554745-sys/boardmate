<x-layout>
    <x-slot name="header">
        <nav class="flex items-center gap-1 text-sm text-muted-foreground">
            <span class="text-foreground font-medium">Rooms</span>
        </nav>
    </x-slot>

    <div>

        <div class="mb-8 flex items-start justify-between">
            <div>
                <h1 class="text-foreground mb-1 text-3xl font-bold">Rooms</h1>
                <p class="text-muted-foreground text-sm">Manage your rental properties</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('rooms.create') }}"
                    class="bg-primary text-primary-foreground hover:bg-primary/90 flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Add Room
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('rooms.index') }}" id="room-filters" class="mb-6 flex items-center gap-2">

            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-muted-foreground absolute top-1/2 left-3 -translate-y-1/2 pointer-events-none">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <input type="text" name="search" id="search-input" placeholder="Search rooms..."
                    value="{{ request('search') }}"
                    class="border-border bg-card text-foreground placeholder:text-muted-foreground focus:border-primary w-full rounded-lg border px-10 py-2.5 text-sm focus:outline-none" />
            </div>

            <div class="relative w-40">
                <select name="floor" id="floor-filter"
                    class="border-border bg-card text-foreground focus:border-primary h-10 w-full rounded-lg border px-3 pr-8 text-sm focus:outline-none appearance-none">
                    <option value="all" {{ request('floor', 'all') === 'all' ? 'selected' : '' }}>All Floors</option>
                    @foreach ($floors as $floor)
                        <option value="{{ $floor }}" {{ request('floor') == $floor ? 'selected' : '' }}>
                            Floor {{ $floor }}
                        </option>
                    @endforeach
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground pointer-events-none absolute right-2 top-1/2 -translate-y-1/2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>

            <div class="relative w-40">
                <select name="status" id="status-filter"
                    class="border-border bg-card text-foreground focus:border-primary h-10 w-full rounded-lg border px-3 pr-8 text-sm focus:outline-none appearance-none">
                    <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active
                    </option>
                    <option value="Vacant" {{ request('status') === 'Vacant' ? 'selected' : '' }}>Vacant
                    </option>
                    <option value="Available" {{ request('status') === 'Available' ? 'selected' : '' }}>Available
                    </option>
                    <option value="Occupied" {{ request('status') === 'Occupied' ? 'selected' : '' }}>Occupied
                    </option>
                    <option value="Under Maintenance"{{ request('status') === 'Under Maintenance' ? 'selected' : '' }}>
                        Under Maintenance</option>
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground pointer-events-none absolute right-2 top-1/2 -translate-y-1/2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>

            @if (request('search') ||
                    (request('floor') && request('floor') !== 'all') ||
                    (request('status') && request('status') !== 'all'))
                <a href="{{ route('rooms.index') }}"
                    class="border-border bg-card text-muted-foreground hover:bg-muted flex items-center gap-1.5 rounded-lg border px-3 py-2.5 text-sm transition-colors whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                    Clear filters
                </a>
            @endif

        </form>

        <div class="border-border bg-card overflow-hidden rounded-xl border">

            @if ($rooms->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="text-muted-foreground text-sm">
                        {{ request('search') ||
                        (request('floor') && request('floor') !== 'all') ||
                        (request('status') && request('status') !== 'all')
                            ? 'No rooms match your filters'
                            : 'No rooms found' }}
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-muted text-muted-foreground text-left text-xs tracking-wider uppercase">
                                <th class="px-6 py-4 font-medium">Room</th>
                                <th class="px-6 py-4 font-medium">Floor</th>
                                <th class="px-6 py-4 font-medium">Monthly Rent</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                                <th class="px-6 py-4 font-medium">Current Tenant</th>
                                <th class="px-6 py-4 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-border divide-y">
                            @foreach ($rooms as $room)
                                <tr class="hover:bg-muted transition-colors">
                                    <td class="text-foreground px-6 py-4 text-sm font-medium">
                                        {{ $room['room_number'] }}
                                    </td>

                                    <td class="text-muted-foreground px-6 py-4 text-sm">
                                        Floor {{ $room['floor'] }}
                                    </td>

                                    <td class="text-foreground px-6 py-4 text-sm font-medium">
                                        ₱{{ number_format($room['monthly_rent'], 0) }}
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        <x-status-badge :status="$room['status']" />
                                    </td>

                                    <td class="text-muted-foreground px-6 py-4 text-sm">
                                        {{ $room['tenant_name'] ?? 'Vacant' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center gap-2">

                                            <a href="{{ route('rooms.show', $room['room_id']) }}"
                                                class="hover:bg-muted rounded p-2 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="text-blue-600">
                                                    <path
                                                        d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </a>

                                            <a href="{{ route('rooms.edit', $room['room_id']) }}"
                                                class="hover:bg-muted rounded p-2 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="text-blue-600">
                                                    <path d="M12 20h9" />
                                                    <path
                                                        d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z" />
                                                </svg>
                                            </a>

                                            <form method="POST" action="{{ route('rooms.destroy', $room['room_id']) }}"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this room?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="hover:bg-muted rounded p-2 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="text-red-600">
                                                        <rect width="20" height="5" x="2" y="3"
                                                            rx="1" />
                                                        <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                        <path d="M10 12v6" />
                                                        <path d="M14 12v6" />
                                                    </svg>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($rooms->hasPages())
                    <div class="border-border border-t px-6 py-4">
                        {{ $rooms->withQueryString()->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('room-filters');
            const search = document.getElementById('search-input');
            const selects = form.querySelectorAll('select');
            let debounce = null;

            selects.forEach(sel => sel.addEventListener('change', () => form.submit()));

            search.addEventListener('input', function() {
                clearTimeout(debounce);
                debounce = setTimeout(() => form.submit(), 300);
            });
        });
    </script>

</x-layout>
