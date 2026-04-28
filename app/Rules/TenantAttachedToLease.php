<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class TenantAttachedToLease implements ValidationRule
{
    protected $leaseId;

    public function __construct($leaseId)
    {
        $this->leaseId = $leaseId;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        // Check if tenant_id exists in lease_tenant table for the given lease_id
        $attached = \DB::table('lease_tenant')
            ->where('lease_id', $this->leaseId)
            ->where('tenant_id', $value)
            ->exists();

        if (!$attached) {
            $fail("The selected tenant is not attached to this lease.");
        }
    }
}
