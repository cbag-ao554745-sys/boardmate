<x-layout title="Edit Payment Method - {{ $paymentMethod->name }}">
    <div>
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('payment-methods.index') }}" class="hover:bg-muted rounded-lg p-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-muted-foreground">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
            </a>
            <div>
                <h1 class="text-foreground text-3xl font-bold">Edit Payment Method</h1>
                <p class="text-muted-foreground mt-1 text-sm">Update payment method details</p>
            </div>
        </div>

        <div class="border-border bg-card mx-auto max-w-2xl rounded-xl border p-8">

            <form method="POST" action="{{ route('payment-methods.update', $paymentMethod->payment_method_id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- <div class="bg-muted text-muted-foreground rounded px-4 py-3 text-sm">
                    <strong>System Code:</strong> <code class="bg-background px-2 py-1 rounded text-xs">{{ $paymentMethod->code }}</code>
                    <p class="text-xs mt-1">Auto-generated from the payment method name. Cannot be changed.</p>
                </div> --}}

                @include('payment-methods.form')

                <div class="flex gap-3 pt-4">
                    <button type="submit"
                        class="bg-primary text-primary-foreground hover:bg-primary/90 flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Update Payment Method
                    </button>
                    <a href="{{ route('payment-methods.index') }}"
                        class="border-border text-muted-foreground hover:bg-muted flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-medium transition-colors">
                        Cancel
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-layout>
