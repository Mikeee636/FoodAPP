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
        Schema::table('menus', function (Blueprint $table) {
            $table->integer('stock')->default(0)->after('price');
        $table->boolean('is_available')->default(true)->after('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
