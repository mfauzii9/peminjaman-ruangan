<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pbm_occurrences', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pbm_id');
            $table->unsignedBigInteger('source_occurrence_id')->nullable();
            $table->tinyInteger('is_rescheduled')->default(0);
            $table->unsignedBigInteger('room_id');
            $table->date('occ_date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('status', ['draft', 'approved', 'rejected', 'cancelled'])->default('draft');
            $table->string('approved_by', 100)->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->text('reschedule_note')->nullable();
            $table->string('rescheduled_by', 100)->nullable();
            $table->dateTime('rescheduled_at')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();

            // Indexes sesuai SQL Dump
            $table->unique(['pbm_id', 'occ_date'], 'uniq_pbm_date');
            $table->index(['room_id', 'start_time', 'end_time'], 'idx_room_time');
            $table->index(['status', 'occ_date'], 'idx_status_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pbm_occurrences');
    }
};