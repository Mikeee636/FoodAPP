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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom 'role' setelah 'password'
            // Anda bisa memilih tipe data string atau enum jika peran sudah ditentukan
            // Contoh dengan string, default 'customer'
            $table->string('role')->default('customer')->after('password');
            // Atau jika hanya ada beberapa peran tetap, bisa pakai enum:
            // $table->enum('role', ['admin', 'customer', 'staff'])->default('customer')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Saat rollback migrasi, hapus kolom 'role'
            $table->dropColumn('role');
        });
    }
};
