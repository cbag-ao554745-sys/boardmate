<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of all payment methods.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::paginate(10);

        return view('payment-methods.index', compact('paymentMethods'));
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
        PaymentMethod::create($request->validated());

        return redirect()->route('payment-methods.index')->with('success', 'Payment method created successfully.');
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
        $paymentMethod->update($request->validated());

        return redirect()->route('payment-methods.index')->with('success', 'Payment method updated successfully.');
    }

    /**
     * Deactivate the specified payment method (soft delete via is_active flag).
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_active' => false]);

        return redirect()->route('payment-methods.index')->with('success', 'Payment method deactivated successfully.');
    }
}
