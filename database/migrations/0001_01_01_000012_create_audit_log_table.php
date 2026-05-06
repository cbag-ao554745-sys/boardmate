<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedInteger('landlord_id');

            /**
             * Action ENUM must match AuditLog model constants exactly:
             * ACTION_INSERT, ACTION_UPDATE, ACTION_DELETE, ACTION_LOGIN, ACTION_LOGOUT.
             * Original migration already has this correct — keeping as-is.
             */
            $table->enum('action', ['INSERT', 'UPDATE', 'DELETE', 'LOGIN', 'LOGOUT']);

            /**
             * table_name column was present in the migration,
             * and must also be in the model $fillable — confirmed fixed in model.
             * AuditLogController filters by table_name — this column is essential.
             */
            $table->string('table_name', 50);

            $table->unsignedInteger('record_id')->nullable();
            $table->text('description');
            $table->timestamp('timestamp')->useCurrent();

            /**
             * audit_log intentionally has NO updated_at / created_at.
             * The model sets public $timestamps = false to match.
             */

            $table->foreign('landlord_id')
                ->references('landlord_id')
                ->on('landlord')
                ->onUpdate('cascade')
                ->onDelete('restrict'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }
};