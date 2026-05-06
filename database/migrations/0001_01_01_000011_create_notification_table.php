<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->unsignedInteger('notification_id')->primary()->autoIncrement();
            $table->unsignedInteger('payment_id')->nullable();
            $table->unsignedInteger('landlord_id');

            /**
             * ENUM must match the Notification model constants and what
             * NotificationController::mapNotificationType() matches against.
             * Values: 'Due Soon', 'Overdue', 'Payment Received', 'System'
             * Original migration already had this correct — confirmed.
             */
            $table->enum('type', ['Due Soon', 'Overdue', 'Payment Received', 'System'])
                ->default('System');

            $table->text('message');
            $table->dateTime('sent_at')->useCurrent();
            $table->boolean('is_read')->default(false);

            $table->timestamps();

            $table->foreign('payment_id')
                ->references('payment_id')
                ->on('payment_record')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('landlord_id')
                ->references('landlord_id')
                ->on('landlord')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};