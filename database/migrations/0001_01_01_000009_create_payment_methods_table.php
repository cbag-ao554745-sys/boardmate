<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->unsignedInteger('payment_method_id')->primary()->autoIncrement();
            $table->string('name', 255);
            $table->string('code', 100)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_reference')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // Seed initial payment methods
        DB::table('payment_methods')->insert([
            [
                'payment_method_id' => 1,
                'name' => 'Cash',
                'code' => 'cash',
                'description' => 'Cash payment',
                'is_active' => true,
                'requires_reference' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method_id' => 2,
                'name' => 'GCash',
                'code' => 'gcash',
                'description' => 'GCash mobile payment',
                'is_active' => true,
                'requires_reference' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
