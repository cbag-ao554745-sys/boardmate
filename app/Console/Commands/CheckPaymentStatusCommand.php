<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentRecord;
use App\Models\Notification;
use App\Models\Landlord;
use Carbon\Carbon;

class CheckPaymentStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:check-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for due-soon and overdue payment records and create notifications';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dueSoonThreshold = 3; // days before due date

        // Get all landlords
        $landlords = Landlord::all();

        foreach ($landlords as $landlord) {
            // Check for pending and partial payments
            $payments = PaymentRecord::whereIn('status', ['Pending', 'Partial'])
                ->get();

            foreach ($payments as $payment) {
                $now = Carbon::now();
                $dueDate = Carbon::parse($payment->bills_due_date);

                // Check for overdue payments
                if ($dueDate->isPast() && $now->gt($dueDate)) {
                    // Check if overdue notification already exists
                    $existingNotif = Notification::where('payment_id', $payment->payment_id)
                        ->where('type', 'Overdue')
                        ->exists();

                    if (!$existingNotif) {
                        Notification::create([
                            'payment_id' => $payment->payment_id,
                            'landlord_id' => $landlord->landlord_id,
                            'type' => 'Overdue',
                            'message' => "Payment for Lease #{$payment->lease_id} (Room: {$payment->lease->room->room_number}) is now overdue. Amount: ₱{$payment->total_amount}, Due: {$dueDate->format('M d, Y')}",
                        ]);

                        $this->info("Created overdue notification for Payment #{$payment->payment_id}");
                    }
                }

                // Check for due-soon payments (3 days before)
                elseif ($dueDate->diffInDays($now, false) <= $dueSoonThreshold && $dueDate->isFuture()) {
                    // Check if due-soon notification already exists
                    $existingNotif = Notification::where('payment_id', $payment->payment_id)
                        ->where('type', 'Due Soon')
                        ->exists();

                    if (!$existingNotif) {
                        Notification::create([
                            'payment_id' => $payment->payment_id,
                            'landlord_id' => $landlord->landlord_id,
                            'type' => 'Due Soon',
                            'message' => "Payment for Lease #{$payment->lease_id} (Room: {$payment->lease->room->room_number}) is due soon. Amount: ₱{$payment->total_amount}, Due: {$dueDate->format('M d, Y')}",
                        ]);

                        $this->info("Created due-soon notification for Payment #{$payment->payment_id}");
                    }
                }
            }
        }

        $this->info('Payment status check completed.');
        return Command::SUCCESS;
    }
}
