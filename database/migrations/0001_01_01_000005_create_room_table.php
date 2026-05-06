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
        Schema::create('room', function (Blueprint $table) {
            $table->unsignedInteger('room_id')->primary()->autoIncrement();
            $table->unsignedInteger('landlord_id');
            $table->string('room_number', 20);
            $table->unsignedTinyInteger('floor')->nullable();
            $table->decimal('monthly_rent', 10, 2);
            $table->enum('status', ['Available', 'Occupied', 'Under Maintenance'])->default('Available');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['landlord_id', 'room_number']);
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
        Schema::dropIfExists('room');
    }
};
