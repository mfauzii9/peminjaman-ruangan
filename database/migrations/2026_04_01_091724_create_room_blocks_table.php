<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_blocks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            
            $table->integer('id', true);
            $table->integer('room_id');
            $table->string('title', 150)->nullable();
            $table->text('note')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('source', ['jadwal', 'admin', 'kemahasiswaan', 'mahasiswa'])->default('jadwal');
            $table->enum('status', ['draft', 'terbooking', 'cancel'])->default('terbooking');
            $table->integer('created_by')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();

            // Index
            $table->index(['room_id', 'start_time', 'end_time'], 'idx_room_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_blocks');
    }
};