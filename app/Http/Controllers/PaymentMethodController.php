<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of payment methods.
     */
    public function index()
    {
        $methods = PaymentMethod::paginate(10);

        return view('payment-methods.index', compact('methods'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create()
    {
        return view('payment-methods.create');
    }

    /**
     * Store a newly created payment method in storage.
     */
    public function store(StorePaymentMethodRequest $request)
    {
        $paymentMethod = PaymentMethod::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
            'requires_reference' => (bool) $request->requires_reference,
        ]);

        return redirect()->route('payment-methods.index', $paymentMethod->payment_method_id)
            ->with('success', 'Payment method created successfully.');
    }

    /**
     * Display the specified payment method.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return view('payment-methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified payment method in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->update([
            'name' => $request->name,
            'description' => $request->description,
            'requires_reference' => (bool) $request->requires_reference,
        ]);

        return redirect()->route('payment-methods.index', $paymentMethod->payment_method_id)
            ->with('success', 'Payment method updated successfully.');
    }

    /**
     * Remove the specified payment method from storage (soft delete via deactivation).
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Check if payment method is in use
        $paymentRecordCount = $paymentMethod->paymentRecords()->count();

        if ($paymentRecordCount > 0) {
            // Deactivate instead of deleting if in use
            $paymentMethod->update(['is_active' => false]);

            return redirect()->route('payment-methods.index')
                ->with('success', 'Payment method deactivated (has ' . $paymentRecordCount . ' associated payment records).');
        }

        // Safe to delete if not in use
        $paymentMethod->delete();

        return redirect()->route('payment-methods.index')
            ->with('success', 'Payment method deleted successfully.');
    }

    /**
     * Toggle the active status of a payment method.
     */
    public function toggleActive(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $paymentMethod->is_active,
            'message' => $paymentMethod->is_active ? 'Payment method activated.' : 'Payment method deactivated.',
        ]);
    }
}
