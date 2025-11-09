<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id('transfer_id');
            $table->unsignedBigInteger('item_id');
            $table->string('tr_from_unit');
            $table->string('tr_destination');
            $table->integer('tr_quantity');
            $table->string('tr_transfer_status');
            $table->string('tr_requested_by');
            $table->string('tr_received_by')->nullable();
            $table->date('tr_date_requested');
            $table->date('tr_date_received')->nullable();
            $table->text('tr_remarks')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('stock_transfers');
    }
};
