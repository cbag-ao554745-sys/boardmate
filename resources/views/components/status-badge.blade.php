@props(['status'])

@php
    $statusConfig = match ($status) {
        // Payment statuses
        'Paid' => [
            'bg' => 'bg-green-500/10',
            'text' => 'text-green-600 dark:text-green-400',
            'dot' => 'bg-green-600 dark:bg-green-400',
            'label' => 'PAID',
        ],
        'Pending' => [
            'bg' => 'bg-yellow-500/10',
            'text' => 'text-yellow-600 dark:text-yellow-400',
            'dot' => 'bg-yellow-600 dark:bg-yellow-400',
            'label' => 'PENDING',
        ],
        'Partial' => [
            'bg' => 'bg-blue-500/10',
            'text' => 'text-blue-600 dark:text-blue-400',
            'dot' => 'bg-blue-600 dark:bg-blue-400',
            'label' => 'PARTIAL',
        ],
        'Unpaid' => [
            'bg' => 'bg-red-500/10',
            'text' => 'text-red-600 dark:text-red-400',
            'dot' => 'bg-red-600 dark:bg-red-400',
            'label' => 'UNPAID',
        ],
        'Overdue' => [
            'bg' => 'bg-red-500/10',
            'text' => 'text-red-600 dark:text-red-400',
            'dot' => 'bg-red-600 dark:bg-red-400',
            'label' => 'OVERDUE',
        ],
        // Lease statuses
        'Active' => [
            'bg' => 'bg-green-500/10',
            'text' => 'text-green-600 dark:text-green-400',
            'dot' => 'bg-green-600 dark:bg-green-400',
            'label' => 'ACTIVE',
        ],
        'Completed' => [
            'bg' => 'bg-blue-500/10',
            'text' => 'text-blue-600 dark:text-blue-400',
            'dot' => 'bg-blue-600 dark:bg-blue-400',
            'label' => 'COMPLETED',
        ],
        'Terminated' => [
            'bg' => 'bg-slate-500/10',
            'text' => 'text-slate-600 dark:text-slate-400',
            'dot' => 'bg-slate-600 dark:bg-slate-400',
            'label' => 'TERMINATED',
        ],
        // Room statuses
        'Available' => [
            'bg' => 'bg-emerald-500/10',
            'text' => 'text-emerald-600 dark:text-emerald-400',
            'dot' => 'bg-emerald-600 dark:bg-emerald-400',
            'label' => 'AVAILABLE',
        ],
        'Occupied' => [
            'bg' => 'bg-purple-500/10',
            'text' => 'text-purple-600 dark:text-purple-400',
            'dot' => 'bg-purple-600 dark:bg-purple-400',
            'label' => 'OCCUPIED',
        ],
        'Maintenance' => [
            'bg' => 'bg-orange-500/10',
            'text' => 'text-orange-600 dark:text-orange-400',
            'dot' => 'bg-orange-600 dark:bg-orange-400',
            'label' => 'UNDER MAINTENANCE',
        ],
        'Under Maintenance' => [
            'bg' => 'bg-orange-500/10',
            'text' => 'text-orange-600 dark:text-orange-400',
            'dot' => 'bg-orange-600 dark:bg-orange-400',
            'label' => 'UNDER MAINTENANCE',
        ],
        // Tenant statuses
        'Inactive' => [
            'bg' => 'bg-gray-500/10',
            'text' => 'text-gray-600 dark:text-gray-400',
            'dot' => 'bg-gray-600 dark:bg-gray-400',
            'label' => 'INACTIVE',
        ],
        'Blacklisted' => [
            'bg' => 'bg-red-500/10',
            'text' => 'text-red-600 dark:text-red-400',
            'dot' => 'bg-red-600 dark:bg-red-400',
            'label' => 'BLACKLISTED',
        ],
        // Default fallback
        default => [
            'bg' => 'bg-slate-500/10',
            'text' => 'text-slate-600 dark:text-slate-400',
            'dot' => 'bg-slate-600 dark:bg-slate-400',
            'label' => strtoupper($status),
        ],
    };
    $config = $statusConfig;
@endphp

<span
    class="inline-flex items-center gap-1 rounded-full {{ $config['bg'] }} px-3 py-1 text-xs font-semibold {{ $config['text'] }}">
    <span class="h-1.5 w-1.5 rounded-full {{ $config['dot'] }}"></span>
    {{ $config['label'] }}
</span>
