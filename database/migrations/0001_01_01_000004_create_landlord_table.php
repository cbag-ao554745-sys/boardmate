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
        Schema::create('landlord', function (Blueprint $table) {
            $table->unsignedInteger('landlord_id')->primary()->autoIncrement();
            $table->unsignedInteger('person_id');
            $table->string('username', 50)->unique();
            $table->string('password_hash', 255);
            $table->timestamps();

            $table->unique('person_id');
            $table->foreign('person_id')
                ->references('person_id')
                ->on('person')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landlord');
    }
};
