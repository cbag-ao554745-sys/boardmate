<x-layout title="Edit Payment">

    <div>

        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('payments.show', $payment->payment_id) }}"
                class="hover:bg-muted rounded-lg p-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-muted-foreground">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
            </a>
            <div>
                <h1 class="text-foreground text-3xl font-bold">Edit Payment</h1>
                <p class="text-muted-foreground mt-1 text-sm">Update payment details</p>
            </div>
        </div>

        <div class="border-border bg-card mx-auto rounded-xl border p-8" x-data="{
            amountPaid: {{ (float) old('amount_paid', $payment->amount_paid ?? 0) }},
            get showPaymentFields() { return parseFloat(this.amountPaid) > 0; }
        }">

            <form method="POST" action="{{ route('payments.update', $payment->payment_id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="border-border rounded-lg border p-4 space-y-4">
                    <p class="text-foreground text-sm font-medium">Amount Breakdown</p>

                    @foreach ([['rent_amount', 'Rent (₱)', $payment->rent_amount], ['electricity_amount', 'Electricity (₱)', $payment->electricity_amount], ['water_amount', 'Water (₱)', $payment->water_amount], ['other_fees', 'Other Fees (₱)', $payment->other_fees]] as [$field, $label, $val])
                        <div class="grid gap-2">
                            <label for="{{ $field }}"
                                class="text-foreground text-sm font-medium">{{ $label }}</label>
                            <input type="number" name="{{ $field }}" id="{{ $field }}" min="0"
                                step="0.01" value="{{ old($field, $val ?? 0) }}"
                                class="border-input bg-background text-foreground placeholder:text-muted-foreground
                                          focus-visible:border-ring focus-visible:ring-ring/50
                                          flex h-9 w-full rounded-md border px-3 py-1 text-sm
                                          transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                                          @error($field) border-destructive focus-visible:ring-destructive/50 @enderror" />
                            @error($field)
                                <div class="text-destructive flex items-center gap-1.5 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" x2="12" y1="8" y2="12" />
                                        <line x1="12" x2="12.01" y1="16" y2="16" />
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @endforeach

                    <div
                        class="border-primary/20 bg-primary/10 flex items-center justify-between rounded-lg border p-3">
                        <span class="text-foreground text-sm">Total Amount</span>
                        <span class="text-primary text-lg font-bold" id="edit_total_display">
                            ₱{{ number_format(($payment->rent_amount ?? 0) + ($payment->electricity_amount ?? 0) + ($payment->water_amount ?? 0) + ($payment->other_fees ?? 0), 2) }}
                        </span>
                    </div>
                </div>

                <div class="border-border rounded-lg border p-4 space-y-4">
                    <p class="text-foreground text-sm font-medium">Payment Details</p>

                    <div class="grid gap-2">
                        <label for="edit_amount_paid" class="text-foreground text-sm font-medium">Amount Paid
                            (₱)</label>
                        <input type="number" name="amount_paid" id="edit_amount_paid" min="0" step="0.01"
                            value="{{ old('amount_paid', $payment->amount_paid ?? 0) }}" x-model.number="amountPaid"
                            class="border-input bg-background text-foreground placeholder:text-muted-foreground
                                      focus-visible:border-ring focus-visible:ring-ring/50
                                      flex h-9 w-full rounded-md border px-3 py-1 text-sm
                                      transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                                      @error('amount_paid') border-destructive focus-visible:ring-destructive/50 @enderror" />
                        @error('amount_paid')
                            <div class="text-destructive flex items-center gap-1.5 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="shrink-0">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="12" x2="12" y1="8" y2="12" />
                                    <line x1="12" x2="12.01" y1="16" y2="16" />
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <template x-if="showPaymentFields">
                        <div class="space-y-4">

                            <div class="grid gap-2">
                                <label class="text-foreground text-sm font-medium">Payment Method</label>
                                <div class="relative">
                                    <select id="payment_method_edit" name="payment_method"
                                        class="border-input bg-background text-foreground
                                                   focus-visible:border-ring focus-visible:ring-ring/50
                                                   flex h-9 w-full rounded-md border px-3 py-1 text-sm pr-8
                                                   transition-colors focus-visible:outline-none focus-visible:ring-[3px] appearance-none
                                                   @error('payment_method') border-destructive focus-visible:ring-destructive/50 @enderror">
                                        <option value="">— Select Method —</option>
                                        @foreach ($paymentMethods as $method)
                                            <option value="{{ $method->name }}"
                                                data-requires-reference="{{ $method->requires_reference ? 'true' : 'false' }}"
                                                {{ old('payment_method', $payment->paymentMethod?->name) === $method->name ? 'selected' : '' }}>
                                                {{ $method->name }}</option>
                                        @endforeach
                                    </select>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground pointer-events-none absolute right-2 top-1/2 -translate-y-1/2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </div>
                                @error('payment_method')
                                    <div class="text-destructive flex items-center gap-1.5 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="12" x2="12" y1="8" y2="12" />
                                            <line x1="12" x2="12.01" y1="16" y2="16" />
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="grid gap-2" id="reference_number_field_edit" style="display: none;">
                                <label class="text-foreground text-sm font-medium">
                                    Reference Number
                                    <span id="reference_required_badge_edit" class="text-destructive"></span><span id="reference_optional_badge_edit" class="text-muted-foreground font-normal">(Optional)</span>
                                </label>
                                <input type="text" id="payment_reference_edit" name="payment_reference"
                                    value="{{ old('payment_reference', $payment->payment_reference) }}"
                                    placeholder="e.g., GCash Reference, Check #, Bank Reference"
                                    class="border-input bg-background text-foreground placeholder:text-muted-foreground
                                              focus-visible:border-ring focus-visible:ring-ring/50
                                              flex h-9 w-full rounded-md border px-3 py-1 text-sm
                                              transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                                              @error('payment_reference') border-destructive focus-visible:ring-destructive/50 @enderror" />
                                @error('payment_reference')
                                    <div class="text-destructive flex items-center gap-1.5 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="12" x2="12" y1="8" y2="12" />
                                            <line x1="12" x2="12.01" y1="16" y2="16" />
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="grid gap-2">
                                <label class="text-foreground text-sm font-medium">Payment Date</label>
                                <div class="relative">
                                    <input type="date" name="date_paid"
                                        value="{{ old('date_paid', $payment->date_paid?->format('Y-m-d')) }}"
                                        class="border-input bg-background text-foreground
                                                  focus-visible:border-ring focus-visible:ring-ring/50
                                                  flex h-9 w-full rounded-md border px-3 py-1 text-sm pr-8
                                                  transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                                                  @error('date_paid') border-destructive focus-visible:ring-destructive/50 @enderror" />
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground pointer-events-none absolute right-2 top-1/2 -translate-y-1/2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2" /><path d="M16 2v4" /><path d="M8 2v4" /><path d="M3 10h18" /></svg>
                                </div>
                                @error('date_paid')
                                    <div class="text-destructive flex items-center gap-1.5 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="12" x2="12" y1="8" y2="12" />
                                            <line x1="12" x2="12.01" y1="16" y2="16" />
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </template>

                    <script>
                        function updateReferenceFieldEdit() {
                            const selectedOption = document.getElementById('payment_method_edit').options[document.getElementById('payment_method_edit').selectedIndex];
                            const requiresReference = selectedOption.getAttribute('data-requires-reference') === 'true';
                            const refField = document.getElementById('reference_number_field_edit');
                            const refInput = document.getElementById('payment_reference_edit');
                            const requiredBadge = document.getElementById('reference_required_badge_edit');
                            const optionalBadge = document.getElementById('reference_optional_badge_edit');

                            if (document.getElementById('payment_method_edit').value && requiresReference) {
                                refField.style.display = 'block';
                                refInput.required = true;
                                requiredBadge.textContent = '*';
                                requiredBadge.style.display = 'inline';
                                optionalBadge.style.display = 'none';
                            } else if (document.getElementById('payment_method_edit').value) {
                                refField.style.display = 'block';
                                refInput.required = false;
                                requiredBadge.style.display = 'none';
                                optionalBadge.style.display = 'inline';
                            } else {
                                refField.style.display = 'none';
                                refInput.required = false;
                            }
                        }

                        document.getElementById('payment_method_edit').addEventListener('change', updateReferenceFieldEdit);
                        
                        // Initialize on page load if method is already selected
                        updateReferenceFieldEdit();
                    </script>

                    <div class="grid gap-2">
                        <label class="text-foreground text-sm font-medium">Bill Due Date</label>
                        <div class="relative">
                            <input type="date" name="bills_due_date"
                                value="{{ old('bills_due_date', $payment->bills_due_date?->format('Y-m-d')) }}"
                                class="border-input bg-background text-foreground
                                          focus-visible:border-ring focus-visible:ring-ring/50
                                          flex h-9 w-full rounded-md border px-3 py-1 text-sm pr-8
                                          transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                                          @error('bills_due_date') border-destructive focus-visible:ring-destructive/50 @enderror" />
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground pointer-events-none absolute right-2 top-1/2 -translate-y-1/2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2" /><path d="M16 2v4" /><path d="M8 2v4" /><path d="M3 10h18" /></svg>
                        </div>
                        @error('bills_due_date')
                            <div class="text-destructive flex items-center gap-1.5 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="12" x2="12" y1="8" y2="12" />
                                    <line x1="12" x2="12.01" y1="16" y2="16" />
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('payments.show', $payment->payment_id) }}"
                        class="border-border text-foreground hover:bg-muted flex-1 rounded-lg border px-4
                              py-2.5 text-center text-sm font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-primary text-primary-foreground hover:bg-primary/90 flex-1 rounded-lg
                                   px-4 py-2.5 text-center text-sm font-medium transition-colors">
                        Save Payment
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        ['rent_amount', 'electricity_amount', 'water_amount', 'other_fees'].forEach(function(id) {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', updateEditTotal);
        });

        function updateEditTotal() {
            const rent = parseFloat(document.getElementById('rent_amount')?.value) || 0;
            const elec = parseFloat(document.getElementById('electricity_amount')?.value) || 0;
            const water = parseFloat(document.getElementById('water_amount')?.value) || 0;
            const other = parseFloat(document.getElementById('other_fees')?.value) || 0;
            const disp = document.getElementById('edit_total_display');
            if (disp) disp.textContent = '₱' + (rent + elec + water + other).toLocaleString('en-US', {
                minimumFractionDigits: 2
            });
        }
    </script>

</x-layout>
