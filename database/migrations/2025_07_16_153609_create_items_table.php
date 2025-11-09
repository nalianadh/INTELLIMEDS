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
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id'); // Primary Key
            $table->string('i_name');
            $table->text('i_description')->nullable();
            $table->integer('i_reorderLevel')->nullable();
            $table->integer('i_quantity_in_stock')->nullable();
            $table->integer('i_minLevel')->nullable();
            $table->integer('i_maxLevel')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
