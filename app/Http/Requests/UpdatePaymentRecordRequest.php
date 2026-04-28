<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\PaymentMethod;

class UpdatePaymentRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rent_amount' => ['required', 'numeric', 'min:0'],
            'electricity_amount' => ['required', 'numeric', 'min:0'],
            'water_amount' => ['required', 'numeric', 'min:0'],
            'other_fees' => ['required', 'numeric', 'min:0'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['nullable', Rule::in(PaymentMethod::where('is_active', true)->pluck('name')->toArray())],
            'payment_reference' => $this->getPaymentReferenceRules(),
            'bills_due_date' => ['required', 'date'],
            'date_paid' => ['nullable', 'date'],
        ];
    }

    /**
     * Get validation rules for payment_reference based on the selected payment method
     */
    protected function getPaymentReferenceRules(): array
    {
        if (!$this->payment_method) {
            return ['nullable', 'string'];
        }

        $method = PaymentMethod::where('name', $this->payment_method)
            ->where('is_active', true)
            ->first();

        if ($method && $method->requires_reference) {
            return ['required', 'string', 'min:1'];
        }

        return ['nullable', 'string'];
    }

    public function messages(): array
    {
        return [
            'payment_reference.required' => 'Reference number is required for this payment method.',
        ];
    }
}
