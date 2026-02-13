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
        Schema::create('tb_user', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email');
            $table->string('password', 500);
            $table->string('full_name');
            $table->integer('role_id');
            $table->string('status')->default('active'); // gue kasih default biar aman
            $table->string('img')->nullable(); // nullable biar kalau belum ada foto gak error
            $table->timestamp('last_login')->nullable();
            $table->timestamps(); // ini otomatis bikin created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_user');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
