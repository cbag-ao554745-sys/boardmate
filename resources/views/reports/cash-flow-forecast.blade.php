<x-layout title="Cash Flow Forecast">
    <div>
        <div class="mb-8">
            <div>
                <h1 class="text-foreground mb-1 text-3xl font-bold">Cash Flow Forecast</h1>
                <p class="text-muted-foreground text-sm">90-day payment due date forecast for cash planning</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid gap-4 mb-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
            <div class="border-border bg-card rounded-xl border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-muted-foreground text-sm font-medium">Total Due (90 days)</p>
                        <p class="text-foreground text-2xl font-bold">₱{{ number_format($summary['totalDue'], 2) }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-primary/20">
                        <line x1="12" x2="12" y1="2" y2="22" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </div>
            </div>

            <div class="border-border bg-card rounded-xl border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-muted-foreground text-sm font-medium">Already Collected</p>
                        <p class="text-foreground text-2xl font-bold">₱{{ number_format($summary['totalCollected'], 2) }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-green-500/20">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                    </svg>
                </div>
            </div>

            <div class="border-border bg-card rounded-xl border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-muted-foreground text-sm font-medium">Amount at Risk</p>
                        <p class="text-foreground text-2xl font-bold">₱{{ number_format($summary['totalAtRisk'], 2) }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-red-500/20">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" x2="12" y1="8" y2="12" />
                        <line x1="12" x2="12.01" y1="16" y2="16" />
                    </svg>
                </div>
            </div>

            <div class="border-border bg-card rounded-xl border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-muted-foreground text-sm font-medium">Collection Rate</p>
                        <p class="text-foreground text-2xl font-bold">{{ $summary['collectionRate'] }}%</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-blue-500/20">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Alert Cards for Issues -->
        @if ($summary['overdueDates'] > 0 || $summary['criticalDates'] > 0)
            <div class="grid gap-4 mb-8 grid-cols-1 md:grid-cols-2">
                @if ($summary['overdueDates'] > 0)
                    <div class="border-border bg-red-50 dark:bg-red-950/20 rounded-xl border border-red-200 dark:border-red-800 p-4">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="text-red-600">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" x2="12" y1="8" y2="12" />
                                <line x1="12" x2="12.01" y1="16" y2="16" />
                            </svg>
                            <div>
                                <p class="text-red-900 dark:text-red-200 font-semibold">{{ $summary['overdueDates'] }} Overdue Dates</p>
                                <p class="text-red-700 dark:text-red-300 text-sm">Payment dates that have passed</p>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($summary['criticalDates'] > 0)
                    <div class="border-border bg-orange-50 dark:bg-orange-950/20 rounded-xl border border-orange-200 dark:border-orange-800 p-4">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="text-orange-600">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3.05h16.94a2 2 0 0 0 1.71-3.05L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                <line x1="12" x2="12" y1="9" y2="13" />
                                <line x1="12" x2="12.01" y1="17" y2="17" />
                            </svg>
                            <div>
                                <p class="text-orange-900 dark:text-orange-200 font-semibold">{{ $summary['criticalDates'] }} Critical Dates</p>
                                <p class="text-orange-700 dark:text-orange-300 text-sm">Within next 3 days - requires attention</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Cash Flow Forecast Table -->
        <div class="border-border bg-card overflow-hidden rounded-xl border">
            <div class="border-border border-b px-6 py-4">
                <h2 class="text-foreground font-semibold">Payment Due Dates Forecast (Next 90 Days)</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-muted text-muted-foreground text-left text-xs tracking-wider uppercase">
                            <th class="px-4 py-4 font-medium">Due Date</th>
                            <th class="px-4 py-4 font-medium text-center">Days Until Due</th>
                            <th class="px-4 py-4 font-medium text-center">Leases Due</th>
                            <th class="px-4 py-4 font-medium text-center">Rooms Due</th>
                            <th class="px-4 py-4 font-medium text-right">Total Due</th>
                            <th class="px-4 py-4 font-medium text-right">Collected</th>
                            <th class="px-4 py-4 font-medium text-right">At Risk</th>
                            <th class="px-4 py-4 font-medium text-center">Payment Status</th>
                            <th class="px-4 py-4 font-medium text-center">Priority</th>
                        </tr>
                    </thead>
                    <tbody class="divide-border divide-y">
                        @forelse ($cashFlowData as $forecast)
                            <tr class="hover:bg-muted transition-colors {{ 
                                $forecast->priority_flag === 'OVERDUE' ? 'bg-red-50 dark:bg-red-950/10' : 
                                ($forecast->priority_flag === 'CRITICAL' ? 'bg-orange-50 dark:bg-orange-950/10' : '')
                            }}">
                                <td class="text-foreground px-4 py-4 font-medium">
                                    {{ \Carbon\Carbon::parse($forecast->bills_due_date)->format('M d, Y') }}</td>
                                <td class="text-foreground px-4 py-4 text-center">
                                    {{ $forecast->days_until_due > 0 ? $forecast->days_until_due . ' days' : 'TODAY' }}</td>
                                <td class="text-foreground px-4 py-4 text-center">
                                    <span class="bg-primary/10 text-primary rounded-full px-2 py-1 text-xs font-medium">
                                        {{ $forecast->lease_count_due }}
                                    </span>
                                </td>
                                <td class="text-foreground px-4 py-4 text-center">
                                    <span class="bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 rounded-full px-2 py-1 text-xs font-medium">
                                        {{ $forecast->room_count_due }}
                                    </span>
                                </td>
                                <td class="text-foreground px-4 py-4 text-right font-medium">
                                    ₱{{ number_format($forecast->total_due, 2) }}</td>
                                <td class="text-green-600 dark:text-green-400 px-4 py-4 text-right font-medium">
                                    ₱{{ number_format($forecast->already_collected, 2) }}</td>
                                <td class="px-4 py-4 text-right font-medium {{ $forecast->amount_at_risk > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    ₱{{ number_format($forecast->amount_at_risk, 2) }}</td>
                                <td class="px-4 py-4 text-center text-xs">
                                    <div class="flex justify-center gap-2">
                                        @if ($forecast->payment_count_collected > 0)
                                            <span class="bg-green-100 dark:bg-green-950 text-green-700 dark:text-green-300 px-2 py-1 rounded text-xs">
                                                {{ $forecast->payment_count_collected }} Paid
                                            </span>
                                        @endif
                                        @if ($forecast->payment_count_partial > 0)
                                            <span class="bg-yellow-100 dark:bg-yellow-950 text-yellow-700 dark:text-yellow-300 px-2 py-1 rounded text-xs">
                                                {{ $forecast->payment_count_partial }} Partial
                                            </span>
                                        @endif
                                        @if ($forecast->payment_count_pending > 0)
                                            <span class="bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-300 px-2 py-1 rounded text-xs">
                                                {{ $forecast->payment_count_pending }} Pending
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if ($forecast->priority_flag === 'OVERDUE')
                                        <span class="bg-red-600 text-white px-2 py-1 rounded-full text-xs font-bold">OVERDUE</span>
                                    @elseif ($forecast->priority_flag === 'TODAY')
                                        <span class="bg-orange-600 text-white px-2 py-1 rounded-full text-xs font-bold">TODAY</span>
                                    @elseif ($forecast->priority_flag === 'CRITICAL')
                                        <span class="bg-orange-500 text-white px-2 py-1 rounded-full text-xs font-bold">CRITICAL</span>
                                    @else
                                        <span class="bg-green-600 text-white px-2 py-1 rounded-full text-xs font-bold">NORMAL</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-foreground px-4 py-12 text-center">
                                    <p class="text-muted-foreground">No payment due dates in the next 90 days</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Footer -->
        <div class="text-muted-foreground mt-6 text-xs">
            <p>Last updated: {{ now()->format('M d, Y H:i A') }}</p>
            <p>Data shown is for the next 90 days from today.</p>
        </div>
    </div>
</x-layout>

