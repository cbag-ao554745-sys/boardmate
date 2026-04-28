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
        Schema::create('tenant', function (Blueprint $table) {
            $table->unsignedInteger('tenant_id')->primary()->autoIncrement();
            $table->unsignedInteger('person_id');
            $table->unsignedInteger('guardian_person_id')->nullable();
            $table->unsignedInteger('landlord_id');
            $table->enum('status', ['Active', 'Inactive', 'Blacklisted'])->default('Active');
            $table->timestamps();

            $table->unique('person_id');
            $table->foreign('person_id')
                ->references('person_id')
                ->on('person')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('guardian_person_id')
                ->references('person_id')
                ->on('person')
                ->onUpdate('cascade')
                ->onDelete('set null');
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
        Schema::dropIfExists('tenant');
    }
};
