<x-layout title="Payment Method - {{ $paymentMethod->name }}">
    <div>

        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('payment-methods.index') }}" class="hover:bg-muted rounded-lg p-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-muted-foreground">
                        <path d="m12 19-7-7 7-7" />
                        <path d="M19 12H5" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-foreground text-3xl font-bold">{{ $paymentMethod->name }}</h1>
                    <p class="text-muted-foreground mt-1 text-sm">Payment method details</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('payment-methods.edit', $paymentMethod->payment_method_id) }}"
                    class="bg-primary text-primary-foreground hover:bg-primary/90 flex items-center gap-2
                          rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 20h9" />
                        <path
                            d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z" />
                    </svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('payment-methods.destroy', $paymentMethod->payment_method_id) }}" class="inline"
                    onsubmit="return confirm('Are you sure you want to deactivate this payment method?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm
                                   font-medium text-white transition-colors hover:bg-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m20.87 4.71-15.42 15.42M4.13 4.71l15.42 15.42" />
                        </svg>
                        Deactivate
                    </button>
                </form>
            </div>
        </div>

        <div class="border-border bg-card rounded-xl border">

            <div class="grid grid-cols-1 gap-6 px-6 py-6 md:grid-cols-2">

                {{-- Payment Method Name --}}
                <div>
                    <p class="text-muted-foreground mb-2 text-sm font-medium">Payment Method Name</p>
                    <p class="text-foreground text-lg font-semibold">{{ $paymentMethod->name }}</p>
                </div>

                {{-- System Code --}}
                {{-- <div>
                    <p class="text-muted-foreground mb-2 text-sm font-medium">System Code</p>
                    <div class="flex items-center gap-2">
                        <code class="bg-muted text-foreground px-3 py-1.5 rounded text-sm font-mono">{{ $paymentMethod->code }}</code>
                        <span class="text-muted-foreground text-xs">(Auto-generated)</span>
                    </div>
                </div> --}}

                {{-- Status --}}
                <div>
                    <p class="text-muted-foreground mb-2 text-sm font-medium">Status</p>
                    @if ($paymentMethod->is_active)
                        <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-3 py-1.5 rounded-full text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-gray-50 text-gray-700 px-3 py-1.5 rounded-full text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                            Inactive
                        </span>
                    @endif
                </div>

                {{-- Requires Reference --}}
                <div>
                    <p class="text-muted-foreground mb-2 text-sm font-medium">Requires Reference Number</p>
                    @if ($paymentMethod->requires_reference)
                        <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-3 py-1.5 rounded-full text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                            Yes, Required
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-3 py-1.5 rounded-full text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                            No, Optional
                        </span>
                    @endif
                </div>

            </div>

            {{-- Description --}}
            @if ($paymentMethod->description)
                <div class="border-border border-t px-6 py-6">
                    <p class="text-muted-foreground mb-3 text-sm font-medium">Description</p>
                    <p class="text-foreground leading-relaxed">{{ $paymentMethod->description }}</p>
                </div>
            @endif

            {{-- Timestamps --}}
            <div class="border-border border-t px-6 py-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">Created</p>
                        <p class="text-foreground text-sm">
                            {{ $paymentMethod->created_at->format('M d, Y') }}
                            <span class="text-muted-foreground text-xs">at {{ $paymentMethod->created_at->format('H:i') }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">Last Updated</p>
                        <p class="text-foreground text-sm">
                            {{ $paymentMethod->updated_at->format('M d, Y') }}
                            <span class="text-muted-foreground text-xs">at {{ $paymentMethod->updated_at->format('H:i') }}</span>
                        </p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Info Box --}}
        {{-- <div class="mt-6 border-border bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
            <p class="text-blue-900 text-sm">
                <strong>Tip:</strong> This payment method will be available in the payment recording dropdown. 
                @if ($paymentMethod->requires_reference)
                    When selecting this method, users will be required to provide a reference number.
                @else
                    Users can select this method without providing a reference number.
                @endif
            </p>
        </div> --}}

    </div>
</x-layout>
