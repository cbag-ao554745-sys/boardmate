<x-layout title="Record Payment">

    <div>

        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('payments.index') }}" class="hover:bg-muted rounded-lg p-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-muted-foreground">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
            </a>
            <div>
                <h1 class="text-foreground text-3xl font-bold">Record Payment</h1>
                <p class="text-muted-foreground mt-1 text-sm">Record a payment transaction</p>
            </div>
        </div>

        <div class="border-border bg-card mx-auto rounded-xl border p-8">

            <form method="POST" action="{{ route('payments.store') }}" class="space-y-6">
                @csrf

                <div class="grid gap-2">
                    <label for="lease_id" class="text-foreground text-sm font-medium">
                        Lease <span class="text-destructive">*</span>
                    </label>
                    <select id="lease_id" name="lease_id"
                        class="border-input bg-background text-foreground
                                   focus-visible:border-ring focus-visible:ring-ring/50
                                   flex h-9 w-full rounded-md border px-3 py-1 text-sm 
                                   transition-colors focus-visible:outline-none focus-visible:ring-[3px] appearance-none
                                   @error('lease_id') border-destructive focus-visible:ring-destructive/50 @enderror">
                        <option value="">Select a lease</option>
                        @foreach ($leases as $lease)
                            <option value="{{ $lease['lease_id'] }}"
                                {{ old('lease_id') == $lease['lease_id'] ? 'selected' : '' }}>
                                Room {{ $lease['room_number'] }} — {{ $lease['tenant_names'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('lease_id')
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

                <div class="grid gap-2">
                    <label for="tenant_id" class="text-foreground text-sm font-medium">
                        Tenant <span class="text-destructive">*</span>
                    </label>
                    <select id="tenant_id" name="tenant_id"
                        class="border-input bg-background text-foreground
                                   focus-visible:border-ring focus-visible:ring-ring/50
                                   flex h-9 w-full rounded-md border px-3 py-1 text-sm 
                                   transition-colors focus-visible:outline-none focus-visible:ring-[3px] appearance-none
                                   @error('tenant_id') border-destructive focus-visible:ring-destructive/50 @enderror">
                        <option value="">Select a lease first</option>
                    </select>
                    @error('tenant_id')
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

                <div class="border-border rounded-lg border p-4 space-y-4">
                    <p class="text-foreground text-sm font-medium">Amount Breakdown</p>

                    <div class="grid grid-cols-2 gap-4">

                        <div class="grid gap-2">
                            <label for="rent_amount" class="text-foreground text-sm font-medium">Rent (₱)</label>
                            <input type="number" id="rent_amount" name="rent_amount" min="0" step="0.01"
                                value="{{ old('rent_amount', 0) }}"
                                class="border-input bg-background text-foreground placeholder:text-muted-foreground
                                          focus-visible:border-ring focus-visible:ring-ring/50
                                          flex h-9 w-full rounded-md border px-3 py-1 text-sm 
                                          transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                                          @error('rent_amount') border-destructive focus-visible:ring-destructive/50 @enderror" />
                            @error('rent_amount')
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
                            <label for="electricity_amount" class="text-foreground text-sm font-medium">Electricity
                                (₱)</label>
                            <input type="number" id="electricity_amount" name="electricity_amount" min="0"
                                step="0.01" value="{{ old('electricity_amount', 0) }}"
                                class="border-input bg-background text-foreground placeholder:text-muted-foreground
                                          focus-visible:border-ring focus-visible:ring-ring/50
                                          flex h-9 w-full rounded-md border px-3 py-1 text-sm 
                                          transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                                          @error('electricity_amount') border-destructive focus-visible:ring-destructive/50 @enderror" />
                            @error('electricity_amount')
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
                            <label for="water_amount" class="text-foreground text-sm font-medium">Water (₱)</label>
                            <input type="number" id="water_amount" name="water_amount" min="0"
                                step="0.01" value="{{ old('water_amount', 0) }}"
                                class="border-input bg-background text-foreground placeholder:text-muted-foreground
                                          focus-visible:border-ring focus-visible:ring-ring/50
                                          flex h-9 w-full rounded-md border px-3 py-1 text-sm 
                                          transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                                          @error('water_amount') border-destructive focus-visible:ring-destructive/50 @enderror" />
                            @error('water_amount')
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
                            <label for="other_fees" class="text-foreground text-sm font-medium">Other Fees (₱)</label>
                            <input type="number" id="other_fees" name="other_fees" min="0" step="0.01"
                                value="{{ old('other_fees', 0) }}"
                                class="border-input bg-background text-foreground placeholder:text-muted-foreground
                                          focus-visible:border-ring focus-visible:ring-ring/50
                                          flex h-9 w-full rounded-md border px-3 py-1 text-sm 
                                          transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                                          @error('other_fees') border-destructive focus-visible:ring-destructive/50 @enderror" />
                            @error('other_fees')
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

                    <div
                        class="border-primary/20 bg-primary/10 flex items-center justify-between rounded-lg border p-3">
                        <span class="text-foreground text-sm">Total Amount</span>
                        <span class="text-primary text-lg font-bold" id="total_display">₱0.00</span>
                    </div>
                </div>

                <div class="border-border rounded-lg border p-4 space-y-4">
                    <p class="text-foreground text-sm font-medium">Payment Details</p>

                    <div class="grid gap-2">
                        <label for="amount_paid" class="text-foreground text-sm font-medium">
                            Amount Paid (₱) <span class="text-muted-foreground font-normal">(Optional)</span>
                        </label>
                        <input type="number" id="amount_paid" name="amount_paid" min="0" step="0.01"
                            value="{{ old('amount_paid', 0) }}"
                            class="border-input bg-background text-foreground placeholder:text-muted-foreground
                                      focus-visible:border-ring focus-visible:ring-ring/50
                                      flex h-9 w-full rounded-md border px-3 py-1 text-sm 
                                      transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                                      @error('amount_paid') border-destructive focus-visible:ring-destructive/50 @enderror" />
                        @error('amount_paid')
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
                        <label for="payment_method" class="text-foreground text-sm font-medium">
                            Payment Method <span class="text-muted-foreground font-normal">(Optional)</span>
                        </label>
                        <select id="payment_method" name="payment_method"
                            class="border-input bg-background text-foreground
                                       focus-visible:border-ring focus-visible:ring-ring/50
                                       flex h-9 w-full rounded-md border px-3 py-1 text-sm 
                                       transition-colors focus-visible:outline-none focus-visible:ring-[3px] appearance-none
                                       @error('payment_method') border-destructive focus-visible:ring-destructive/50 @enderror">
                            <option value="">— Select Method —</option>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method->name }}"
                                    data-requires-reference="{{ $method->requires_reference ? 'true' : 'false' }}"
                                    {{ old('payment_method') === $method->name ? 'selected' : '' }}>
                                    {{ $method->name }}
                                </option>
                            @endforeach
                        </select>
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

                    <div class="grid gap-2" id="reference_number_field" style="display: none;">
                        <label for="payment_reference" class="text-foreground text-sm font-medium">
                            Reference Number <span id="reference_required_badge" class="text-destructive"></span><span id="reference_optional_badge" class="text-muted-foreground font-normal">(Optional)</span>
                        </label>
                        <input type="text" id="payment_reference" name="payment_reference"
                            value="{{ old('payment_reference') }}" placeholder="e.g., GCash Reference, Check #, Bank Reference"
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

                    <script>
                        document.getElementById('payment_method').addEventListener('change', function() {
                            const selectedOption = this.options[this.selectedIndex];
                            const requiresReference = selectedOption.getAttribute('data-requires-reference') === 'true';
                            const refField = document.getElementById('reference_number_field');
                            const refInput = document.getElementById('payment_reference');
                            const requiredBadge = document.getElementById('reference_required_badge');
                            const optionalBadge = document.getElementById('reference_optional_badge');

                            if (this.value && requiresReference) {
                                refField.style.display = 'block';
                                refInput.required = true;
                                requiredBadge.textContent = '*';
                                requiredBadge.style.display = 'inline';
                                optionalBadge.style.display = 'none';
                            } else if (this.value) {
                                refField.style.display = 'block';
                                refInput.required = false;
                                requiredBadge.style.display = 'none';
                                optionalBadge.style.display = 'inline';
                            } else {
                                refField.style.display = 'none';
                                refInput.required = false;
                            }
                        });
                    </script>

                    <div class="grid grid-cols-2 gap-4">

                        <div class="grid gap-2">
                            <label for="bills_due_date" class="text-foreground text-sm font-medium">
                                Bills Due Date <span class="text-destructive">*</span>
                            </label>
                            <input type="date" id="bills_due_date" name="bills_due_date"
                                value="{{ old('bills_due_date') }}"
                                class="border-input bg-background text-foreground
                                          focus-visible:border-ring focus-visible:ring-ring/50
                                          flex h-9 w-full rounded-md border px-3 py-1 text-sm 
                                          transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                                          @error('bills_due_date') border-destructive focus-visible:ring-destructive/50 @enderror" />
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

                        <div class="grid gap-2">
                            <label for="date_paid" class="text-foreground text-sm font-medium">
                                Date Paid <span class="text-muted-foreground font-normal">(Optional)</span>
                            </label>
                            <input type="date" id="date_paid" name="date_paid" value="{{ old('date_paid') }}"
                                class="border-input bg-background text-foreground
                                          focus-visible:border-ring focus-visible:ring-ring/50
                                          flex h-9 w-full rounded-md border px-3 py-1 text-sm 
                                          transition-colors focus-visible:outline-none focus-visible:ring-[3px]
                                          @error('date_paid') border-destructive focus-visible:ring-destructive/50 @enderror" />
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
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('payments.index') }}"
                        class="border-border text-foreground hover:bg-muted flex-1 rounded-lg border px-4 py-2.5
                              text-center text-sm font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-primary text-primary-foreground hover:bg-primary/90 flex-1 rounded-lg px-4
                                   py-2.5 text-center text-sm font-medium transition-colors">
                        Record Payment
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        const leasesData = @json($leases);

        document.getElementById('lease_id').addEventListener('change', function() {
            const leaseId = this.value;
            const tenantSelect = document.getElementById('tenant_id');
            const rentInput = document.getElementById('rent_amount');
            const dueDateInput = document.getElementById('bills_due_date');

            tenantSelect.innerHTML = '';

            if (!leaseId) {
                tenantSelect.innerHTML = '<option value="">Select a lease first</option>';
                rentInput.value = 0;
                dueDateInput.value = '';
                updateTotal();
                return;
            }

            const lease = leasesData.find(l => String(l.lease_id) === String(leaseId));
            if (lease) {
                tenantSelect.innerHTML = '<option value="">Select a tenant</option>';
                lease.tenants.forEach(function(t) {
                    const opt = document.createElement('option');
                    opt.value = t.tenant_id;
                    opt.textContent = t.first_name + ' ' + t.last_name + (t.is_primary ? ' (Primary)' : '');
                    if (t.is_primary) opt.selected = true;
                    tenantSelect.appendChild(opt);
                });
                rentInput.value = lease.monthly_rent;
                
                // Calculate bills due date
                const today = new Date();
                const currentYear = today.getFullYear();
                const currentMonth = today.getMonth(); // 0-11
                const dueDay = lease.payment_due_day;
                
                // Create date with payment_due_day in current month
                let dueDate = new Date(currentYear, currentMonth, dueDay);
                
                // If the due date has already passed this month, use next month's due date
                if (dueDate < today) {
                    dueDate = new Date(currentYear, currentMonth + 1, dueDay);
                }
                
                // Format as YYYY-MM-DD for input[type="date"]
                const year = dueDate.getFullYear();
                const month = String(dueDate.getMonth() + 1).padStart(2, '0');
                const day = String(dueDate.getDate()).padStart(2, '0');
                dueDateInput.value = `${year}-${month}-${day}`;
                
                updateTotal();
            }
        });

        ['rent_amount', 'electricity_amount', 'water_amount', 'other_fees'].forEach(function(id) {
            document.getElementById(id).addEventListener('input', updateTotal);
        });

        function updateTotal() {
            const rent = parseFloat(document.getElementById('rent_amount').value) || 0;
            const elec = parseFloat(document.getElementById('electricity_amount').value) || 0;
            const water = parseFloat(document.getElementById('water_amount').value) || 0;
            const other = parseFloat(document.getElementById('other_fees').value) || 0;
            document.getElementById('total_display').textContent =
                '₱' + (rent + elec + water + other).toLocaleString('en-US', {
                    minimumFractionDigits: 2
                });
        }

        document.addEventListener('DOMContentLoaded', updateTotal);
    </script>

</x-layout>
