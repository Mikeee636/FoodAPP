<?php
// In database/migrations/xxxx_xx_xx_xxxxxx_create_menus_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama makanan/minuman
            $table->text('description')->nullable(); // Deskripsi singkat
            $table->decimal('price', 10, 2); // Harga
            $table->enum('type', ['makanan', 'minuman']); // Jenis menu
            $table->string('image_url')->nullable(); // URL gambar (untuk sementara)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
