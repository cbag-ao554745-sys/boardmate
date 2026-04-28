<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Cash',
                'description' => 'Direct cash payment',
                'is_active' => true,
                'requires_reference' => false,
            ],
            [
                'name' => 'GCash',
                'description' => 'Mobile wallet payment via GCash app. Requires transaction ID or reference number.',
                'is_active' => true,
                'requires_reference' => true,
            ],
            [
                'name' => 'Bank Transfer',
                'description' => 'Payment via bank deposit or online transfer. Requires bank reference number or check number.',
                'is_active' => true,
                'requires_reference' => true,
            ],
            [
                'name' => 'Online Payment',
                'description' => 'Payment through online banking or payment gateway. Requires confirmation number.',
                'is_active' => true,
                'requires_reference' => true,
            ],
            [
                'name' => 'Check',
                'description' => 'Payment by check. Requires check number.',
                'is_active' => true,
                'requires_reference' => true,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::firstOrCreate(
                ['name' => $method['name']],
                $method
            );
        }
    }
}
