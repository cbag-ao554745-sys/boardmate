<x-layout title="Payments">
    <div>

        <div class="mb-8 flex items-start justify-between">
            <div>
                <h1 class="text-foreground mb-1 text-3xl font-bold">Payments</h1>
                <p class="text-muted-foreground text-sm">Track payment records</p>
            </div>
            <a href="{{ route('payments.create') }}"
                class="bg-primary text-primary-foreground hover:bg-primary/90 flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
                Record Payment
            </a>
        </div>

        <form method="GET" action="{{ route('payments.index') }}" id="payment-filters"
            class="mb-6 grid grid-cols-1 gap-2 sm:grid-cols-2 lg:flex lg:items-center">

            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-muted-foreground absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <input type="text" name="search" id="search-input" placeholder="Search payments..."
                    value="{{ request('search') }}"
                    class="border-border bg-card text-foreground placeholder:text-muted-foreground focus:border-primary w-full rounded-lg border px-10 py-2.5 text-sm focus:outline-none" />
            </div>

            <div class="relative w-40">
                <select name="month" id="month-filter"
                    class="border-border bg-card text-foreground focus:border-primary h-10 w-full rounded-lg border px-3 pr-8 text-sm focus:outline-none appearance-none">
                    @foreach (['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'] as $val => $label)
                        <option value="{{ $val }}" {{ request('month', now()->month) == $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground pointer-events-none absolute right-2 top-1/2 -translate-y-1/2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>

            <div class="relative w-32">
                <select name="year" id="year-filter"
                    class="border-border bg-card text-foreground focus:border-primary h-10 w-full rounded-lg border px-3 pr-8 text-sm focus:outline-none appearance-none">
                    @for ($y = now()->year - 2; $y <= now()->year + 2; $y++)
                        <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                            {{ $y }}</option>
                    @endfor
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground pointer-events-none absolute right-2 top-1/2 -translate-y-1/2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>

            <div class="relative w-40">
                <select name="status" id="status-filter"
                    class="border-border bg-card text-foreground focus:border-primary h-10 w-full rounded-lg border px-3 pr-8 text-sm focus:outline-none appearance-none">
                    <option value="">All Status</option>
                    @foreach (['Paid', 'Pending', 'Overdue', 'Partial'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                            {{ $s }}</option>
                    @endforeach
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground pointer-events-none absolute right-2 top-1/2 -translate-y-1/2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>

            @if (request('search') ||
                    request('status') ||
                    (request('month') && request('month') != now()->month) ||
                    (request('year') && request('year') != now()->year))
                <a href="{{ route('payments.index') }}"
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

            @if ($payments->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="text-muted-foreground text-sm">No payment records found</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-muted text-muted-foreground text-left text-xs tracking-wider uppercase">
                                <th class="px-4 py-4 font-medium">Room</th>
                                <th class="px-4 py-4 font-medium">Paid By</th>
                                <th class="px-4 py-4 font-medium">Rent</th>
                                <th class="px-4 py-4 font-medium">Electric</th>
                                <th class="px-4 py-4 font-medium">Water</th>
                                <th class="px-4 py-4 font-medium">Other</th>
                                <th class="px-4 py-4 font-medium">Total</th>
                                <th class="px-4 py-4 font-medium">Paid</th>
                                <th class="px-4 py-4 font-medium">Balance</th>
                                <th class="px-4 py-4 font-medium">Method</th>
                                <th class="px-4 py-4 font-medium">Status</th>
                                <th class="px-4 py-4 font-medium">Due</th>
                                <th class="px-4 py-4 font-medium">Date Paid</th>
                                <th class="px-4 py-4 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-border divide-y">
                            @foreach ($payments as $payment)
                                <tr class="hover:bg-muted transition-colors">
                                    <td class="text-foreground px-4 py-4 font-medium">{{ $payment['room_number'] }}
                                    </td>
                                    <td class="text-muted-foreground px-4 py-4">
                                        {{ ($payment['amount_paid'] ?? 0) == 0 ? '—' : $payment['tenant_name'] }}
                                    </td>
                                    <td class="text-foreground px-4 py-4">
                                        ₱{{ number_format($payment['rent_amount'] ?? 0) }}</td>
                                    <td class="text-foreground px-4 py-4">
                                        ₱{{ number_format($payment['electricity_amount'] ?? 0) }}</td>
                                    <td class="text-foreground px-4 py-4">
                                        ₱{{ number_format($payment['water_amount'] ?? 0) }}</td>
                                    <td class="text-foreground px-4 py-4">
                                        ₱{{ number_format($payment['other_fees'] ?? 0) }}</td>
                                    <td class="text-foreground px-4 py-4 font-medium">
                                        ₱{{ number_format($payment['total_amount'] ?? 0) }}</td>
                                    <td class="text-foreground px-4 py-4">
                                        ₱{{ number_format($payment['amount_paid'] ?? 0) }}</td>
                                    <td class="text-foreground px-4 py-4">
                                        ₱{{ number_format($payment['balance'] ?? 0) }}</td>
                                    <td class="text-muted-foreground px-4 py-4">
                                        {{ $payment['payment_method'] ?? '—' }}</td>
                                    <td class="px-4 py-4">
                                        <x-status-badge :status="$payment['status']" />
                                    </td>
                                    <td class="text-muted-foreground px-4 py-4">
                                        {{ $payment['bills_due_date'] ?? '—' }}</td>
                                    <td class="text-muted-foreground px-4 py-4">{{ $payment['date_paid'] ?? '—' }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">

                                            <a href="{{ route('payments.show', $payment['payment_id']) }}"
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

                                            <a href="{{ route('payments.edit', $payment['payment_id']) }}"
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

                                            <form method="POST"
                                                action="{{ route('payments.destroy', $payment['payment_id']) }}"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to archive this payment?')">
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

                @if ($payments->hasPages())
                    <div class="border-border border-t px-6 py-4">
                        {{ $payments->withQueryString()->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('payment-filters');
            const search = document.getElementById('search-input');
            const selects = form.querySelectorAll('select');
            let timer = null;

            selects.forEach(sel => sel.addEventListener('change', () => form.submit()));

            search.addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(() => form.submit(), 300);
            });
        });
    </script>

</x-layout>
