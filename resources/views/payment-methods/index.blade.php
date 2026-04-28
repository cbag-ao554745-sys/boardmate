<x-layout title="Payment Methods">
    <x-slot name="header">
        <nav class="flex items-center gap-1 text-sm text-muted-foreground">
            <span class="text-foreground font-medium">Payment Methods</span>
        </nav>
    </x-slot>

    <div>

        <div class="mb-8 flex items-start justify-between">
            <div>
                <h1 class="text-foreground mb-1 text-3xl font-bold">Payment Methods</h1>
                <p class="text-muted-foreground text-sm">Manage payment methods available for recording payments</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('payment-methods.create') }}"
                    class="bg-primary text-primary-foreground hover:bg-primary/90 flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Add Payment Method
                </a>
            </div>
        </div>

        {{-- Flash messages --}}
        {{-- @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 mb-6 flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="shrink-0 mt-0.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                <div>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif --}}

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 mb-6 flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="shrink-0 mt-0.5">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" x2="12" y1="8" y2="12" />
                    <line x1="12" x2="12.01" y1="16" y2="16" />
                </svg>
                <div>
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="border-border bg-card overflow-hidden rounded-xl border">

            @if ($paymentMethods->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="text-muted-foreground text-sm mb-4">
                        No payment methods found. Create your first payment method to get started.
                    </p>
                    <a href="{{ route('payment-methods.create') }}"
                        class="bg-primary text-primary-foreground hover:bg-primary/90 inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        Create Payment Method
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-muted text-muted-foreground text-left text-xs tracking-wider uppercase">
                                <th class="px-6 py-4 font-medium">Method Name</th>
                                {{-- <th class="px-6 py-4 font-medium">Code</th> --}}
                                <th class="px-6 py-4 font-medium">Description</th>
                                <th class="px-6 py-4 font-medium">Requires Reference</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                                <th class="px-6 py-4 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-border divide-y">
                            @foreach ($paymentMethods as $method)
                                <tr class="hover:bg-muted transition-colors">
                                    <td class="text-foreground px-6 py-4 text-sm font-medium">
                                        {{ $method->name }}
                                    </td>

                                    {{-- <td class="text-muted-foreground px-6 py-4 text-sm">
                                        <code class="bg-muted px-2 py-1 rounded text-xs">{{ $method->code }}</code>
                                    </td> --}}

                                    <td class="text-muted-foreground px-6 py-4 text-sm">
                                        @if ($method->description)
                                            {{ Str::limit($method->description, 50) }}
                                        @else
                                            <span class="text-muted-foreground/50">—</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        @if ($method->requires_reference)
                                            <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full text-xs font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                                                Required
                                            </span>
                                        @else
                                            <span class="text-muted-foreground text-xs">Not required</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        @if ($method->is_active)
                                            <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-1 rounded-full text-xs font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 bg-gray-50 text-gray-700 px-2.5 py-1 rounded-full text-xs font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center gap-2">

                                            <a href="{{ route('payment-methods.show', $method->payment_method_id) }}"
                                                class="hover:bg-muted rounded p-2 transition-colors"
                                                title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="text-blue-600">
                                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </a>

                                            <a href="{{ route('payment-methods.edit', $method->payment_method_id) }}"
                                                class="hover:bg-muted rounded p-2 transition-colors"
                                                title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="text-blue-600">
                                                    <path d="M12 20h9" />
                                                    <path
                                                        d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z" />
                                                </svg>
                                            </a>

                                            <form method="POST" action="{{ route('payment-methods.destroy', $method->payment_method_id) }}"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to deactivate this payment method?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="hover:bg-muted rounded p-2 transition-colors"
                                                    title="Deactivate">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="text-red-600">
                                                        <path d="m20.87 4.71-15.42 15.42M4.13 4.71l15.42 15.42" />
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

                @if ($paymentMethods->hasPages())
                    <div class="border-border border-t px-6 py-4">
                        {{ $paymentMethods->withQueryString()->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-layout>
