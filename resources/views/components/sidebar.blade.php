<style>
    :root {
        --sidebar-width: 16rem;
        --sidebar-width-icon: 3rem;
    }

    #app-sidebar-wrapper {
        display: flex;
        height: 100%;
        /* padding: 0.5rem; */
        border: 1px solid #e7e5e4;
    }

    #app-sidebar {
        display: flex;
        flex-direction: column;
        width: var(--sidebar-width);
        min-width: 0;
        height: 100%;
        border-radius: 0.75rem;
        background-color: hsl(var(--sidebar, var(--card)));
        overflow: hidden;
        transition: width 200ms ease;
        font-family: inherit;
    }

    .nav-item svg {
        color: hsl(var(--muted-foreground));
        flex-shrink: 0;
        width: 1rem;
        height: 1rem;
    }

    .nav-item.is-active svg {
        color: currentColor;
    }

    #app-sidebar.collapsed .sidebar-label,
    #app-sidebar.collapsed .nav-section-title,
    #app-sidebar.collapsed .user-info,
    #app-sidebar.collapsed .app-name {
        display: none;
    }

    #app-sidebar.collapsed .nav-item {
        justify-content: center;
        padding-left: 0;
        padding-right: 0;
    }

    #app-sidebar.collapsed #sidebar-header .menu-button {
        justify-content: center;
    }
</style>

