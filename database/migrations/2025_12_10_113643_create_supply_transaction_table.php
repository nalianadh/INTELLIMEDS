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
        Schema::create('supply_transaction', function (Blueprint $table) {
            $table->id(); // auto-increment primary key
            $table->date('Date');
            $table->string('Stock_ID', 50);
            $table->string('Stock', 255);
            $table->text('Brand')->nullable();
            $table->string('Site_Supplier', 255)->nullable();
            $table->string('Activity', 100)->nullable();
            $table->integer('Quantity');
            $table->string('Unit', 50)->nullable();
            $table->string('Demand_Level', 255)->nullable();
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_transaction');
    }
};
