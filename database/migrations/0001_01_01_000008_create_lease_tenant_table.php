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
        Schema::create('lease_tenant', function (Blueprint $table) {
            $table->unsignedInteger('lease_tenant_id')->primary()->autoIncrement();
            $table->unsignedInteger('lease_id');
            $table->unsignedInteger('tenant_id');
            $table->boolean('is_primary_tenant')->default(false);
            $table->date('move_in_date');
            $table->date('move_out_date')->nullable();
            $table->timestamps();

            $table->unique(['lease_id', 'tenant_id']);
            $table->foreign('lease_id')
                ->references('lease_id')
                ->on('lease')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('tenant_id')
                ->references('tenant_id')
                ->on('tenant')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lease_tenant');
    }
};
