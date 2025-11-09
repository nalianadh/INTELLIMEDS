<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('receive_notes', function (Blueprint $table) {
            $table->id('grn_id');
            $table->unsignedBigInteger('item_id');
            $table->string('grn_received_by');
            $table->integer('grn_quantity_received');
            $table->date('grn_date_received');
            $table->string('grn_supplier');
            $table->string('grn_po_number')->nullable();
            $table->text('grn_remarks')->nullable();
            $table->string('grn_itemExpiredDate', 7)->nullable();
            $table->string('grn_itemBatchNumber')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('receive_notes');
    }
};