<div id="app-sidebar-wrapper">
    <aside id="app-sidebar">

        <div id="sidebar-header" class="flex flex-col gap-2 p-2">
            <ul class="flex w-full min-w-0 flex-col gap-1">
                <li>
                    <a href="{{ route('rooms.index') }}"
                        class="menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left
                               outline-none ring-sidebar-ring transition-[width,height,padding]
                               hover:bg-sidebar-accent hover:text-sidebar-accent-foreground
                               focus-visible:ring-2 h-12 text-sm">

                        {{-- <div
                            class="flex aspect-square size-8 items-center justify-center rounded-lg text-primary-foreground flex-shrink-0">
                            <img src="/logo-icon.png" alt="Upahan" class="size-8 object-contain"
                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <span class="hidden text-xs font-bold leading-none">U</span>
                        </div> --}}

                        <div class="app-name grid flex-1 text-left text-lg leading-tight">
                            <span class="truncate font-semibold">BoardMate</span>
                            {{-- <span class="truncate text-xs text-muted-foreground">Property Management</span> --}}
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        <div class="flex min-h-0 flex-1 flex-col gap-2 overflow-auto p-2">

            <div class="relative flex w-full min-w-0 flex-col px-2 py-0 hidden">
                <p
                    class="nav-section-title flex h-8 shrink-0 items-center rounded-md px-2
                          text-xs font-medium text-sidebar-foreground/70 uppercase tracking-wider">
                    Overview
                </p>
                <ul class="flex w-full min-w-0 flex-col gap-1">

                    {{-- @php $active = request()->routeIs('dashboard'); @endphp
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="nav-item {{ $active ? 'is-active' : '' }} flex w-full items-center gap-2 overflow-hidden
                                   rounded-md px-2 py-1.5 text-sm outline-none ring-sidebar-ring
                                   transition-[width,height,padding] focus-visible:ring-2 h-8
                                   {{ $active
                                       ? 'bg-sidebar-primary text-sidebar-primary-foreground font-medium'
                                       : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="7" x="3" y="3" rx="1" />
                                <rect width="7" height="7" x="14" y="3" rx="1" />
                                <rect width="7" height="7" x="14" y="14" rx="1" />
                                <rect width="7" height="7" x="3" y="14" rx="1" />
                            </svg>
                            <span class="sidebar-label truncate">Dashboard</span>
                        </a>
                    </li> --}}

                    {{-- @php $active = request()->routeIs('notifications'); @endphp
                    <li>
                        <a href="{{ route('notifications') }}"
                            class="nav-item {{ $active ? 'is-active' : '' }} flex w-full items-center gap-2 overflow-hidden
                                   rounded-md px-2 py-1.5 text-sm outline-none ring-sidebar-ring
                                   transition-[width,height,padding] focus-visible:ring-2 h-8
                                   {{ $active
                                       ? 'bg-sidebar-primary text-sidebar-primary-foreground font-medium'
                                       : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                            </svg>
                            <span class="sidebar-label truncate">Notifications</span>
                        </a>
                    </li> --}}

                </ul>
            </div>

            <div class="relative flex w-full min-w-0 flex-col px-2 py-0">
                <p
                    class="nav-section-title flex h-8 shrink-0 items-center rounded-md px-2
                          text-xs font-medium text-sidebar-foreground/70 uppercase tracking-wider">
                    Management
                </p>
                <ul class="flex w-full min-w-0 flex-col gap-1">

                    @php $active = request()->routeIs('rooms.*'); @endphp
                    <li>
                        <a href="{{ route('rooms.index') }}"
                            class="nav-item {{ $active ? 'is-active' : '' }} flex w-full items-center gap-2 overflow-hidden
                                   rounded-md px-2 py-1.5 text-sm outline-none ring-sidebar-ring
                                   transition-[width,height,padding] focus-visible:ring-2 h-8
                                   {{ $active
                                       ? 'bg-sidebar-primary text-sidebar-primary-foreground font-medium'
                                       : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                <polyline points="9 22 9 12 15 12 15 22" />
                            </svg>
                            <span class="sidebar-label truncate">Rooms</span>
                        </a>
                    </li>

                    @php $active = request()->routeIs('tenants.*'); @endphp
                    <li class="hidden">
                        <a href="{{ route('tenants.index') }}"
                            class="nav-item {{ $active ? 'is-active' : '' }} flex w-full items-center gap-2 overflow-hidden
                                   rounded-md px-2 py-1.5 text-sm outline-none ring-sidebar-ring
                                   transition-[width,height,padding] focus-visible:ring-2 h-8
                                   {{ $active
                                       ? 'bg-sidebar-primary text-sidebar-primary-foreground font-medium'
                                       : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                            <span class="sidebar-label truncate">Tenants</span>
                        </a>
                    </li>

                    {{-- @php $active = request()->routeIs('leases.*'); @endphp
                    <li class="hidden">
                        <a href="{{ route('leases.index') }}"
                            class="nav-item {{ $active ? 'is-active' : '' }} flex w-full items-center gap-2 overflow-hidden
                                   rounded-md px-2 py-1.5 text-sm outline-none ring-sidebar-ring
                                   transition-[width,height,padding] focus-visible:ring-2 h-8
                                   {{ $active
                                       ? 'bg-sidebar-primary text-sidebar-primary-foreground font-medium'
                                       : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                <path d="M10 9H8" />
                                <path d="M16 13H8" />
                                <path d="M16 17H8" />
                            </svg>
                            <span class="sidebar-label truncate">Leases</span>
                        </a>
                    </li> --}}

                    @php $active = request()->routeIs('payment-methods.*'); @endphp
                    <li>
                        <a href="{{ route('payment-methods.index') }}"
                            class="nav-item {{ $active ? 'is-active' : '' }} flex w-full items-center gap-2 overflow-hidden
                                   rounded-md px-2 py-1.5 text-sm outline-none ring-sidebar-ring
                                   transition-[width,height,padding] focus-visible:ring-2 h-8
                                   {{ $active
                                       ? 'bg-sidebar-primary text-sidebar-primary-foreground font-medium'
                                       : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="11" x="3" y="7" rx="1" ry="1" />
                                <path d="M7 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                <path d="M7 18h10M6 18H3M21 18h-3" />
                            </svg>
                            <span class="sidebar-label truncate">Payment Methods</span>
                        </a>
                    </li>

                    @php $active = request()->routeIs('payments.*'); @endphp
                    <li>
                        <a href="{{ route('payments.index') }}"
                            class="nav-item {{ $active ? 'is-active' : '' }} flex w-full items-center gap-2 overflow-hidden
                                   rounded-md px-2 py-1.5 text-sm outline-none ring-sidebar-ring
                                   transition-[width,height,padding] focus-visible:ring-2 h-8
                                   {{ $active
                                       ? 'bg-sidebar-primary text-sidebar-primary-foreground font-medium'
                                       : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect width="20" height="14" x="2" y="5" rx="2" />
                                <line x1="2" x2="22" y1="10" y2="10" />
                            </svg>
                            <span class="sidebar-label truncate">Payments</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- <div class="relative flex w-full min-w-0 flex-col px-2 py-0 hidden">
                <p
                    class="nav-section-title flex h-8 shrink-0 items-center rounded-md px-2
                          text-xs font-medium text-sidebar-foreground/70 uppercase tracking-wider">
                    Reports
                </p>
                <ul class="flex w-full min-w-0 flex-col gap-1">

                    @php $active = request()->routeIs('reports'); @endphp
                    <li>
                        <a href="{{ route('reports') }}"
                            class="nav-item {{ $active ? 'is-active' : '' }} flex w-full items-center gap-2 overflow-hidden
                                   rounded-md px-2 py-1.5 text-sm outline-none ring-sidebar-ring
                                   transition-[width,height,padding] focus-visible:ring-2 h-8
                                   {{ $active
                                       ? 'bg-sidebar-primary text-sidebar-primary-foreground font-medium'
                                       : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M3 3v18h18" />
                                <path d="M18 17V9" />
                                <path d="M13 17V5" />
                                <path d="M8 17v-3" />
                            </svg>
                            <span class="sidebar-label truncate">Reports</span>
                        </a>
                    </li>

                    @php $active = request()->routeIs('audit-log'); @endphp
                    <li>
                        <a href="{{ route('audit-log') }}"
                            class="nav-item {{ $active ? 'is-active' : '' }} flex w-full items-center gap-2 overflow-hidden
                                   rounded-md px-2 py-1.5 text-sm outline-none ring-sidebar-ring
                                   transition-[width,height,padding] focus-visible:ring-2 h-8
                                   {{ $active
                                       ? 'bg-sidebar-primary text-sidebar-primary-foreground font-medium'
                                       : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M2 6h4" />
                                <path d="M2 10h4" />
                                <path d="M2 14h4" />
                                <path d="M2 18h4" />
                                <rect width="16" height="20" x="4" y="2" rx="2" />
                                <path d="M16 2v20" />
                            </svg>
                            <span class="sidebar-label truncate">Audit Log</span>
                        </a>
                    </li>

                </ul>
            </div> --}}

        </div>

        <div class="flex flex-col gap-2 p-2">
            @if (auth('landlord')->check())
                @php
                    $user = auth('landlord')->user();
                    $landlord = $user->landlord;
                    $person = $landlord->person ?? null;
                    $firstName = $person->first_name ?? 'Landlord';
                    $lastName = $person->last_name ?? '';
                    $email = $user->email ?? '';
                    $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                @endphp

                <div x-data="{ open: false }" class="relative">

                    <button @click="open = !open"
                        class="flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left
                               outline-none ring-sidebar-ring transition-[width,height,padding]
                               hover:bg-sidebar-accent hover:text-sidebar-accent-foreground
                               focus-visible:ring-2 h-12 text-sm mb-3">

                        <span class="relative flex size-8 shrink-0 overflow-hidden rounded-lg">
                            <span
                                class="flex size-full items-center justify-center rounded-lg
                                         bg-sidebar-primary text-sidebar-primary-foreground text-xs font-semibold">
                                {{ $initials }}
                            </span>
                        </span>

                        <div class="user-info grid flex-1 text-left text-sm leading-tight">
                            <span class="truncate font-semibold">{{ $firstName }} {{ $lastName }}</span>
                            <span class="truncate text-xs text-muted-foreground">Landlord</span>
                        </div>



                        {{-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="sidebar-label ml-auto size-4 shrink-0 text-muted-foreground">
                            <path d="m7 15 5 5 5-5" />
                            <path d="m7 9 5-5 5 5" />
                        </svg> --}}
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center gap-2 rounded-sm px-2 py-1.5 text-sm
                                       cursor-pointer outline-none transition-colors bg-red-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="size-4 text-white">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" x2="9" y1="12" y2="12" />
                            </svg>
                            Log out
                        </button>
                    </form>

                    <div x-show="open" @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute bottom-full left-0 mb-1 w-full min-w-56 rounded-lg
                                border border-border bg-popover p-1 shadow-lg z-50">

                        <div class="flex items-center gap-2 px-1 py-1.5 text-sm">
                            <span class="relative flex size-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex size-full items-center justify-center rounded-lg
                                             bg-sidebar-primary text-sidebar-primary-foreground text-xs font-semibold">
                                    {{ $initials }}
                                </span>
                            </span>
                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ $firstName }} {{ $lastName }}</span>
                                <span class="truncate text-xs text-muted-foreground">{{ $email }}</span>
                            </div>
                        </div>

                        <div class="my-1 -mx-1 h-px bg-border"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center gap-2 rounded-sm px-2 py-1.5 text-sm
                                       text-foreground hover:bg-accent hover:text-accent-foreground
                                       cursor-pointer outline-none transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="size-4 text-muted-foreground">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" x2="9" y1="12" y2="12" />
                                </svg>
                                Log out
                            </button>
                        </form>

                    </div>
                </div>
            @endif
        </div>

    </aside>
</div>
