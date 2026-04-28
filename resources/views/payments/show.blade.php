<x-layout title="Payment Record">

    <div>

        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('payments.index') }}" class="hover:bg-muted rounded-lg p-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-muted-foreground">
                        <path d="m12 19-7-7 7-7" />
                        <path d="M19 12H5" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-foreground text-3xl font-bold">Payment Record</h1>
                    <p class="text-muted-foreground mt-1 text-sm">Payment details and breakdown</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('payments.edit', $payment->payment_id) }}"
                    class="bg-primary text-primary-foreground hover:bg-primary/90 flex items-center gap-2
                          rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 20h9" />
                        <path
                            d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z" />
                    </svg>
                    Edit Payment
                </a>
                <form method="POST" action="{{ route('payments.destroy', $payment->payment_id) }}" class="inline"
                    onsubmit="return confirm('Are you sure you want to delete this payment record?')">
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

            <div class="space-y-6 lg:col-span-2">

                <div class="border-border bg-card rounded-xl border">
                    <div class="border-border border-b px-6 py-4">
                        <h2 class="text-foreground font-semibold">Payment Details</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-2 gap-6">
                            <div>
                                <dt class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">Room
                                </dt>
                                <dd class="text-foreground text-sm font-medium">
                                    Room {{ $payment->lease?->room->room_number ?? 'N/A' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">
                                    Tenant</dt>
                                <dd class="text-foreground text-sm">
                                    {{ $tenant?->person->first_name ?? '' }} {{ $tenant?->person->last_name ?? '' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">Date
                                    Paid</dt>
                                <dd class="text-foreground text-sm">
                                    {{ $payment->date_paid?->format('M d, Y') ?? '—' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">
                                    Payment Method</dt>
                                <dd class="text-foreground text-sm">{{ $payment->payment_method ?? '—' }}</dd>
                            </div>
                            @if ($payment->payment_reference)
                                <div>
                                    <dt class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">
                                        Reference</dt>
                                    <dd class="text-foreground text-sm">{{ $payment->payment_reference }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">
                                    Status</dt>
                                <dd class="mt-1"><x-status-badge :status="$payment->status" /></dd>
                            </div>
                            <div>
                                <dt class="text-muted-foreground mb-1 text-xs font-medium uppercase tracking-wider">
                                    Recorded</dt>
                                <dd class="text-foreground text-sm">{{ $payment->created_at->format('M d, Y H:i') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="border-border bg-card rounded-xl border">
                    <div class="border-border border-b px-6 py-4">
                        <h2 class="text-foreground font-semibold">Amount Breakdown</h2>
                    </div>
                    <div class="p-6">
                        <div class="bg-muted rounded-lg border border-border divide-y divide-border">
                            @foreach ([
        'Rent' => $payment->rent_amount,
        'Electricity' => $payment->electricity_amount,
        'Water' => $payment->water_amount,
        'Other Fees' => $payment->other_fees,
    ] as $label => $amount)
                                <div class="flex items-center justify-between px-4 py-3 text-sm">
                                    <span class="text-muted-foreground">{{ $label }}</span>
                                    <span
                                        class="text-foreground font-medium">₱{{ number_format($amount ?? 0, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <div class="space-y-6">

                <div class="border-border bg-card rounded-xl border">
                    <div class="border-border border-b px-6 py-4">
                        <h2 class="text-foreground font-semibold">Summary</h2>
                    </div>
                    <div class="space-y-3 p-6">
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground text-sm">Total Amount Due</span>
                            <span class="text-foreground text-sm font-medium">
                                ₱{{ number_format($payment->total_amount ?? 0, 2) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground text-sm">Amount Paid</span>
                            <span class="text-sm font-medium text-green-500">
                                ₱{{ number_format($payment->amount_paid ?? 0, 2) }}
                            </span>
                        </div>
                        <div class="border-border flex items-center justify-between border-t pt-3">
                            <span class="text-muted-foreground text-sm">Balance</span>
                            @php
                                $balance = $payment->balance ?? 0;
                                $balanceClass =
                                    $balance == 0
                                        ? 'text-green-500'
                                        : ($balance > 0
                                            ? 'text-red-500'
                                            : 'text-blue-500');
                            @endphp
                            <span class="text-sm font-medium {{ $balanceClass }}">
                                ₱{{ number_format($balance, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-layout>
