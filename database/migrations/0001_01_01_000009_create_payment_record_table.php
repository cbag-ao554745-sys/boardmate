<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_record', function (Blueprint $table) {
            $table->unsignedInteger('payment_id')->primary()->autoIncrement();
            $table->unsignedInteger('lease_id');
            $table->unsignedInteger('tenant_id');
            $table->decimal('rent_amount', 10, 2)->default(0);
            $table->decimal('electricity_amount', 10, 2)->default(0);
            $table->decimal('water_amount', 10, 2)->default(0);
            $table->decimal('other_fees', 10, 2)->default(0);

            /**
             * total_amount and balance are computed by the DB trigger on
             * INSERT/UPDATE, but must still exist as regular columns.
             * Default(0) prevents NOT NULL violations if the trigger ever
             * misfires or is run outside MySQL.
             */
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);

            $table->enum('payment_method', ['Cash', 'GCash'])->nullable();
            $table->string('payment_reference', 100)->nullable();

            $table->enum('status', ['Pending', 'Partial', 'Paid', 'Overdue'])->default('Pending');

            $table->date('bills_due_date');

            $table->dateTime('date_paid')->nullable();

            $table->timestamps();

            $table->foreign('lease_id')
                ->references('lease_id')
                ->on('lease')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('tenant_id')
                ->references('tenant_id')
                ->on('tenant')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_record');
    }
};