<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pbm_templates', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            
            $table->integer('id', true);
            $table->integer('room_id');
            $table->string('mata_kuliah', 150)->nullable();
            $table->string('kelas', 50)->nullable();
            $table->string('dosen', 150)->nullable();
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'])->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('semester', 100)->nullable();
            $table->tinyInteger('aktif')->default(1);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pbm_templates');
    }
};