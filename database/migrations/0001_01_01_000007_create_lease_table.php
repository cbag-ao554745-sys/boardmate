<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lease', function (Blueprint $table) {
            $table->unsignedInteger('lease_id')->primary()->autoIncrement();
            $table->unsignedInteger('room_id');
            $table->unsignedInteger('landlord_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('monthly_rent', 10, 2);
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->decimal('initial_payment', 10, 2)->default(0);
            $table->unsignedTinyInteger('payment_due_day')->default(1);
            $table->enum('status', ['Active', 'Completed', 'Terminated'])->default('Active');
            $table->timestamps();

            $table->foreign('room_id')
                ->references('room_id')
                ->on('room')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('landlord_id')
                ->references('landlord_id')
                ->on('landlord')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lease');
    }
};
