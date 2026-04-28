<x-layout title="Room {{ $room->room_number }}">

    <div>

        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('rooms.index') }}" class="hover:bg-muted rounded-lg p-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-muted-foreground">
                        <path d="m12 19-7-7 7-7" />
                        <path d="M19 12H5" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-foreground text-3xl font-bold">Room {{ $room->room_number }}</h1>
                    <p class="text-muted-foreground mt-1 text-sm">Room details and lease information</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('rooms.edit', $room->room_id) }}"
                    class="bg-primary text-primary-foreground hover:bg-primary/90 flex items-center gap-2
                          rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 20h9" />
                        <path
                            d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z" />
                    </svg>
                    Edit Room
                </a>
                <form method="POST" action="{{ route('rooms.destroy', $room->room_id) }}" class="inline"
                    onsubmit="return confirm('Are you sure you want to delete this room?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm
                                   font-medium text-white transition-colors hover:bg-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect width="20" height="5" x="2" y="3" rx="1" />
                            <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                            <path d="M10 12v6" />
                            <path d="M14 12v6" />
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <div class="space-y-6 lg:col-span-3">

                <div class="border-border bg-card rounded-xl border">
                    <div class="border-border border-b px-6 py-4">
                        <h2 class="text-foreground font-semibold">Room Information</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-2 gap-6">
                            <div>
                                <dt class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">Room
                                    ID</dt>
                                <dd class="text-foreground text-sm">{{ $room->room_id }}</dd>
                            </div>
                            <div>
                                <dt class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">Room
                                    Number</dt>
                                <dd class="text-foreground text-sm font-medium">{{ $room->room_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">
                                    Floor</dt>
                                <dd class="text-foreground text-sm">Floor {{ $room->floor }}</dd>
                            </div>
                            <div>
                                <dt class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">
                                    Monthly Rent</dt>
                                <dd class="text-foreground text-sm font-medium">
                                    ₱{{ number_format($room->monthly_rent, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">
                                    Status</dt>
                                <dd class="mt-1"><x-status-badge :status="$room->status" /></dd>
                            </div>
                            <div>
                                <dt class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">
                                    Created</dt>
                                <dd class="text-foreground text-sm">{{ $room->created_at->format('M d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                @if ($activeLease && $tenants && count($tenants) > 0)
                    <div class="border-border bg-card rounded-xl border">
                        <div class="border-border border-b px-6 py-4">
                            <h2 class="text-foreground font-semibold">
                                Current Tenants
                                <span
                                    class="text-muted-foreground ml-1 text-sm font-normal">({{ count($tenants) }})</span>
                            </h2>
                        </div>
                        <div class="divide-border divide-y">
                            @foreach ($tenants as $tenant)
                                <div class="flex items-center justify-between px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="bg-muted text-muted-foreground flex size-9 items-center
                                                    justify-center rounded-full text-xs font-semibold">
                                            {{ strtoupper(substr($tenant['name'], 0, 1)) }}
                                        </div>
                                        <p class="text-foreground text-sm font-medium">{{ $tenant['name'] }}</p>
                                    </div>
                                    <span class="text-muted-foreground text-xs uppercase tracking-wider">
                                        {{ $tenant['is_primary'] ? 'Primary' : 'Co-Occupant' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- <div class="space-y-6">

                @if ($activeLease)
                    <div class="border-border bg-card rounded-xl border">
                        <div class="border-border border-b px-6 py-4">
                            <h2 class="text-foreground font-semibold">Lease Information</h2>
                        </div>
                        <div class="space-y-4 p-6">
                            <div>
                                <p class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">Lease
                                    ID</p>
                                <p class="text-foreground text-sm">{{ $activeLease['lease_id'] }}</p>
                            </div>
                            <div>
                                <p class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">
                                    Move-In Date</p>
                                <p class="text-foreground text-sm">{{ $activeLease['start_date'] }}</p>
                            </div>
                            @if (isset($activeLease['end_date']) && $activeLease['end_date'])
                                <div>
                                    <p class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">
                                        End Date</p>
                                    <p class="text-foreground text-sm">{{ $activeLease['end_date'] }}</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">
                                    Status</p>
                                <x-status-badge :status="$activeLease['status'] ?? 'Active'" />
                            </div>
                            <a href="{{ route('leases.show', $activeLease['lease_id']) }}"
                                class="border-border text-foreground hover:bg-muted mt-2 flex items-center
                                      justify-center gap-2 rounded-lg border px-4 py-2 text-sm transition-colors">
                                View Lease
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="border-border bg-card rounded-xl border">
                        <div class="border-border border-b px-6 py-4">
                            <h2 class="text-foreground font-semibold">Lease Information</h2>
                        </div>
                        <div class="px-6 py-8 text-center">
                            <p class="text-muted-foreground text-sm">No active lease</p>
                            <a href="{{ route('leases.create') }}"
                                class="bg-primary text-primary-foreground hover:bg-primary/90 mt-4 inline-flex
                                      items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="M12 5v14" />
                                </svg>
                                Create Lease
                            </a>
                        </div>
                    </div>
                @endif

            </div> --}}
        </div>
    </div>

</x-layout>
