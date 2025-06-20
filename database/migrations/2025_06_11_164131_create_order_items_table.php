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
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();

        // Jika Order dihapus,itemnya ikut terhapus
        $table->foreignId('order_id')->constrained()->cascadeOnDelete();

        // Jika Menu dihapus, cegah penghapusan jika sudah ada di order
        $table->foreignId('menu_id')->constrained()->onDelete('restrict');

        $table->integer('quantity');
        $table->decimal('price', 10, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
