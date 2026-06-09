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
        Schema::create('item_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->string('unit_label')->nullable();  // Pcs,Box,Dozen
            $table->integer('unit_qty')->nullable();  // 1,10,12
            $table->string('color')->nullable();     // Red,Blue,Green
            $table->string('size')->nullable();         // S,M,L,XL,XXL
            $table->decimal('price', 10, 2)->nullable(); // variant ရဲ့စျေးနှုန်း (base price)
            $table->integer('stock_quantity')->nullable()->default(0); // variant ရဲ့လက်ကျန်
            $table->string('sku')->nullable();    // ပစ္စည်းကုဒ်
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_variants');
    }
};
