<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrow_requests', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            
            $table->bigIncrements('id');
            $table->string('public_code', 20)->nullable()->unique('uq_borrow_requests_public_code');
            $table->char('token_hash', 64)->nullable()->index('idx_borrow_requests_token_hash');
            $table->dateTime('token_created_at')->nullable();
            $table->integer('room_id')->index();
            $table->string('email', 120)->index('idx_borrow_requests_email');
            $table->string('responsible_name', 120);
            $table->string('org_name', 150);
            $table->string('phone', 30);
            $table->dateTime('start_time')->index();
            $table->dateTime('end_time')->index();
            $table->string('letter_file', 255)->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'selesai', 'hangus'])->default('menunggu')->index();
            $table->enum('kema_status', ['menunggu', 'disetujui', 'ditolak', 'hangus'])->default('menunggu');
            $table->text('kema_note')->nullable();
            $table->timestamp('kema_approved_at')->nullable();
            $table->string('kema_approved_by', 255)->nullable();
            $table->text('admin_note')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable();

            // Constraint Foreign Key
            $table->foreign('room_id', 'fk_borrow_room')
                  ->references('id')
                  ->on('rooms')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrow_requests');
    }
};